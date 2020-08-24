<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\InterestedFilterQueries;
use App\Exceptions\DBActionException;
use App\Models\InterestedFilter;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EloquentInterestedFilterQueries implements InterestedFilterQueries
{

    /**
     * @inheritDoc
     */
    public function create($userId, $data)
    {
        try {
            $data['user_id'] = $userId;
            $data['gender_code'] = (string)$data['gender_code'];
            return InterestedFilter::create($data);
        } catch (QueryException $exception) {

            Log::warning(' Query exception on create filter', [$exception]);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function update($user_id, $data)
    {
        try {

            if (isset($data['gender_code'])) {
                $data['gender_code'] = (string) $data['gender_code'];
            }
            $interested_user = InterestedFilter::whereUserId($user_id)->first();
            return $interested_user->update($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot update filter ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function findByUser($userId)
    {
        try {
            return InterestedFilter::whereUserId($userId)->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot update filter ', 503);
        }
    }
}