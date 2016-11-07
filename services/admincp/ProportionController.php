<?php

/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-10
 * Time: 下午4:11
 */
class Admincp_ProportionController extends Admincp_Controller_Action
{
    public function init()
    {
        $this->_auth();
    }

    public function doList()
    {
        //$datas = M('Proportion')->select()->fetchRows()->toArray();
        $datalist = M('Proportion')->select()->paginator(15, $this->_request->page);
        /*foreach($datas as &$data) {
            $left_name = M('Coltypes')->select('name')->where('id='.$data['left_id'])->fetchRow()->toArray();
            $right_name = M('Coltypes')->select('name')->where('id='.$data['right_id'])->fetchRow()->toArray();
            $type_name = M('Coltypes')->select('name')->where('id='.$data['type'])->fetchRow()->toArray();
            $data['left_name'] = $left_name['name'];
            $data['right_name'] = $right_name['name'];
            $data['type_name'] = $type_name['name'];
            if($data['exts']) {
                $data['exts'] = json_decode($data['exts']);
            }
        }*/
        if ($this->_request->q) {
            $coltypes = M('Coltypes')->select('id')->where('(name LIKE ?)', '%'.$this->_request->q.'%')->fetchRows()->toArray();
            foreach ($coltypes as $row) {
                $ids6[] = $row['id'];
            }
            $ids = $ids6 ? implode(',', $ids6) : 0;
            $datalist->where('type IN ('.($ids ? $ids : 0).')');
        }
        $view = $this->_initView();
        $view->datalist = $datalist->fetchRows();;
        $view->datas = $datalist->fetchRows();;
        $view->render('proportion/list.php');
    }
    public function doAdd()
    {
        if ($this->_request->isPost()) {
            $data = $this->_request->getPosts();
            if($data['exts']['value']) {
                $data['exts'] = json_encode($data['exts']);
            } else {
                $data['exts'] = '';
            }
            $data['type'] = $data['name'] ? $data['type_p'] : $data['type'];
            M('Proportion')->insert($data);
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }
        $datalist = M('Proportion')->select()->fetchRows();
        $coltypes_cash = M('Coltypes')->select()->where('type=1')->fetchRows()->toArray();
        $coltypes_desc = M('Coltypes')->select()->where('type=2')->fetchRows()->toArray();
        $earnings = M('Coltypes')->select()->where('type=3')->fetchRows()->toArray();
        $view = $this->_initView();
        $view->datalist = $datalist;
        $view->coltypes_cash = $coltypes_cash;
        $view->coltypes_desc = $coltypes_desc;
        $view->earnings = $earnings;
        $view->render('proportion/input.php');
    }
    public function doEdit()
    {
        if ($this->_request->isPost()) {
            $pdata = $this->_request->getPosts();
            if($pdata['exts']['value']) {
                $pdata['exts'] = json_encode($pdata['exts']);
            } else {
                $pdata['exts'] = '';
            }
            $pdata['type'] = $pdata['name'] ? $pdata['type_p'] : $pdata['type'];
            M('Proportion')->updateById($pdata, (int)$this->_request->id);

            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }
        $data = M('Proportion')->select()->where('id='.(int)$this->_request->id)->fetchRow()->toArray();
        if($data['exts']) {
            $data['exts'] = json_decode($data['exts']);
        }
        $coltypes_cash = M('Coltypes')->select()->where('type=1')->fetchRows()->toArray();
        $coltypes_desc = M('Coltypes')->select()->where('type=2')->fetchRows()->toArray();
        $earnings = M('Coltypes')->select()->where('type=3')->fetchRows()->toArray();
        $view = $this->_initView();
        $view->data = $data;
        $view->coltypes_cash = $coltypes_cash;
        $view->coltypes_desc = $coltypes_desc;
        $view->earnings = $earnings;
        $view->render('proportion/input.php');
    }
    public function doAddColtypes() {
        if ($this->_request->isPost()) {
            M('Coltypes')->insert($this->_request->getPosts());
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }
        $view = $this->_initView();
        $view->render('proportion/types_input.php');
    }
    public function doTypesList() {
        $view = $this->_initView();
        $datalist = M('Coltypes')->select()->paginator(15, $this->_request->page);
        if ($this->_request->q) {
            $datalist ->where('(name LIKE ?)', '%'.$this->_request->q.'%');
        }
        $view->datalist = $datalist->fetchRows();
        $view->render('proportion/types_list.php');
    }
    public function doTypeSearch() {
        $this->doTypesList();
    }
    public function doTypesEdit() {
        if ($this->_request->isPost()) {
            M('Coltypes')->updateById($this->_request->getPosts(), (int)$this->_request->id);
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }
        $view = $this->_initView();
        $view->data = M('Coltypes')->select()->where('id='.$this->_request->id)->fetchRow();
        $view->render('proportion/types_input.php');
    }
    public function doBatch()
    {
        switch($_POST['act']) {
            case 'delete':
                foreach ((array)$_POST['ids'] as $id) {
                    M('Proportion')->deleteById((int)$id);
                }
                break;
            case 'typesdelete':
                foreach ((array)$_POST['ids'] as $id) {
                    M('Coltypes')->deleteById((int)$id);
                }
                break;
        }
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
    public function doTypesDelete() {
        M('Coltypes')->deleteById((int)$this->_request->id);
        $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
    }
}