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
        $this->user = $this->_auth();
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
        echo ($this->_encrypt_data($rows));
//        $encrypt_data = ($this->_encrypt_data($rows));
        //echo $this->_decrypt_data($encrypt_data);
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
        echo $this->_encrypt_data($recGoodsCates);
        /*$encrypt_data = ($this->_encrypt_data($recGoodsCates));
        echo $this->_decrypt_data($encrypt_data);*/
        die();
    }
    /**
     * 首页头部、首页中上部、中左部、中右部广告位
     */
    public function doAdvertise() {
        $data = array('app-home-heard','app-center-top','app-center-left','app-center-right','app-hot-market');
        $home_heard = M('Advert')->getAppRowsByCode($data);
        echo $this->_encrypt_data($home_heard);
        /*$encrypt_data = ($this->_encrypt_data($home_heard));
        echo $this->_decrypt_data($encrypt_data);*/
        die();
    }
    /**
     * 合作商家
     */
    public function doTeamShop() {
        $type_ids = M('Coltypes')->select('id,name')->where('english='."'shop"."'")->fetchRows()->toArray();
        $data = array();
        foreach($type_ids as $key=>$row){
            $ids[] = $key;
            $shops = M('Shop')->select('id,name,thumb')
                ->where('is_special ='.(int)$key)
                ->order('is_rec DESC, id DESC')
                ->limit(4)
                ->fetchRows()->toArray();
            if($shops) {
                foreach($shops as &$shop){
                    $shop['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$shop['thumb'];
                }
                $data[$key]['shop'] = $shops ;
                $data[$key]['name'] = $row['name'];

            }
        }
        echo $this->_encrypt_data($data);
        /*$encrypt_data = ($this->_encrypt_data($data));
        echo $this->_decrypt_data($encrypt_data);*/
        die();
    }
    /**
     * 本月精选
     */
    public function doSelectGoods() {
        $goods = M('Goods')->alias('g')
            ->columns('g.id,g.title,g.thumb,g.notes')
            ->where('g.is_selling = 1 AND g.is_checked = 2 and ((g.quantity-g.quantity_warning) > 0) and is_select = 1 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time())
            ->limit(4)->order('g.create_time DESC')->fetchRows()->toArray();
        foreach($goods as $key=>$row) {
            $arrs = M('Goods_Sku')->select('point1,point2,point3,point4,point5,exts,market_price')
                ->where('goods_id ='.(int)$row['id'])
                ->fetchRow()
                ->toArray();
            $goods[$key]['point1'] = $arrs['point1'];
            $goods[$key]['point2'] = $arrs['point2'];
            $goods[$key]['point3'] = $arrs['point3'];
            $goods[$key]['point4'] = $arrs['point4'];
            $goods[$key]['point5'] = $arrs['point5'];
            $goods[$key]['market_price'] = $arrs['market_price'];
            $goods[$key]['exts'] = $arrs['exts'];
            $goods[$key]['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
        }
        echo $this->_encrypt_data($goods);
        //$encrypt_data = ($this->_encrypt_data($goods));
       /* $d = json_decode($encrypt_data);
        $datas =  $this->_decrypt_data($d->data);
        echo $datas ;*/
        die();
    }
}