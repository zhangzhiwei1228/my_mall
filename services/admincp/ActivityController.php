<?php

class Admincp_ActivityController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Activity')->select()
			->order('create_time DESC')
			->paginator(20, $this->_request->page);

		//按类型查找
		if ($this->_request->tid) {
			$select->where('tid = ?', $this->_request->tid);
		}
		//按地区查找
		if ($this->_request->aid) {
			$ids = M('Area')->getChildIds((int)$this->_request->aid);
			$select->where('aid IN ('.($ids ? $ids : $this->_request->aid).')');
		}
		if ($this->_request->addr) {
			$select->where('address LIKE ?', '%'.$this->_request->addr.'%');
		}
		//按关键词查找
		if ($this->_request->q) {
			$keywords = explode(' ', $this->_request->q);
			foreach ($keywords as $i => $val) {
				$cond[] = 'theme LIKE \'%'.$val.'%\'';
			}
			$select->where('('.implode(' OR ', $cond).')');
		}
		if ($this->_request->begin_time) {
			$select->where('begin_time >= ?', strtotime($this->_request->begin_time));
		}
		if ($this->_request->end_time) {
			$select->where('end_time <= ?', strtotime($this->_request->end_time) + (3600 * 24));
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('activity/list.php');
	}

	public function doHelper()
	{
		if ($this->_request->isPost()) {
			$spider = new Suco_Spider();
			$spider->setTimeOut(30);

			$url = $_POST['url'];
			if (strstr($url, 'wines-info.com')) {
				$spider->connect($url, null, 'utf-8');
				$data['ref_url'] = $url;
				
				$data['tid'] = 1;
				$data['thumb'] = $spider->match('<img id="imgPicUrl" class="imgMaxWidth" src="{data}"');
				$data['theme'] = $spider->match('<span id="labTitle" class="title STYLE3">{data}</span>');
				$data['theme'] = preg_replace('#\d+年\d+月\d+日#', '', $data['theme']);
				#$data['theme'] = H('cutstr', $data['theme'], 65, '');
				$tmp = $spider->match('<span id="labContentHead" class="ContentHead">{data}</span>');
				$data['address'] = $spider->match('活动地点：{data}<br/>', $tmp);
				$data['begin_time'] = $spider->match('活动时间：{data}<br/>', $tmp);
				if ($data['begin_time']) {
					$data['begin_time'] = strtotime(str_replace(array('年','月','日'), '/', $data['begin_time']));
					$data['end_time'] = $data['begin_time'];
				}
				$data['create_time'] = $spider->match('<span id="labTime" class="gray">{data}</span>');
				if ($data['create_time']) {
					$data['create_time'] = strtotime(str_replace(array('年','月','日'), '/', $data['create_time']));
				}
				
				$data['contact'] = $spider->match('联 系 人：{data}<br/>', $tmp);
				$data['phone'] = $spider->match('联系电话：{data}<br/>', $tmp);
				$data['email'] = $spider->match('联系邮箱：{data}<br/>', $tmp);
				$data['intro'] = $spider->match('<span id="labContent" class="as1">{data}</span>{space}<script language="javascript"');
				
				if ($data['address']) {
					$province = M('Area')->select()->where('level = 2 AND ? LIKE CONCAT("%",name,"%")', $data['address'])
						->fetchRow();
					$city = M('Area')->select()->where('level = 3 AND ? LIKE CONCAT("%",name,"%")', $data['address'])
						->fetchRow();
					$district = M('Area')->select()->where('level = 4 AND ? LIKE CONCAT("%",name,"%")', $data['address'])
						->fetchRow();

					$data['province'] = $province->name;
					$data['city'] = $city->name;

					if ($district['id']) {
						$data['aid'] = $district['id'];
					} elseif ($city['id']) {
						$data['aid'] = $city['id'];
					} elseif ($province['id']) {
						$data['aid'] = $province['id'];
					}
					
				}

				$view = $this->_initView();
				$view->data = $data;
				$view->render('activity/input.php');
				return;
			} else {
				die('暂不支持此网站');
			}
			
		}

		$view = $this->_initView();
		$view->render('activity/helper.php');
	}
}