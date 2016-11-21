<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-21
 * Time: 下午2:53
 */
class Goods_Comment extends Abstract_Model
{
    protected $_name = 'goods_comment';
    protected $_primary = 'id';

    protected $_referenceMap = array(
        'goods' => array(
            'class' => 'Goods',
            'type' => 'hasone',
            'target' => 'id',
            'source' => 'goods_id'
        )
    );

    protected function _insertAfter($data)
    {
        //计算好评
        $avg = $this->select('AVG(score) AS result')->where('goods_id = ?', $data['goods_id'])->fetchCol('result');
        M('Goods')->updateById('comments_num = comments_num + 1, score_avg = '.(float)$avg, (int)$data['goods_id']);
    }
}