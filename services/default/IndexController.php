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
		$clotypes = M('Coltypes')->select('name')->where("english = 'shop'")->fetchRows()->toArray();
		//$view->clotypes = $clotypes;
		foreach($clotypes as $key => &$val) {
			$recShop = M('Shop')->select()
				->where('is_special = '.$key)
				->order('is_rec DESC, id DESC')
				->limit(7)
				->fetchRows()->toArray();
			$val['shops'] = $recShop;
		}

		$specialShop = M('Shop')->select()
			->where('is_special = 1')
			->order('is_rec DESC, id DESC')
			->limit(7)
			->fetchRows();

		$recGoodsCates = M('Goods_Category')->select()
			->where('parent_id = 0 and is_enabled <> 0')
			->order('rank ASC, id ASC')
			->fetchRows();

		$view = $this->_initView();
		$view->intro = M('Page')->getByCode('intro');
		$view->guide = M('Page')->getByCode('guide');
		$view->description = M('Page')->getByCode('description');
		$view->video = M('Page')->getByCode('video');
		$view->todaynews = M('Page')->getByCode('today-news');
		$view->quickLinks = $quickLinks;
		$view->recShop = $clotypes;
		$view->specialShop = $specialShop;
		$view->recGoodsCates = $recGoodsCates->hasmanyGoods();
		if($view->isMobile()) {
			$view->render('views/welcome.php');
		} else {
			$view->render('views/web/welcome.php');
		}

	}
	public function doWxtokencheck() {
		$view = $this->_initView();
		$view->render('views/shopping/wx_tokencheck.php');
	}

	public function doNotes() {
		$view = $this->_initView();
		$view->render('views/new_text/notes.php');
	}
	public function doOtherGoods() {
		$view = $this->_initView();
		$view->render('views/new_text/other_goods.php');
	}

	public function doRecords() {
		$view = $this->_initView();
		$view->render('views/new_text/records.php');
	}
	public function doRecordsNumerical() {
		$view = $this->_initView();
		$view->render('views/new_text/records_numerical.php');
	}
	public function doRecordsVolume() {
		$view = $this->_initView();
		$view->render('views/new_text/records_volume.php');
	}
	public function doWelInc() {
		$view = $this->_initView();
		$view->render('views/web/ajax/wel_inc.php');
	}


}