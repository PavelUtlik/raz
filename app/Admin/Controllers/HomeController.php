<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingChatMessage;
use App\Models\User;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {


        return $content
            ->row(function (Row $row) {

                $userInfoBox = new InfoBox('Пользователи', 'user', 'aqua', '/admin/users', User::count());
                $meetingInfoBox = new InfoBox('Созданные встречи', 'users', 'green', '/admin/meetings', Meeting::count());
                $messageInfoBox = new InfoBox('Сообщения', 'comments', 'yellow', null, Meeting::sum('deleted_messages_counter') + MeetingChatMessage::count());


                $row->column(4, function (Column $column) use ($userInfoBox) {
                    $column->append($userInfoBox->render());
                });

                $row->column(4, function (Column $column) use ($meetingInfoBox) {
                    $column->append($meetingInfoBox->render());
                });

                $row->column(4, function (Column $column) use ($messageInfoBox) {
                    $column->append($messageInfoBox->render());
                });


            });
    }
}
