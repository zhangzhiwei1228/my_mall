<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-2
 * Time: 下午6:10
 */
class App_IndexController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        //$this->user = $this->_auth();
    }

    public function doDefault()
    {
        $user = M('Region')->select('*')->where('level=2')->fetchRows()->toArray();
        $rows = array();
        foreach($user as $key=>$row) {
            $rows['pro'][$key] = $row;
            $city  = M('Region')->select('*')->where('parent_id='.(int)$row['id'])->fetchRows()->toArray();
            foreach($city as $key1=>$c) {
                $rows['pro'][$key]['cities'][$key1] = $c;
                $areas = M('Region')->select('*')->where('parent_id='.(int)$c['id'])->fetchRows()->toArray();
                foreach($areas as $key2=>$area) {
                    $rows['pro'][$key]['cities'][$key1]['areas'][$key2] = $area;
                }
            }
        }
        $encrypt_data = ($this->_encrypt_data($rows));
        echo $this->_decrypt_data($encrypt_data);
        die();
    }

    /**
     * 商场类别
     */
    public function doGoodsCategory() {
        $recGoodsCates = M('Goods_Category')->select('id,name')
                ->where('parent_id = 0 and is_enabled<>0')
                ->order('rank ASC, id ASC')
                ->fetchRows()->toArray();
        $encrypt_data = ($this->_encrypt_data($recGoodsCates));
        echo $this->_decrypt_data($encrypt_data);
        die();
    }
}