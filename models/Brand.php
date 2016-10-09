<?php

/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-8
 * Time: 下午4:52
 */
class Brand extends Abstract_Model {
    protected $_name = 'brand';
    protected $_primary = 'id';
    /**
     * 输入数据前过滤
     * @return string
     */
    public function inputFilter($data)
    {
        //获取第一张作为缩略图
        if ($data['ref_img']) {
            //重新排序
            foreach($data['ref_img'] as $item) {
                $arr[] = $item;
            }
            $data['ref_img'] = $arr;

            $img = current($data['ref_img']);
            $data['thumb'] = M('Image')->getById($img['id'])->src;
            $data['ref_img'] = json_encode($data['ref_img']);
        }
        return parent::inputFilter($data);
    }
}