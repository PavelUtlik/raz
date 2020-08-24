<?php


namespace App\Http\Services;


use App\EloquentQueries\Api\Interfaces\ChatMessageFileQueries;
use App\EloquentQueries\Api\Interfaces\ChatMessageQueries;
use App\EloquentQueries\Api\Interfaces\InterestedFilterQueries;
use App\EloquentQueries\Api\Interfaces\MeetingChatQueries;
use App\EloquentQueries\Api\Interfaces\MeetingPhotoQueries;
use App\EloquentQueries\Api\Interfaces\MeetingQueries;
use App\EloquentQueries\Api\Interfaces\MeetingThemeQueries;
use App\EloquentQueries\Api\Interfaces\UserQueries;
use App\Exceptions\ErrorImplementServiceMethodException;
use App\Helpers\DateHelpers;
use App\Helpers\FileHelper;
use App\Helpers\MeetingStatuses;
use App\Http\Services\interfaces\IMeetingService;
use App\Jobs\DestroyFiles;
use App\Models\ChatMessageFile;
use App\Models\Meeting;
use App\Models\MeetingChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MeetingService implements IMeetingService
{

    private $meetingQueries;
    private $meetingPhotoQueries;
    private $meetingThemeQueries;
    private $interestedFilterQueries;
    private $userQueries;
    private $chatMessageFileQueries;
    private $meetingChatQueries;
    private $chatMessageQueries;


    public function __construct(
        MeetingQueries $meetingQueries,
        MeetingPhotoQueries $meetingPhotoQueries,
        MeetingThemeQueries $meetingThemeQueries,
        InterestedFilterQueries $interestedFilterQueries,
        UserQueries $userQueries,
        ChatMessageFileQueries $chatMessageFileQueries,
        MeetingChatQueries $meetingChatQueries,
        ChatMessageQueries $chatMessageQueries
    )
    {
        $this->userQueries = $userQueries;
        $this->meetingChatQueries = $meetingChatQueries;
        $this->meetingQueries = $meetingQueries;
        $this->meetingPhotoQueries = $meetingPhotoQueries;
        $this->meetingThemeQueries = $meetingThemeQueries;
        $this->interestedFilterQueries = $interestedFilterQueries;
        $this->chatMessageQueries = $chatMessageQueries;
        $this->chatMessageFileQueries = $chatMessageFileQueries;
    }


    public function checkPossibilityCreateMeeting($id)
    {
        $activeMeetingCount = $this->meetingQueries->getActiveCountByOwner(auth()->id());
        $diffSeconds = DateHelpers::differenceFromNowTime(auth()->user()->last_time_create_meeting);

        if (auth()->user()->is_vip) {

            /**
             * если вип то следующая встреча доступна сразу после окончания предыдущей
             */
            if ($activeMeetingCount > 0) {
                return response()->json([
                    "success" => false,
                    "message" => "The following free publication is available through:",
                    "timeToEnd" => DateHelpers::convertHourToSeconds(Meeting::VIP_LIFETIME_IN_HOURS) - $diffSeconds
                ], 200);
            }

            return response()->json([
                "success" => true
            ], 201);
        } else {

            /**
             * если нету времени значит пользователь не создавал встречи
             */
            if (!$diffSeconds) {
                return response()->json([
                    "success" => true,
                ], 200);
            }

            /**
             * если с момента начала прошло 12 часов
             */
            if (DateHelpers::convertSecondsToHours($diffSeconds) < config('meeting.time_between_creation')) {
                return response()->json([
                    "success" => false,
                    "message" => "The following free publication is available through:",
                    "timeToEnd" => DateHelpers::convertHourToSeconds(config('meeting.time_between_creation')) - $diffSeconds
                ], 200);
            }

            return response()->json([
                "success" => true,
            ], 200);

        }
    }


    /**
     * @inheritDoc
     * @throws \App\Exceptions\HelperMethodException
     */
    public function create($data)
    {
        /**
         * Проверки
         *  нужно в идеале сюда перенести функционал с метода checkPossibilityCreateMeeting
         */

        if (!empty($data['image'])) {

            $filename = FileHelper::storeBase64File(
                $data['image'],
                config('image.meeting_photo.save_path')
            );

            $meetingPhotoId = $this->meetingPhotoQueries->create($filename);
            $data['meeting_photo_id'] = $meetingPhotoId;
        }

        $data['meeting_status_code'] = MeetingStatuses::STARTED;
        $meetingLifeTime = auth()->user()->is_vip ? 2 : 1;
        $data['end_time'] = Carbon::now()->addHours($meetingLifeTime)->toDateTimeString();

        /**
         * Если не передан ид темы, то создаем новую
         */
        if (empty($data['meeting_theme_id'])) {
            $meeting_theme = $this->meetingThemeQueries->create([
                'name' => !empty($data['new_meeting_theme']) ? $data['new_meeting_theme'] : '-_/\_-',
                'user_id' => $data['owner_id']
            ]);
            $data['meeting_theme_id'] = $meeting_theme['id'];
        }

        $meeting = $this->meetingQueries->create($data);

        if ($meeting) {
            $this->userQueries->update(auth()->id(), ['last_time_create_meeting' => $meeting->created_at]);
        }

        return response()->json([
            'message' => 'Successfully created meeting'
        ], 201);
    }


    /**
     * @inheritDoc
     */
    public function updateTheme($data)
    {
        $meetingId = $data['meeting_id'];

        /**
         * если  передали новую тему  new_meeting_theme
         */
        if (empty($data['meeting_theme_id'])) {

            $newMeetingTheme = $this->meetingThemeQueries->create([
                    'user_id' => auth()->id(),
                    'name' => !empty($data['new_meeting_theme']) ? $data['new_meeting_theme'] : '-_/\_-',
                ]
            );

            $meetingThemeId = $newMeetingTheme->id;

        } else {
            $meetingThemeId = $data['meeting_theme_id'];
        }

        $this->meetingQueries->update($meetingId, ['meeting_theme_id' => $meetingThemeId]);

        return response()->json([
            'message' => 'Successfully updated meeting theme'
        ], 202);
    }

    /**
     *
     * @inheritDoc
     * @throws \App\Exceptions\HelperMethodException
     */
    public function updatePhoto($meeting_id, $photo)
    {

        $savePath = config('image.meeting_photo.save_path');
        /**
         * сначала сохраняем новую фотографию на сервер
         */
        $newFilename = FileHelper::storeBase64File(
            $photo,
            $savePath
        );

        try {

            /**
             * Далее удалаяем старую
             */
            $meeting = $this->meetingQueries->findWith($meeting_id, ['photo']);
            $oldFilename = $meeting->photo->name;
            FileHelper::deleteFile($oldFilename, $savePath);

            /**
             * Заменяем на новую
             */
            $this->meetingPhotoQueries->update($meeting->meeting_photo_id, ['name' => $newFilename]);

            return response()->json([
                'message' => 'Successfully updated photo'
            ], 202);

        } catch (ErrorImplementServiceMethodException $exception) {
            /**
             * если словили исключение удалаяем загруженное фото с сервера
             */
            FileHelper::deleteFile($newFilename, $savePath);
            Log::warning('Error update photo', [$exception]);

            return response()->json([
                'message' => 'Error update photo'
            ], 422);
        }
    }


    /**
     * Данный метод не удаляет встречу физически, а лишь изменяет статус,
     *  но удаляются все сообщения из встречи, информация об участниках
     * @param $meetingId
     * @param $userId
     * @param bool $ignoreOwnerCheck
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws ErrorImplementServiceMethodException
     */
    public function destroy($meetingId, $userId, $ignoreOwnerCheck = false)
    {
        $meeting = $this->meetingQueries->find($meetingId);

        if (!$meeting) {
            throw  new ErrorImplementServiceMethodException('meeting not found', 422);
        }

        if ($meeting->owner_id !== $userId && !$ignoreOwnerCheck) {
            throw  new ErrorImplementServiceMethodException('current user doesnt delete this meeting, permission denied', 422);
        }

        $deletingFiles = [];

//        if ($meeting->photo) {
//            $deletingFiles[] = config('image.meeting_photo.save_path') . '/' . $meeting->photo->name;
//            $this->meetingPhotoQueries->destroy($meeting->photo->id);
//        }

        $meetingChatMessageFiles = $this->chatMessageFileQueries->getByMeeting($meetingId);

        if ($meetingChatMessageFiles->isNotEmpty()) {

            $meetingChatFiles = FileHelper::fetchFullFilePaths(
                $meetingChatMessageFiles->pluck('name')->toArray(),
                config('image.meeting_chat_photo.save_path')
            );

            $deletingFiles = array_merge($deletingFiles, $meetingChatFiles);

            $this->chatMessageFileQueries->destroy($meetingChatMessageFiles->pluck('id'));

        }

        if (count($deletingFiles) > 0) {
            DestroyFiles::dispatch($deletingFiles);
        }

        $this->meetingQueries->update($meetingId, [
            'meeting_status_code' => $userId ? MeetingStatuses::USER_DELETED : MeetingStatuses::CRON_DELETED,
            'deleted_messages_counter' => $this->chatMessageQueries->countByMeeting($meetingId)
        ]);

        $this->meetingChatQueries->destroyByMeeting($meetingId);

        return response()->json([
            'message' => "meeting successfully deleted"
        ], 202);
    }

    public function checkEndMeeting($id)
    {
        if (!empty($id)) {
            $meeting = $this->meetingQueries->find($id);

            $result = DateHelpers::checkEndEvent($meeting['end_time']);
            return response()->json([
                'message' => $result
            ], 200);
        }
    }

    public function howMuchToTheEndMeeting($id)
    {
        $meeting = $this->meetingQueries->find($id);
        if (DateHelpers::checkEndEvent($meeting['end_time']) === false) {
            return DateHelpers::differenceFromNowTime($meeting['end_time']);
        }
    }


    /**
     * @inheritDoc
     */
    public function search($userId)
    {

        $interestedFilter = $this->interestedFilterQueries->findByUser($userId);

        if (!$interestedFilter) {
            throw new ErrorImplementServiceMethodException('Filter not found for user', 404);
        }
        return $this->meetingQueries->search($interestedFilter, $userId);

    }

    /**
     * @inheritDoc
     */
    public function updateTime($meeting_id, $time)
    {

        $meeting = $this->meetingQueries->find($meeting_id);

        if (!$meeting) {
            throw  new ErrorImplementServiceMethodException('meeting not found', 422);
        }

        if ($meeting->owner_id !== auth()->id()) {
            throw  new ErrorImplementServiceMethodException('you must be owner meeting', 422);
        }

        /**
         * если мы продлевали встречу больше 3 раз то бросаем исключение
         */
        if ($meeting->time_extension_counter >= Meeting::MAX_EXTENDED_TIME_COUNT) {
            throw  new ErrorImplementServiceMethodException('max extended time limit exceeded', 422);
        }

        $this->meetingQueries->addTimeToMeeting($meeting_id, $time);

        return response()->json([
            'message' => "Meeting life time extended by " . $time . " minutes"
        ], 202);
    }
}




















