<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->id('ID')->sortable();
        $grid->title('product name');
        $grid->on_sale('It has been added to')->display(function ($value) {
            return $value ? 'Yes' : 'no';
        });
        $grid->price('price');
        $grid->rating('score');
        $grid->sold_count('sales');
        $grid->review_count('number of comments');

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        $form->text('title', 'product name')->rules('required');

        $form->image('image', 'cover image')->rules('required|image');

        $form->quill('description', 'product description')->rules('required');

        $form->radio('on_sale', 'listed')->options(['1' => 'Yes', '0' => 'No'])->default('0');

        $form->hasMany('skus', 'SKU list', function (Form\NestedForm $form) {
            $form->text('title', 'SKU name')->rules('required');
            $form->text('description', 'SKU describe')->rules('required');
            $form->text('price', 'unit price')->rules('required|numeric|min:0.01');
            $form->text('stock', 'remaining stock')->rules('required|integer|min:0');
        });

        $form->saving(function (Form $form) {
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        return $form;
    }
}
