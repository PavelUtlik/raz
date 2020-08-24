<?php


namespace App\Http\Services;


use App\EloquentQueries\Api\Interfaces\ChatMessageFileQueries;
use App\EloquentQueries\Api\Interfaces\ChatMessageQueries;
use App\EloquentQueries\Api\Interfaces\MeetingChatQueries;
use App\EloquentQueries\Api\Interfaces\MeetingQueries;
use App\Events\Chat;
use App\Events\MeetingChatMessage;
use App\Exceptions\ErrorImplementServiceMethodException;
use App\Helpers\ChatMessageTypeCode;
use App\Helpers\FileHelper;
use App\Helpers\WebSocketResponseGenerator;
use App\Http\Services\interfaces\IMeetingChatService;
use App\Models\ChatMessageFile;
use App\Models\MeetingChat;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MeetingChatService implements IMeetingChatService
{

    private $meetingChatQueries;
    private $meetingQueries;
    private $chatMessageFileQueries;
    private $chatMessageQueries;

    public function __construct(
        MeetingChatQueries $meetingChatQueries,
        MeetingQueries $meetingQueries,
        ChatMessageFileQueries $chatMessageFileQueries,
        ChatMessageQueries $chatMessageQueries
    )
    {
        $this->meetingChatQueries = $meetingChatQueries;
        $this->meetingQueries = $meetingQueries;
        $this->chatMessageFileQueries = $chatMessageFileQueries;
        $this->chatMessageQueries = $chatMessageQueries;
    }

    /**
     * Create chat and add users
     * @inheritDoc
     */
    public function store($senderId, $meetingId)
    {
        /**
         * список проверок
         * 1. Не создан ли чат с такой комбинацией пользователей и встречей +
         * 2. Существует ли встреча +
         */

        $meeting = $this->meetingQueries->findActive($meetingId);


        if (!$meeting) {
            throw new ErrorImplementServiceMethodException('Meeting not found or delete', 422);
        }

        $ownerId = $meeting->owner_id;

        if ($this->meetingChatQueries->isExist($senderId, $meetingId)) {
            Log::warning('Сhat already exist: sender: ' . $senderId . ' recep: ' . $ownerId . ' meet: ' . $meetingId);
            throw new ErrorImplementServiceMethodException('Сhat already exist', 422);

        }

        $chat = $this->meetingChatQueries->store([
            'meeting_id' => $meetingId
        ]);
        $this->meetingChatQueries->attachUsers($senderId, $ownerId, $chat);

        return $chat;
    }

    /**
     * @inheritDoc
     */
    public function destroy($chatId)
    {
        return $this->meetingChatQueries->destroy($chatId);
    }

    public function leave($userId, $uniqueChatId)
    {
        $this->meetingChatQueries->detachUser(
            $userId,
            $this->meetingChatQueries->findByUnique($uniqueChatId)
        );

        return response()->json([
            'message' => 'Successfully detach user from chat'
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function sendMessage($senderId, $data)
    {
        /**
         * список проверок
         * 1. не заблокирован ли чат +
         * 2. являемся ли мы пользователем этого чата +
         * 3. не закончилось ли время встречи +
         * 4. существет ли чат вообще +
         * 5. Относится ли чат к данной встрече +
         */

        $meetingChat = $this->meetingChatQueries->getByIdAndParticipant(
            $data['meeting_chat_id'],
            $senderId
        );

        if (!$meetingChat) {
            throw new ErrorImplementServiceMethodException('Meeting chat not found or user is not a member of the chat', 422);
        }

        $meeting = $this->meetingQueries->findActive($data['meeting_id']);

        if (!$meeting) {
            throw new ErrorImplementServiceMethodException('Meeting not found or deleted', 422);
        }

        if ($meeting->end_time < Carbon::now()->toDateTimeString()) {
            throw new ErrorImplementServiceMethodException('Meeting is end', 422);
        }

        if ($meetingChat->meeting_id !== $meeting->id) {
            throw new ErrorImplementServiceMethodException('Chat does not apply to this meeting', 422);
        }

        if ($meetingChat->is_blocked == 1) {
            throw new ErrorImplementServiceMethodException('Chat has been blocked', 422);
        }

        if ((int)$data['chat_message_type_id'] === ChatMessageTypeCode::IMAGE) {

            try {
                $filename = FileHelper::storeBase64File(
                    $data['message'],
                    config('image.meeting_chat_photo.save_path')
                );

                $dbFile = $this->chatMessageFileQueries->create($senderId, ['name' => $filename]);

                $data['message'] = config('image.meeting_chat_photo.url') . $filename;
                $data['chat_message_file_id'] = $dbFile->id;

            } catch (Exception $exception) {

                Log::warning('error store chat image', [$exception]);
                throw new ErrorImplementServiceMethodException('Error store chat image', 503);
            }


        }

        // добавить в очередь
        $chatMessage = $this->chatMessageQueries->create($senderId, $data);

        if (!$chatMessage) {
            throw new ErrorImplementServiceMethodException('Error store chat message', 503);
        }

        $data['sender_id'] = $senderId;
        $data['sender_name'] = auth()->user()->name;
        $data['time'] = Carbon::now()->unix();
        MeetingChatMessage::dispatch($data);

        return response()->json([
            'message' => 'Successfully created message'
        ], 201);
    }

    /**
     * @inheritDoc
     */
    public function getMessages($userId, $chatId)
    {
        /**
         * проверим может ли пользователь просматривать сообщения
         */
        $meetingChat = $this->meetingChatQueries->getByIdAndParticipant($chatId, $userId);
        if (!$meetingChat) {
            throw new ErrorImplementServiceMethodException('Meeting chat not found or user is not a member of the chat', 422);
        }

        return $this->chatMessageQueries->getByChatId($chatId);
    }

    /**
     * @inheritDoc
     */
    public function block($chatId, $whoBlockedId)
    {

        /**
         * проверим является ли пользователем участником чата и существует ли он вообще
         */
        $meetingChat = $this->meetingChatQueries->getByIdAndParticipant($chatId, $whoBlockedId);
        if (!$meetingChat) {
            throw new ErrorImplementServiceMethodException('Meeting chat not found or user is not a member of the chat', 422);
        }

        $this->meetingChatQueries->block($chatId, $whoBlockedId);

        return response()->json([
            'message' => 'Successfully block chat'
        ], 202);

    }


    /**
     * @inheritDoc
     */
    public function unblock($chatId, $whoUnblockedId)
    {

        /**
         * проверим является ли пользователем участником чата и существует ли он вообще
         */
        $meetingChat = $this->meetingChatQueries->getByIdAndParticipant($chatId, $whoUnblockedId);
        if (!$meetingChat) {
            throw new ErrorImplementServiceMethodException('Meeting chat not found or user is not a member of the chat', 422);
        }

        /**
         * проверим может ли пользователь разблокировать чат, может в том случае если он блокировал его
         */
        if ($meetingChat->who_blocked !== $whoUnblockedId) {
            throw new ErrorImplementServiceMethodException('User does not unblocked this chat because it was blocked by another user or it is not blocked at all', 422);
        }

        $this->meetingChatQueries->unblock($chatId);

        return response()->json([
            'message' => 'Successfully unblock chat'
        ], 202);

    }


    /**
     * @inheritDoc
     */
    public function checkBlock($chatId, $whoCheckedId)
    {
        $meetingChat = $this->meetingChatQueries->findWithParticipants($chatId);

        if (!$meetingChat) {
            throw new ErrorImplementServiceMethodException('Meeting chat not found', 422);
        }

        if ($meetingChat->users && !$meetingChat->users->where('id', $whoCheckedId)->first()) {
            throw new ErrorImplementServiceMethodException('You doesnt participant this chat', 422);
        }

        return $meetingChat->is_blocked === 1
            ? response()->json([
                'result' => true,
                'whoBlockedId' => $meetingChat->who_blocked
            ], 200)
            : response()->json([
                'result' => false
            ], 200);

    }


}


















