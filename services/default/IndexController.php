<?php

class IndexController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}
	
	public function doDefault()
	{

		if(isset($this->_request->invite_id)) {
			$_SESSION['invite_id'] = $this->_request->invite_id ;
		}
		$quickLinks = M('Navigate')->select()
			->where('parent_id = 0 AND type = ? AND is_enabled <> 0', 'main')
			->order('rank ASC, id ASC')
			->fetchRows();

		$recShop = M('Shop')->select()
			->where('is_special = 0')
			->order('is_rec DESC, id DESC')
			->limit(7)
			->fetchRows();

		$specialShop = M('Shop')->select()
			->where('is_special = 1')
			->order('is_rec DESC, id DESC')
			->limit(7)
			->fetchRows();

		$recGoodsCates = M('Goods_Category')->select()
			->where('parent_id = 0 and is_enabled<>0')
			->order('rank ASC, id ASC')
			->fetchRows();

		$view = $this->_initView();
		$view->intro = M('Page')->getByCode('intro');
		$view->guide = M('Page')->getByCode('guide');
		$view->description = M('Page')->getByCode('description');
		$view->video = M('Page')->getByCode('video');
		$view->todaynews = M('Page')->getByCode('today-news');
		$view->quickLinks = $quickLinks;
		$view->recShop = $recShop;
		$view->specialShop = $specialShop;
		$view->recGoodsCates = $recGoodsCates->hasmanyGoods();
		$view->render('views/welcome.php');
	}
	public function doWxtokencheck() {
		$view = $this->_initView();
		$view->render('views/shopping/wx_tokencheck.php');
	}
}