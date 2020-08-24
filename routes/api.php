<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
    'namespace' => 'Api\V1',
], function () {

    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('email/and/password/validate', 'AuthController@validateEmailAndPassword');

    /**
     *  GEO
     */
    Route::group([
        'prefix' => 'geo',
    ], function () {
        Route::get('countries', 'GeoController@getCountries');
        Route::get('cities', 'GeoController@getCities');
    });

    /**
     *  PASSWORD RESET
     */
    Route::group([
        'prefix' => 'password/reset',
    ], function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
    });


    /**
     * AUTH ROUTES
     */
    Route::group([
        'middleware' => ['auth:api', 'userBlocked']
    ], function () {
        Route::get('logout', 'AuthController@logout')->name('logout');

        /**
         * USER
         */
        Route::group([
            'prefix' => 'user',
        ], function () {
            Route::get('/', 'UserController@user')->name('user');
            Route::post('check-vip', 'UserController@checkVip');
            Route::patch('mark-as-vip', 'UserController@markAsVip');
            Route::put('update', 'UserController@update');

            /**
             * USER PHOTO
             */
            Route::get('{user_id}/photos/get', 'UserPhotoController@getByUserId');
            Route::group([
                'prefix' => 'photo',
            ], function () {
                Route::delete('delete', 'UserPhotoController@destroy');
                Route::get('{photo_id}/check-is-main', 'UserPhotoController@checkIsMain');
                Route::post('add', 'UserPhotoController@add');
                Route::put('make-main', 'UserPhotoController@makeMain');
            });
        });

        /**
         *  INTERESTED FILTER
         */
        Route::put('interested-filter/update', 'InterestedFilterController@update');

        /**
         * FEEDBACK
         */
        Route::post('feedback/meeting-complaint', 'FeedbackController@meetingComplaint');

        /**
         * MEETING
         */
        Route::group([
            'prefix' => 'meeting',
        ], function () {
            Route::post('/', 'MeetingController@store');
            Route::delete('{id}', 'MeetingController@destroy');
            Route::get('user/active', 'MeetingController@findActiveByOwner');
            Route::get('search/{page?}', 'MeetingController@search');
            Route::patch('photo/update', 'MeetingController@updatePhoto');
            Route::post('create-check', 'MeetingController@checkPossibilityCreateMeeting');

            /**
             * MEETING THEME
             */
            Route::group([
                'prefix' => 'theme',
            ], function () {
                Route::get('get', 'MeetingController@getTheme');
                Route::post('create', 'MeetingController@createTheme');
                Route::patch('update', 'MeetingController@updateTheme');
            });

            /**
             * MEETING TIME
             */
            Route::group([
                'prefix' => 'time',
            ], function () {
                Route::post('to-end', 'MeetingController@timeToEnd');
                Route::patch('update', 'MeetingController@updateTime');
                Route::post('check-end', 'MeetingController@checkEndTime');
            });

            /**
             * MEETING CHAT
             */
            Route::group([
                'prefix' => 'chat',
            ], function () {
                Route::post('/', 'MeetingChatController@store');
                Route::get('user', 'MeetingChatController@getByUser');
                Route::get('{chatId}/check-block', 'MeetingChatController@checkBlock');
                Route::patch('{chatId}/block', 'MeetingChatController@block');
                Route::patch('{chatId}/unblock', 'MeetingChatController@unblock');

                /**
                 * MEETING CHAT MESSAGE
                 */
                Route::get('{chatId}/message', 'MeetingChatMessageController@getMessages');
                Route::group([
                    'prefix' => 'message',
                ], function () {
                    Route::post('/', 'MeetingChatMessageController@sendMessage');
                    Route::get('unread', 'MeetingChatMessageController@getUnread');
                    Route::post('mark-as-read', 'MeetingChatMessageController@markAsReadByUniqueId');
                });
            });
        });
    });


});
















