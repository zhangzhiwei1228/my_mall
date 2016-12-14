<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-14
 * Time: 下午5:05
 */
class Admincp_APPController extends Admincp_Controller_Action
{
    public function init()
    {
        $this->_auth();
    }

    public function doDefault()
    {
        $brands = M('Brand')->select('*')->where('category_id =' . (int)$this->_request->id)->fetchRows();
        $view = $this->_initView();
        $view->brands = $brands;
        $view->category_id = (int)$this->_request->id;
        $view->render('brand/list.php');

    }
}