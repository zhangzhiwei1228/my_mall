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

    /**
     * 详细页
     */
    public function doDetail()
    {
        $data = M($this->_formatModelName())->getById((int)$this->_request->id);
        if (!$data->exists()) {
            throw new Suco_Controller_Dispatcher_Exception('Not found.');
        }

        $view = $this->_initView();
        $view->data = $data;
        $view->render($this->_formatViewName().'/detail.php');
    }

    /**
     * 添加
     */
    public function doAdd()
    {
        if ($this->_request->isPost()) {
            $app = $this->_request->getPosts();
            $apk = $_FILES['app'];
            try {
                if (!$apk) {
                    throw new Suco_Exception('The file upload fail');
                }
                $url = Suco_File::upload($apk, 'uploads/app', array('apk'), getUploadFileSize());
                $url = (string)new Suco_Helper_BaseUrl($url, false);

                $data = array(
                    'error' => 0,
                    //'user' => $user,
                    'ref' => $_REQUEST['ref'],
                    'sign' => '',
                    'format' => $apk['type'],
                    'name' => $apk['name'],
                    'size' => $apk['size'],
                    'url' => $url,
                    'src' => $url
                );

                //保存至数据库
                M('Image')->insert($data);
            } catch(Suco_Exception $e) {
                $result = array(
                    'error' => 1,
                    'message' => $e->getMessage()
                );
            }
            $app['url'] = $url;
            M($this->_formatModelName())->insert(array_merge($app, $this->_request->getFiles()));
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }

        $view = $this->_initView();
        $view->render($this->_formatViewName().'/input.php');
    }

    /**
     * 编辑
     */
    public function doEdit()
    {
        $data = M($this->_formatModelName())->getById((int)$this->_request->id);
        if (!$data->exists()) {
            throw new Suco_Controller_Dispatcher_Exception('Not found.');
        }

        if ($this->_request->isPost()) {
            $app = $this->_request->getPosts();
            $apk = $_FILES['app'];
            if($apk) {
                try {
                    if (!$apk) {
                        throw new Suco_Exception('The file upload fail');
                    }
                    $url = Suco_File::upload($apk, 'uploads/app', array('apk'), getUploadFileSize());
                    $url = (string)new Suco_Helper_BaseUrl($url, false);

                    $data = array(
                        'error' => 0,
                        //'user' => $user,
                        'ref' => $_REQUEST['ref'],
                        'sign' => '',
                        'format' => $apk['type'],
                        'name' => $apk['name'],
                        'size' => $apk['size'],
                        'url' => $url,
                        'src' => $url
                    );

                    //保存至数据库
                    M('Image')->insert($data);
                } catch(Suco_Exception $e) {
                    $result = array(
                        'error' => 1,
                        'message' => $e->getMessage()
                    );
                }
                $app['url'] = $url;
            }

            M($this->_formatModelName())->updateById(array_merge($app, $this->_request->getFiles()), (int)$this->_request->id);
            $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
        }

        $view = $this->_initView();
        $view->data = $data;
        $view->render($this->_formatViewName().'/input.php');
    }

    /**
     * 删除
     */
    public function doDelete()
    {
        if (!M($this->_formatModelName())->deleteById((int)$this->_request->id)) {
            throw new Suco_Controller_Dispatcher_Exception('Not found.');
        }
        $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
    }


    public function doTrash()
    {
        if (!M($this->_formatModelName())->updateById(array('is_trash' => 1), (int)$this->_request->id)) {
            throw new Suco_Controller_Dispatcher_Exception('Not found.');
        }
        $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
    }
}