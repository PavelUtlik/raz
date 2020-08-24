<?php


namespace App\Http\Services;


use App\EloquentQueries\Api\EloquentUserPhotoQueries;
use App\Exceptions\ErrorImplementServiceMethodException;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Http\Resources\UserResource;
use App\Http\Services\interfaces\IUserPhotoService;
use http\Env\Request;
use Illuminate\Support\Facades\File;


class UserPhotoService implements IUserPhotoService
{
    private $photoQueries;

    public function __construct(EloquentUserPhotoQueries $photoQueries)
    {
        $this->photoQueries = $photoQueries;
    }

    public function addPhoto($data, $isMain = 0)
    {
        FileHelper::checkBase64Format($data['image']);
        $userId = $data['user_id'];
        $count = $this->photoQueries->countUserPhoto($userId);

        if (gettype($count) != 'integer') {
            throw new ErrorImplementServiceMethodException('Error get count photo', 422);
        }

        if ($count >= config('image.user_photo.limit')) {
            throw new ErrorImplementServiceMethodException('photo limit exceeded', 422);
        }

        if (!empty($data['is_main']) && $isMain == 0) {
            $isMain = $data['is_main'];
        }

        if (false === $this->photoQueries->addPhoto($userId, $data['image'], $isMain)) {
            throw new ErrorImplementServiceMethodException('Can"t add photo', 422);
        }

        return true;
    }

    /**
     * todo: не используется в данный момент
     * @inheritDoc
     */
    public function addPhotos($data)
    {
        throw new ErrorImplementServiceMethodException('method outdated', 422);
        $countUserPhoto = $this->photoQueries->countUserPhoto($data['user_id']);
        $countRequestUserPhoto = count($data['image']);

        foreach ($data['image'] as $image) {
            FileHelper::checkBase64Format($image);
        }

        if (gettype($countUserPhoto) != 'integer') {
            throw new ErrorImplementServiceMethodException('Error get count photo', 422);
        }

        if ($countUserPhoto + $countRequestUserPhoto > config('image.user_photo.limit')) {
            throw new ErrorImplementServiceMethodException('photo limit exceeded', 422);
        }

        if (false === $this->photoQueries->addPhotos($data['user_id'], $data['image'])) {
            throw new ErrorImplementServiceMethodException('Can"t add photo', 422);
        }

        return true;
    }

    public function updatePhoto($userId, $data)
    {
        return $this->photoQueries->updateIsMain($userId, $data);
    }

    public function delete($id)
    {
        $photo = $this->photoQueries->get($id);
        $this->photoQueries->destroy($id);

        $deletePath = config('image.user_photo.save_path');

        FileHelper::deleteFile($photo['name'], $deletePath);

        return true;
    }


}