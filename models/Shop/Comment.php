<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-21
 * Time: 下午2:53
 */
class Shop_Comment extends Abstract_Model
{
    protected $_name = 'shop_comment';
    protected $_primary = 'id';

    protected $_referenceMap = array(
        'goods' => array(
            'class' => 'Shop',
            'type' => 'hasone',
            'target' => 'id',
            'source' => 'shop_id'
        )
    );
}