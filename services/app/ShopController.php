<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-21
 * Time: 下午2:59
 */
class App_ShopController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
    }

    /**
     *商家列表
     */
    public function doList() {
        $page = $this->_request->page;
        $limit = $this->_request->limit;
        if( !$limit || !$page) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cid = $this->_request->cid;
        $q = $this->_request->q;
        $area_id = $this->_request->area_id;
        $is_special = $this->_request->is_special;

        //全部类型
        $cates = M('Shop_Category')->select('id,name')
            ->where('parent_id = 0')
            ->order('rank ASC, id ASC')
            ->fetchRows()->toArray();

        $select = M('Shop')->alias('s')
            ->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
            ->columns(' s.id,s.category_id,s.area_id,s.thumb,s.name,s.tel,s.area_text,s.addr,s.pro_desc,s.is_special')
            ->order('s.is_rec DESC, s.id DESC')
            ->paginator($limit, $page);

        //按分类查找
        if ($cid) {
            $ids = M('Shop_Category')->getChildIds((int)$cid);
            $select->where('s.category_id IN ('.($ids ? $ids : $cid).')');
        }
        //按关键词查找
        if ($this->_request->q) {
            $select->where('s.name LIKE ?', '%'.$q.'%');
        }
        //地区
        if ($this->_request->area_id) {
            $ids = M('Region')->getChildIds((int)$area_id);
            $select->where('s.area_id IN ('.($ids ? $ids : $area_id).')');
        }
        //按照特殊性查找
        if(isset($is_special)) {
            $select->where('s.is_special = '.(int)$is_special);
        }
        $shops = $select->fetchRows()->toArray();

        //获取商家经纬度
        foreach($shops as $key=>&$row) {
            if($row['addr'] && $row['area_text']) {
                $row['lng_lat'] = get_lng_lat($row['area_text'].$row['addr']);
            }
            if($row['thumb']) {
                $row['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
            }
        }
        $data['shops'] = $shops;
        $data['cates'] = $cates;

        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商家详情
     */
    public function doDetail() {
        $id = $this->_request->id;
        if( !$id) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $shop = M('Shop')->select()->where('id='.(int)$id)->fetchRow();
        if (!$shop->exists()) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求资源不存在');
            die();
        }
        $addr = $shop['area_text'].$shop['addr'];
        $lng_lat = get_lng_lat($addr);
        $data['id'] = $shop['id'];
        $data['name'] = $shop['name'];
        $data['tel'] = $shop['tel'];
        $data['lng_lat'] = $lng_lat;
        $data['pro_desc'] = $shop['pro_desc'];
        $data['addr'] = $addr;
        if($shop['ref_img']) {
            $ref_imgs = json_decode($shop['ref_img']);
            foreach($ref_imgs as &$row) {
                $row->src = 'http://'.$_SERVER['HTTP_HOST'].$row->src;
            }
        }
        $data['ref_img'] = $ref_imgs;
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }


    /**
     * 商家评价
     */
    public function doComment() {

    }

    /**
     * 添加商家评论
     */
    public function doAddComment() {
        $data = $this->_request->post();
        M('Shop_Comment')->insert(array_merge($data, $this->_request->getFiles()));
    }
}