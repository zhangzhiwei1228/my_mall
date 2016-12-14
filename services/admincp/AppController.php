<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-14
 * Time: 下午5:05
 */
class Admincp_AppController extends Admincp_Controller_Action
{
    public function init()
    {
        $this->_auth();
    }

    public function doList()
    {
        $select = M('App')->select()
            ->order('id DESC')
            ->paginator(20, (int)$this->_request->page);

        if ($this->_request->q) {
            $select->where('name LIKE ?', '%'.$this->_request->q.'%');
        }

        $view = $this->_initView();
        $view->datalist = $select->fetchRows();
        $view->render('app/list.php');
    }

    public function doEdit()
    {

    }
    public function doDelete()
    {

    }
}