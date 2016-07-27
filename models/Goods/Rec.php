<?php

class Goods_Rec extends Abstract_Model
{
	protected $_name = 'goods_rec';
	protected $_primary = 'id';

	public function getByCode($code)
	{
		return $this->select()
			->where('code = ?', $code)
			->fetchRow();

	}

	public function getGoods($row)
	{
		$ids = $row->goods_ids ? $row->goods_ids : 0;
		$goods = M('Goods')->select('id, title, min_price, max_price, thumb')
			->where('is_selling = 1 AND id IN ('.$ids.')')
			->order(array('substring_index(\''.$ids.'\',id,1)'))
			->fetchRows()
			->hasmanyPromotions();

		return $goods;
	}

	public function toHtml($row)
	{
		$ids = $row->goods_ids ? $row->goods_ids : 0;
		$goods = M('Goods')->select('id, title, min_price, max_price, thumb')
			->where('is_selling = 1 AND id IN ('.$ids.')')
			->order(array('substring_index(\''.$ids.'\',id,1)'))
			->fetchRows()
			->hasmanyPromotions();
		
		$str = '';
		foreach($goods as $i => $row) {
			$url = (string)H('Url', 'controller=goods&action=detail&id='.$row['id']);
			$str .= '<dl class="gd-item-'.($i+1).'">';
			$str .= '<dt class="gd-rank">'.($i+1).'</dt>';
			$str .= '<dd class="gd-thumb"><a href="'.$url.'" target="_blank"><img src="'.getImage($row['thumb'], '400x400').'" /></a></dd>';
			$str .= '<dd class="gd-title"><a href="'.$url.'" target="_blank">'.$row['title'].'</a></dd>';
			$str .= '<dd class="gd-price">&yen;'.$row['min_price'].'</dd>';
			$str .= '</dl>';
		}
		return $str;
	}

	public function inputFilter($data)
	{
		if ($data['goods_ids']) {
			$data['goods_num'] = @count(explode(',',$data['goods_ids']));
		}

		return parent::inputFilter($data);
	}
}