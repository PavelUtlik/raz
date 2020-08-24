<?php

namespace App\Http\Services;

use App\EloquentQueries\Api\Interfaces\InterestedFilterQueries;
use App\EloquentQueries\Api\Interfaces\UserPhotoQueries;
use App\EloquentQueries\Api\Interfaces\UserQueries;
use App\Exceptions\DBActionException;
use App\Exceptions\ErrorImplementServiceMethodException;
use App\Helpers\UserPhotoCodes;
use App\Http\Resources\UserResource;
use App\Http\Services\interfaces\IUserPhotoService;
use App\Http\Services\interfaces\IUserService;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UserService implements IUserService
{

    private $userQueries;
    private $interestedFilterQueries;
    private $userPhotoService;

    public function __construct(UserQueries $userQueries, InterestedFilterQueries $interestedFilterQueries, IUserPhotoService $userPhotoService)
    {
        $this->userQueries = $userQueries;
        $this->interestedFilterQueries = $interestedFilterQueries;
        $this->userPhotoService = $userPhotoService;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function register($data)
    {
        try {

            $user = $this->userQueries->create($data);
            if (!$user) {
                throw new ErrorImplementServiceMethodException('User does not create');
            }

            $userId = $user->id;

            if (!empty($data['image'])) {
                $data['user_id'] = $userId;
                $this->userPhotoService->addPhoto($data, 1);
            }

            $interestedFilter = $this->interestedFilterQueries->create($userId, $data);

            $this->userQueries->update($userId, [
                'interested_filter_id' => $interestedFilter->id
            ]);

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'message' => 'Successfully created user'
            ], 201);

        } catch (QueryException $exception) {
            Log::warning('Transaction Query exception on register user', [$exception]);
            throw new DBActionException('Transaction Query exception on register user', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getUser($id)
    {
        return $this->userQueries->find($id);
    }

    /**
     * @inheritDoc
     */
    public function update($id, $data)
    {
        return $this->userQueries->update($id, $data);
    }

    public function markAsVip($id)
    {
        return $this->userQueries->update($id, ['is_vip' => 1]);
    }

}





















