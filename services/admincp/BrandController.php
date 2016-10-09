<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-8
 * Time: 下午4:18
 */
class Admincp_BrandController extends Admincp_Controller_Action
{
    public function init()
    {
        $this->_auth();
    }

    public function doDefault()
    {
        $brands =  M('Brand')->select('*')->where('category_id ='.(int)$this->_request->id)->fetchRows();
        $view = $this->_initView();
        $view->brands = $brands;
        $view->category_id = (int)$this->_request->id;
        $view->render('brand/list.php');

    }
    public function doBatch()
    {
        foreach ((array)$_POST['ids'] as $id) {
            M('Brand')->deleteById((int)$id);
        }
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
    public function doAdd() {

        if ($this->_request->isPost()) {
            M($this->_formatModelName())->insert(array_merge($this->_request->getPosts(), $this->_request->getFiles()));
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }
        $view = $this->_initView();
        $view->category_id = $this->_request->category_id;
        $view->render('brand/input.php');
    }
    public function doEditTitle() {
        if ($this->_request->isPost()) {

            M($this->_formatModelName())->update($this->_request->getPosts(),'category_id='.(int)$this->_request->category_id );
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }
        $view = $this->_initView();
        $data = M('Brand')->select('title')->where('category_id='.(int)$this->_request->category_id)->fetchRow()->toArray();
        $view->category_id = $this->_request->category_id;
        $view->data = $data;
        $view->render('brand/etitle.php');
    }

}