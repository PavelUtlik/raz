<?php

namespace App\Admin\Controllers;

use App\Models\Meeting;
use App\Http\Controllers\Controller;
use App\Models\MeetingStatus;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MeetingController extends Controller
{
    use HasResourceActions;


    public function index(Content $content)
    {
        return $content
            ->header(trans('Список встреч'))
            ->body($this->grid());
    }


    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description(trans('admin.description'))
            ->body($this->form()->edit($id));
    }


    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.create'))
            ->description(trans('admin.description'))
            ->body($this->form());
    }


    protected function grid()
    {
        $grid = new Grid(new Meeting());
        $grid->enableHotKeys();
        $grid->expandFilter();
        $grid->model()->orderBy('id', 'desc');


        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            $filter->where(function ($query) {
                $query->whereHas('owner', function ($query) {
                    $query->where('email', 'like', "%{$this->input}%");
                });
            }, 'E-mail');

            $filter->between('created_at', 'Дата создания')->datetime();

        });

        $grid->column('owner.email', 'Владелец')->display(function ($email) {
            return "<a href='" . url('admin/users/' . $this->owner->id . '/edit') . "'>{$email}</a>";
        });

        $grid->column('ownerGender.name', 'Пол');
        $grid->column('theme.name', 'Тема встречи');


        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableActions();
        $grid->disableBatchActions();


        $grid->column('photo.name', 'Фотография встречи')->display(function ($image) use ($grid) {
            return $image
                ? view('admin.partials.list-images', ['photos' => [config('image.meeting_photo.url') . $image]])->render()
                : null;
        });

        $grid->column('Количество сообщений')->display(function () {
            return "<span class='label label-warning'>" . ($this->messages->count() + $this->deleted_messages_counter) . "</span>";
        });

        $grid->column('created_at', 'Дата и время создания')->date('h:i d.m.Y');

        $grid->column('meeting_status_code', 'Статус')->editable('select', MeetingStatus::get()->pluck('name', 'code'));
        $grid->column('complaint_counter', 'Количество жалоб');

        return $grid;
    }


    protected function form()
    {
        $form = new Form(new Meeting());

        $form->display('ID');
        $form->text('name', 'name');
        $form->select('meeting_status_code', 'Статус')->options(MeetingStatus::get()->pluck('name', 'code'));

        return $form;
    }
}
