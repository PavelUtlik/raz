<?php


use App\Models\Meeting;
use App\Models\MeetingChat;
use App\Models\MeetingChatMessage;
use App\Models\User;
use App\Notifications\UserBlocked;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Monolog\Handler\StreamHandler;
use Spatie\ImageOptimizer\OptimizerChainFactory;


Auth::routes();

Route::get('/', function () {


    return view('chat');
});
Route::get('/test', function () {
//    dd(\Cache::get('TestUser'));
phpinfo();


});


















