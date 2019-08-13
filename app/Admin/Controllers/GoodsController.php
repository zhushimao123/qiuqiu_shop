<?php

namespace App\Admin\Controllers;

use App\model\Goods;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\model\Goods';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Goods);

        $grid->column('goods_id', __('Goods id'));
        $grid->column('goods_name', __('Goods name'));
        $grid->column('goods_price', __('Goods price'));
        $grid->column('goods_bzprice', __('Goods bzprice'));
        $grid->column('goods_show', __('Goods show'));
        $grid->column('goods_new', __('Goods new'));
        $grid->column('goods_best', __('Goods best'));
        $grid->column('goods_hot', __('Goods hot'));
        $grid->column('goods_inventory', __('Goods inventory'));
        $grid->column('goods_integral', __('Goods integral'));
        $grid->column('goods_img')->display(function ($img) {

            return  $img;

        })->image('http://test.shop.com/uploads/goodsimg/', 50, 50);
        $grid->column('cate_id', __('Cate id'));
        $grid->column('brand_id', __('Brand id'));

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
        $show = new Show(Goods::findOrFail($id));

        $show->field('goods_id', __('Goods id'));
        $show->field('goods_name', __('Goods name'));
        $show->field('goods_price', __('Goods price'));
        $show->field('goods_bzprice', __('Goods bzprice'));
        $show->field('goods_show', __('Goods show'));
        $show->field('goods_new', __('Goods new'));
        $show->field('goods_best', __('Goods best'));
        $show->field('goods_hot', __('Goods hot'));
        $show->field('goods_inventory', __('Goods inventory'));
        $show->field('goods_integral', __('Goods integral'));
        $show->field('goods_img', __('Goods img'));
        $show->field('goods_showimg', __('Goods showimg'));
        $show->field('goods_desc', __('Goods desc'));
        $show->field('cate_id', __('Cate id'));
        $show->field('brand_id', __('Brand id'));
        $show->field('create_time', __('Create time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Goods);

        $form->text('goods_name', __('Goods name'));
        $form->decimal('goods_price', __('Goods price'));
        $form->decimal('goods_bzprice', __('Goods bzprice'));
        $form->switch('goods_show', __('Goods show'));
        $form->switch('goods_new', __('Goods new'))->default(2);
        $form->switch('goods_best', __('Goods best'))->default(2);
        $form->switch('goods_hot', __('Goods hot'))->default(2);
        $form->number('goods_inventory', __('Goods inventory'));
        $form->text('goods_integral', __('Goods integral'));
        $form->text('goods_img', __('Goods img'));
        $form->text('goods_showimg', __('Goods showimg'));
        $form->textarea('goods_desc', __('Goods desc'));
        $form->number('cate_id', __('Cate id'));
        $form->number('brand_id', __('Brand id'));
        $form->number('create_time', __('Create time'));

        return $form;
    }
}
