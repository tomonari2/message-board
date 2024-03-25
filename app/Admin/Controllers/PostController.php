<?php

namespace App\Admin\Controllers;

use App\Post;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Post';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post());

        $grid->column('id', __('ID'));
        $grid->column('user_id', __('ユーザーID'));
        $grid->column('content', __('メッセージ'));
        // $grid->column('image_path', __('Image path'));

        $grid->column('image_path', __('画像'))->image('/', 100, 100);

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('content', __('Content'));
        $show->field('image_path', __('Image path'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Post());
        //編集のフォーム
        if ($form->isEditing()) {
            $form->text('id', __('Id'));
            $form->text('user_id', __('User id'));
            $form->text('content', __('Content'));
            $form->text('image_path', __('Image path'));
            $form->text('created_at', __('Created at'));
            $form->text('updated_at', __('Updated at'));
        } else {
          $form->text('user_id', __('ユーザーID'));
        }

        $form->number('user_id', __('User id'));
        $form->textarea('content', __('Content'));
        $form->text('image_path', __('Image path'));

        return $form;
    }
}
