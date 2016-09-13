<?php

class Admincp_GoodsTypeController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Goods_Type')->select()
			->order('id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->q) {
			$select->where('name LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('goods/type/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$_POST['attr_setting'] = (array)$_POST['attr_setting'];
			$id = M('Goods_Type')->insert($this->_request->getPosts());
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list&cid=' . $this->_request->getPost('category_id'));
		}

		$view = $this->_initView();
		$view->render('goods/type/input.php');
	}

	public function doEdit()
	{
		$data = M('Goods_Type')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			$_POST['attr_setting'] = (array)$_POST['attr_setting'];
			M('Goods_Type')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('goods/type/input.php');
	}

	public function doAttribute()
	{
		$category = M('Goods_Category')->getById((int)$this->_request->cid);
		if ($this->_request->pid) {
			$params = M('Goods_Attribute')->select()
				->where('goods_id = ?', (int)$this->_request->pid)
				->order('id ASC')
				->fetchRows()
				->toArray();
			foreach ($params as $row) {
				if ($row['attr_color']) {
					$d[$row['attr_name']][] = $row['attr_value'].'|'.$row['attr_color'];
				} else {
					$d[$row['attr_name']][] = $row['attr_value'];
				}
			}
		}

		$type = M('Goods_Type')->select('attr_setting')->where('id = ?', (int)$category->type_id)->fetchRow();
		foreach ((array)$type['attr_setting'] as $row) {
			if ($row['attr_values']) {
				$row['attr_values'] = explode("\r\n", $row['attr_values']);
			}
			$attributes[$row['attr_name']] = $row;
		}

		foreach ((array)$d as $name => $value) {
			if (isset($attributes[$name])) {
				$attributes[$name]['attr_value'] = $value;
			} else {
				$customs[] = array(
					'attr_name' => $name,
					'attr_value' => implode(',', $value)
				);
			}
		}

		$view = $this->_initView();
		$view->setLayout(false);
		$view->category = $category;
		$view->attributes = $attributes;
		$view->customs = $customs;
		$view->render('goods/type/attr.php');
	}

	public function doSku()
	{
		$config = new Suco_Config_Php();
		$pointConfig = $config->load(CONF_DIR.'point.conf.php');
		
		$view = $this->_initView();
		$view->setLayout(false);
		$view->code = $this->_request->getPost('code');
		$view->attributes = $this->_request->getPost('attributes');
		$view->pointConfig = $pointConfig;
		$view->skus = M('Goods_Sku')->select()
			->where('goods_id = ?', (int)$this->_request->id)
			->fetchOnKey('key');
		$view->good = M('Goods')->select('quantity,quantity_warning')
			->where('id = ?', (int)$this->_request->id)
			->fetchRow()->toArray();

		$view->render('goods/type/sku.php');
	}
}