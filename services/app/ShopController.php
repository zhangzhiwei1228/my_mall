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
        $ulng = $this->_request->lng;
        $ulat = $this->_request->lat;


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
            $row['lng'] = '';
            $row['lat'] = '';
            if($row['addr'] && $row['area_text']) {
                $lng_lat =  get_lng_lat($row['area_text'].$row['addr']);
                $row['lng'] = is_array($lng_lat) ? $lng_lat['lng'] : '';
                $row['lat'] = is_array($lng_lat) ? $lng_lat['lat'] : '';
            }
            $row['distance'] = '';
            if($row['lng'] && $row['lat'] && $ulng && $ulat) {
                $row['distance'] = getDistance($ulat,$ulng,$row['lat'],$row['lng']);
            }
            //$row['lng_lat'] = $row['addr'] && $row['area_text'] ? get_lng_lat($row['area_text'].$row['addr']) : '';
            $row['thumb'] =  $row['thumb'] ? 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'] : '';
        }
        $data['shops'] = $shops;

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
        $data['lng'] = is_array($lng_lat) ? $lng_lat['lng'] : '';
        $data['lat'] = is_array($lng_lat) ? $lng_lat['lat'] : '';
        $ulng = $this->_request->lng;
        $ulat = $this->_request->lat;
        $data['distance']= '';
        if($data['lng'] && $data['lat'] && $ulng && $ulat) {
            $data['distance'] = getDistance($ulat,$ulng,$data['lat'],$data['lng']);
        }
        //$data['lng_lat'] = $lng_lat;
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
     * 列表头部
     */
    public function doHeard() {
        //全部类型
        $cates = M('Shop_Category')->select('id,name')
            ->where('parent_id = 0')
            ->order('rank ASC, id ASC')
            ->fetchRows()->toArray();
        $type_ids = M('Coltypes')->select('id,name')->where('english='."'shop"."'")->fetchRows()->toArray();
        $data['cates'] = $cates;
        $types = array();
        foreach($type_ids as $key=> $row) {
            $types[$key]['name'] = $row['name'];
            $types[$key]['is_special'] = $key;
        }
        $data['types'] = $types;
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

    /**
     * 商家评价
     */
    public function doComment() {
        $shop_id = $this->_request->shop_id;
        $shop = M('Shop')->select()->where('id='.(int)$shop_id)->fetchRow()->toArray();
        if(!$shop) {
            echo  self::_error_data(API_SHOP_NOT_FOUND,'商家不存在');
            die();
        }
        $data = M('Shop_Comment')->select('comment,photos,create_time')->where('shop_id='.(int)$shop_id.' and is_show <> 0')->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

    /**
     * 添加商家评论
     */
    public function doAddComment() {
        $this->user = $this->_auth();
        $uid = $this->user->id;
        $shop_id = $this->_request->shop_id;
        $comment = $this->_request->comment;
        $ext1 = $this->_request->ext1;
        $ext2 = $this->_request->ext2;
        $ext3 = $this->_request->ext3;
        if(!$shop_id || !$comment) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $shop = M('Shop')->select()->where('id='.(int)$shop_id)->fetchRow()->toArray();
        if(!$shop) {
            echo  self::_error_data(API_SHOP_NOT_FOUND,'商家不存在');
            die();
        }

        if(!$comment) {
            echo  self::_error_data(API_COMMENT_NOT_NULL,'评论不能为空');
            die();
        }
        //ext1 服务态度 ext2 店铺环境   ext3 价格合理

        $extr = array('ext1'=>$ext1,'ext2'=>$ext2,'ext3'=>$ext3);
        $data['shop_id'] = $shop_id;
        $data['user_id'] = $uid;
        $data['comment'] = $comment;
        $data['create_time'] = time();
        $data['extr'] = json_encode($extr);
        $image = $this->Upload();

        if(isset($image['error']) && $image['error']) {
            echo  self::_error_data(API_UPLOAD_RESOURCES_NULL,'上传失败');
            die();
        }
        $data['photos'] = 'http://'.$_SERVER['HTTP_HOST'].$image['src'];
        //try{
        $insert = M('Shop_Comment')->insert($data);
       /* } catch(Exception $e) {
            var_dump($e);
            die();
        }*/

        if(!$insert) {
            echo  self::_error_data(API_COMMENT_FAIL,'评价失败');
            die();
        }
        unset($data['extr']);
        $data = array_merge($data,$extr);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    public function doDesc() {
        $shop_id = $this->_request->id;
        $shop = M('Shop')->select('description')->where('id='.(int)$shop_id)->fetchRow()->toArray();
        $view = $this->_initView();
        if(!$shop_id || !$shop) {
            $view->render('views/app/404.php');
        } else {
            $view->desc = $shop['description'];
            $view->render('views/app/business.php');
        }


    }
    /**
     * 添加商家评论--多图
     */
    public function doAddComments() {
        $this->user = $this->_auth();
        $uid = $this->user->id;
        $shop_id = $this->_request->shop_id;
        $comment = $this->_request->comment;
        $ext1 = $this->_request->ext1;
        $ext2 = $this->_request->ext2;
        $ext3 = $this->_request->ext3;
        if(!$shop_id || !$comment) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $shop = M('Shop')->select()->where('id='.(int)$shop_id)->fetchRow()->toArray();
        if(!$shop) {
            echo  self::_error_data(API_SHOP_NOT_FOUND,'商家不存在');
            die();
        }

        if(!$comment) {
            echo  self::_error_data(API_COMMENT_NOT_NULL,'评论不能为空');
            die();
        }
        //ext1 服务态度 ext2 店铺环境   ext3 价格合理

        $extr = array('ext1'=>$ext1,'ext2'=>$ext2,'ext3'=>$ext3);
        $data['shop_id'] = $shop_id;
        $data['user_id'] = $uid;
        $data['comment'] = $comment;
        $data['create_time'] = time();
        $data['extr'] = json_encode($extr);
        $phpoto_src = array();
        for($i = 1;$i<4;$i++) {
            $_FILES['imgFile'] = $_FILES['imgFile'.$i];
            $image = $this->Upload($_FILES['imgFile']);
            if(isset($image['error']) && $image['error']) {
                continue;
            }
            $phpoto_src[]['src'] = 'http://'.$_SERVER['HTTP_HOST'].$image['src'];
        }
        $data['photos'] = json_encode($phpoto_src);
        $insert = M('Shop_Comment')->insert($data);
        if(!$insert) {
            echo  self::_error_data(API_COMMENT_FAIL,'评价失败');
            die();
        }
        unset($data['extr']);
        $data['photos'] = $phpoto_src;
        $data = array_merge($data,$extr);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
}