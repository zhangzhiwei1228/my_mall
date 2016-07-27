<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

class Helper_Paginator extends Suco_Helper_Paginator
{
	protected $_maxPage = 250;
	
	public $nextPageCaption = '&raquo;';
	public $prevPageCaption = '&laquo;';
	public $firstPageCaption = '首页';
	public $lastPageCaption = '未页';
	
	public static function callback($args)
	{
		return @new self($args[0], $args[1], $args[2], $args[3]);
	}

	public function firstPage($caption = '')
	{
		if ((!$caption && $this->getCurrentPage() - $this->pageNumberLength - 1 < 1)) {
			return;
		}
		return '<a href="'.$this->go('first').'">'.($caption ? $caption : 1).'</a>' . ($caption ? '' : '<a>...</a>');
	}

	public function lastPage($caption = '')
	{
		if ((!$caption && $this->_currentPage > $this->getTotalPage() - $this->pageNumberLength - 1)) {
			return;
		}
		return ($caption ? '' : ' <a>...</a> ') . '<a href="'.$this->go('last').'">'.($caption ? $caption : $this->_totalPage).'</a>';
	}

	public function pageNumber($length = null)
	{
		if ($length) {
			$this->pageNumberLength = $length;
		} else {
			$length = $this->pageNumberLength;
		}

		$str = null;
		for ($i = $this->getCurrentPage() - $length; $i < $this->getCurrentPage() + $length + 1; $i++) {
			if ($i < 1 || $i > $this->getTotalPage()) continue;
			$str .= '<li '.($this->getCurrentPage() == $i ? ' class="active"' : '').'><a href="'.$this->go($i).'">'.$i.'</a></li> ';
		}

		return $str;
	}
	
	public function getFullBar()
	{
		if (!$this->getTotalRecord()) { return ''; }
		$st = ($this->_currentPage - 1) * $this->_pageSize + 1;
		$et = $this->_currentPage * $this->_pageSize;
		$et = $et > $this->_totalRecord ? $this->_totalRecord : $et;

		return <<<EOF
 <li>{$this->prevPage()}</li><li> {$this->firstPage()}</li> {$this->pageNumber(3)} <li>{$this->lastPage()}</li> <li>{$this->nextPage()}</li>
EOF;
	}
	
	public function getMiniBar()
	{
		if (!$this->getTotalRecord()) { return ''; }
		return <<<EOF
<span>共 <em>{$this->getTotalRecord()}</em> 件商品 </span>
<span>{$this->getCurrentPage()}/{$this->getTotalPage()}</span> {$this->prevPage()} {$this->nextPage()}
EOF;
	}
	
	public function getInputBox()
	{
		return <<<EOF
到 <input type="text" id="inputPage" class="page_input" value="{$this->getCurrentPage()}" size="3" name="page"> 页
<input type="submit" class="page_but" value="确定">
EOF;
	}
}