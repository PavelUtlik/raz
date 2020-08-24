<?php

namespace App\Admin\Controllers;

use App\Models\InterestedFilter;
use App\Models\Meeting;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserPhoto;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HasResourceActions;


    const BLOCKED_STATES = [
        'off' => ['value' => 0, 'text' => 'Нет'],
        'on' => ['value' => 1, 'text' => 'Да']
    ];

    public function index(Content $content)
    {
        return $content
            ->header('Список пользователей')
            ->body($this->grid());
    }


    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.detail'))
            ->description(trans('admin.description'))
            ->body($this->detail($id));
    }


    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description(trans('admin.description'))
            ->body($this->form($id)->edit($id));
    }


    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->enableHotKeys();
        $grid->expandFilter();
        $grid->model()->orderBy('id', 'desc');


        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            $filter->like('email', 'E-mail');

            $filter->equal('is_vip', 'Вип')->radio([
                '' => 'Все',
                0 => 'Нет',
                1 => 'Да',
            ]);

            $filter->equal('is_blocked', 'Бан')->radio([
                '' => 'Все',
                0 => 'Нет',
                1 => 'Да',
            ]);

            $filter->between('created_at', 'Дата регистрации')->datetime();

        });

        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableBatchActions();


        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->id('ID');

        $grid->email('E-mail')->display(function ($email) {

            return "<a href='" . url('admin/users/' . $this->id . '/edit') . "'>{$email}</a>";

        });


        $grid->column('gender.name', 'Пол');
        $grid->photo()->gallery(['server' => config('image.user_photo.url'), 'width' => 50, 'height' => 50]);
        $grid->column('is_vip', 'VIP')->switch(self::BLOCKED_STATES);
        $grid->column('is_blocked', 'Бан')->switch(self::BLOCKED_STATES);


        $grid->ownerMeetings('Встречи')->display(function ($meetings) {
            $count = count($meetings);
            return "<span class='label label-warning'>{$count}</span>";
        });

        $grid->created_at('Дата регистрации');


        return $grid;
    }


    protected function form($id)
    {
        $form = new Form(new User());


        $form->display('created_at', 'Дата регистрации');
        $form->display('email', 'E-mail');
        $form->display('name', 'Имя');
        $form->display('date_of_birth', 'Дата рождения');
        $form->display('gender.name', 'Пол');


        $interestedFilter = InterestedFilter::whereUserId($id)->first();
        if ($interestedFilter) {
            $form->html("
                <div class='box box-solid box-default no-margin'>
                    <!-- /.box-header -->
                    <div class='box-body'>
                        {$interestedFilter->latitude}&nbsp;
                    </div><!-- /.box-body -->
                </div>
            ", 'Широта');
            $form->html("                 
                <div class='box box-solid box-default no-margin'>
                    <!-- /.box-header -->
                    <div class='box-body'>
                        {$interestedFilter->longitude}&nbsp;
                    </div><!-- /.box-body -->
                </div>                
           ", 'Долгота');
        }

        $form->switch('is_vip', 'VIP')->states(self::BLOCKED_STATES);
        $form->switch('is_blocked', 'Бан')->states(self::BLOCKED_STATES);


        $photos = UserPhoto::where('user_id', $id)->get();

        if ($photos->isNotEmpty()) {
            $photos = $photos->pluck('name')->map(function ($name) {
                          return config('image.user_photo.url') . $name;
            });
            $form->html(view('admin.partials.list-images', ['photos' => $photos])->render(), 'Фотографии');
        }

        $meetings = Meeting::select('*')
            ->withCount('messages')
            ->addSelect(DB::raw("(SELECT  CONCAT('" . config('image.meeting_photo.url') . "',name) FROM meeting_photos WHERE meeting_photos.id = meeting_photo_id) AS photo_url"))
            ->addSelect(DB::raw("(SELECT name FROM meeting_themes WHERE meeting_themes.id = meeting_theme_id) AS theme"))
            ->where('owner_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        if ($meetings->isNotEmpty()) {
            $form->html(view('admin.partials.meeting-table', ['meetings' => $meetings])->render(), 'Созданные встречи');
        }

        return $form;
    }
}
















