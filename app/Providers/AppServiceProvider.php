<?php

namespace App\Providers;

use App\EloquentQueries\Api\EloquentChatMessageFileQueries;
use App\EloquentQueries\Api\EloquentChatMessageQueries;
use App\EloquentQueries\Api\EloquentGeoQueries;
use App\EloquentQueries\Api\EloquentInterestedFilterQueries;
use App\EloquentQueries\Api\EloquentMeetingChatQueries;
use App\EloquentQueries\Api\EloquentMeetingPhotoQueries;
use App\EloquentQueries\Api\EloquentMeetingQueries;
use App\EloquentQueries\Api\EloquentMeetingThemeQueries;
use App\EloquentQueries\Api\EloquentUserPhotoQueries;
use App\EloquentQueries\Api\EloquentUserQueries;
use App\EloquentQueries\Api\Interfaces\ChatMessageFileQueries;
use App\EloquentQueries\Api\Interfaces\ChatMessageQueries;
use App\EloquentQueries\Api\Interfaces\GeoQueries;
use App\EloquentQueries\Api\Interfaces\InterestedFilterQueries;
use App\EloquentQueries\Api\Interfaces\MeetingChatQueries;
use App\EloquentQueries\Api\Interfaces\MeetingPhotoQueries;
use App\EloquentQueries\Api\Interfaces\MeetingQueries;
use App\EloquentQueries\Api\Interfaces\MeetingThemeQueries;
use App\EloquentQueries\Api\Interfaces\UserPhotoQueries;
use App\EloquentQueries\Api\Interfaces\UserQueries;
use App\Http\Services\interfaces\IMeetingChatService;
use App\Http\Services\interfaces\IMeetingService;
use App\Http\Services\interfaces\IUserPhotoService;
use App\Http\Services\interfaces\IUserService;
use App\Http\Services\MeetingChatService;
use App\Http\Services\MeetingService;
use App\Http\Services\UserPhotoService;
use App\Http\Services\UserService;
use App\Models\InterestedFilter;
use App\Models\MeetingChat;
use App\Models\MeetingChatMessage;
use App\Models\User;
use App\Models\UserPhoto;
use App\Observers\InterestedFilterObserver;
use App\Observers\MeetingChatMessageObserver;
use App\Observers\UserObserver;
use App\Observers\UserPhotoObserver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);

        setlocale(LC_TIME, 'ru_RU.UTF-8');
        Carbon::setLocale(config('app.locale'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        User::observe(UserObserver::class);
        InterestedFilter::observe(InterestedFilterObserver::class);
        UserPhoto::observe(UserPhotoObserver::class);
        MeetingChatMessage::observe(MeetingChatMessageObserver::class);


        $this->app->bind(UserQueries::class, EloquentUserQueries::class);
        $this->app->bind(InterestedFilterQueries::class, EloquentInterestedFilterQueries::class);
        $this->app->bind(UserPhotoQueries::class, EloquentUserPhotoQueries::class);
        $this->app->bind(MeetingThemeQueries::class, EloquentMeetingThemeQueries::class);
        $this->app->bind(MeetingPhotoQueries::class, EloquentMeetingPhotoQueries::class);
        $this->app->bind(MeetingQueries::class, EloquentMeetingQueries::class);
        $this->app->bind(MeetingChatQueries::class, EloquentMeetingChatQueries::class);
        $this->app->bind(ChatMessageFileQueries::class, EloquentChatMessageFileQueries::class);
        $this->app->bind(ChatMessageQueries::class, EloquentChatMessageQueries::class);
        $this->app->bind(GeoQueries::class, EloquentGeoQueries::class);

        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IUserPhotoService::class, UserPhotoService::class);
        $this->app->bind(IMeetingService::class, MeetingService::class);
        $this->app->bind(IMeetingChatService::class, MeetingChatService::class);


    }
}




















