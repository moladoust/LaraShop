<?php

namespace App\Admin\Controllers;

use App\Models\CouponCode;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;

class CouponCodesController extends AdminController
{
    /**
     * Title.
     *
     * @var string
     */
    protected $title = 'CouponCode';

    /**
     * Grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CouponCode);

        $grid->model()->orderBy('created_at', 'desc');
        $grid->id('ID')->sortable();
        $grid->name('name');
        $grid->code('discount code');
        $grid->description('describe');
        $grid->column('usage', 'amount')->display(function ($value) {
            return "{$this->used} / {$this->total}";
        });
        $grid->enabled('Is it enabled?')->display(function ($value) {
            return $value ? 'Yes' : 'no';
        });
        $grid->created_at('creation time');
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

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
        $show = new Show(CouponCode::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('code', __('Code'));
        $show->field('type', __('Type'));
        $show->field('value', __('Value'));
        $show->field('total', __('Total'));
        $show->field('used', __('Used'));
        $show->field('min_amount', __('Min amount'));
        $show->field('not_before', __('Not before'));
        $show->field('not_after', __('Not after'));
        $show->field('enabled', __('Enabled'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new CouponCode);

        $form->display('id', 'ID');
        $form->text('name', 'name')->rules('required');
        $form->text('code', 'discount code')->rules(function ($form) {
            // if $form->model()->id Not empty, it means editing operation
            if ($id = $form->model()->id) {
                return 'nullable|unique:coupon_codes,code,' . $id . ',id';
            } else {
                return 'nullable|unique:coupon_codes';
            }
        });
        $form->radio('type', 'Types of')->options(CouponCode::$typeMap)->rules('required')->default(CouponCode::TYPE_FIXED);
        $form->text('value', 'Discount')->rules(function ($form) {
            if (request()->input('type') === CouponCode::TYPE_PERCENT) {
                // If the percent discount type is selected, the discount range can only be 1 ~ 99
                return 'required|numeric|between:1,99';
            } else {
                // Otherwise, as long as it is greater than or equal to 0.01
                return 'required|numeric|min:0.01';
            }
        });
        $form->text('total', 'total')->rules('required|numeric|min:0');
        $form->text('min_amount', 'minimum amount')->rules('required|numeric|min:0');
        $form->datetime('not_before', 'Starting time');
        $form->datetime('not_after', 'End Time');
        $form->radio('enabled', 'enable')->options(['1' => 'Yes', '0' => 'no']);

        $form->saving(function (Form $form) {
            if (!$form->code) {
                $form->code = CouponCode::findAvailableCode();
            }
        });

        return $form;
    }
}
