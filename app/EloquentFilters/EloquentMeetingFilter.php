<?php

namespace App\EloquentFilters;

use App\Helpers\DateHelpers;
use App\Helpers\DistanceHelpers;
use App\Helpers\GenderCodes;
use App\Models\InterestedFilter;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentMeetingFilter
{
    private $query;

    /**
     * @var InterestedFilter
     */
    private $filter;

    public function __construct(InterestedFilter $filter)
    {

        $this->query = Meeting::with(['owner' => function($query){
            $query->select('id','name','date_of_birth','is_vip');
            $query->addSelect(DB::raw("(SELECT CONCAT('" . config('image.user_photo.url') . "',name) FROM user_photos WHERE user_id=users.id AND is_main=1) as photo_url"));

        }, 'theme', 'status', 'photo' => function ($query) {
            $query->select('*');
            $query->addSelect(DB::raw("CONCAT('" . config('image.meeting_photo.url') . "',name) AS url"));
        }]);
        $this->filter = $filter;
    }

    public function get($ownerId = null)
    {
        $this->query->whereHas('owner', function ($ownerQuery) {
            $this->searchByAge($ownerQuery);
            $this->searchByGender($ownerQuery);
        });

        $this->filterByDistance();

        if ($ownerId) {
            $this->addChat($ownerId);
            $this->query->ignoreOwner($ownerId);
        }


        return $this->query
            ->active()
            ->activeStatus()
            ->paginate(20);
    }

    /**
     * query scope https://laravel.com/docs/5.8/eloquent#query-scopes
     */
    private function filterByDistance()
    {
        $this->query->closeTo($this->filter->latitude, $this->filter->longitude, $this->filter->max_location_range);
    }

    private function searchByGender($ownerQuery)
    {
        $searchGender = $this->filter->gender_code;
        if ($searchGender != GenderCodes::ANY_GENDER) {
            $ownerQuery->whereHas('gender', function ($query) use ($searchGender) {
                $query->where('gender_code', $searchGender);
            });
        }
    }

    private function searchByAge($ownerQuery)
    {
        $ageRange = DateHelpers::getAgeRange($this->filter->min_age, $this->filter->max_age);
        $ownerQuery->whereBetween('date_of_birth', [$ageRange['ageTo'], $ageRange['ageFrom']]);
    }

    private function addChat($ownerId)
    {
        /**
         * добавим чат если существует
         */
        $this->query->with(['chats' => function ($query) use ($ownerId) {
            $query->whereHas('users', function ($query) use ($ownerId) {
                $query->where('user_id', $ownerId);
            });
        }]);
    }

}


