<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-2
 * Time: 下午7:04
 */
class App_GoodsController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
    }
    /**
     * 商品详情
     */
    public function doDetail() {
        $id = $this->_request->id;
        if( !$id) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $filed = 'id,category_id,title,sup,thumb,ref_img,notes,shipping_id,sales_num,quantity';
        $good = M('Goods')->select($filed)->where('id='.(int)$id)->fetchRow();
        if (!$good->exists()) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求资源不存在');
            die();
        }
        $getSkuOpts = $good->AppGetSkuOpts();
        $sku = M('Goods_Sku')->select('market_price,point1,point2,point3,point4,point5,exts')
            ->where('goods_id = ?', (int)$id)
            ->fetchRow()->toArray();
        $k_v = array();
        $i = 0;
        if($sku['point1']) {
            $k_v[$i]['name'] = '快乐积分';
            $k_v[$i]['value'] = $sku['point1'];
            $k_v[$i]['price_type'] = 1;
        }
        if($sku['point2']) {
            $k_v[$i+1]['name'] = '帮帮币';
            $k_v[$i+1]['value'] = $sku['point2'];
            $k_v[$i+1]['price_type'] = 2;
        }
        if($sku['point3']) {
            $k_v[$i+2]['name'] = '积分币';
            $k_v[$i+2]['value'] = $sku['point3'];
            $k_v[$i+2]['price_type'] = 3;
        }
        if($sku['point4']) {
            $k_v[$i+3]['name'] = '抵用券';
            $k_v[$i+3]['value'] = $sku['point4'];
            $k_v[$i+3]['price_type'] = 6;
        }
        if($sku['point5']) {
            $k_v[$i+4]['name'] = '现金';
            $k_v[$i+4]['value'] = $sku['point5'];
            $k_v[$i+4]['price_type'] = 7;
        }
        if($sku['exts']) {
            if($sku['exts']['ext1']['cash'] && $sku['exts']['ext1']['point']) {
                $k_v[$i+5]['name'] = '现金+帮帮币';
                $k_v[$i+5]['value'] = $sku['exts']['ext1']['cash'].'+'.$sku['exts']['ext1']['point'];
                $k_v[$i+5]['price_type'] = 4;
            }
            if($sku['exts']['ext2']['cash'] && $sku['exts']['ext2']['point']) {
                $k_v[$i+6]['name'] = '现金+积分币';
                $k_v[$i+6]['value'] = $sku['exts']['ext2']['cash'].'+'.$sku['exts']['ext2']['point'];
                $k_v[$i+6]['price_type'] = 5;
            }
            if($sku['exts']['ext3']['cash'] && $sku['exts']['ext3']['point']) {
                $k_v[$i+7]['name'] = '现金+抵用券';
                $k_v[$i+7]['value'] = $sku['exts']['ext3']['cash'].'+'.$sku['exts']['ext3']['point'];
                $k_v[$i+7]['price_type'] = 8;
            }
        }
        $data = $good->toArray();
        if($data['thumb']) {
            $data['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$data['thumb'];
        }
        $ref_img = json_decode($data['ref_img']);
        foreach($ref_img as &$row) {
            $row->src = 'http://'.$_SERVER['HTTP_HOST'].$row->src;
        }
        $data['ref_img'] = $ref_img;
        $data['opts'] = $getSkuOpts;
        /*$data['sku'] = $sku;*/
        $data['prices'] = array_values($k_v);
        $data['market_price'] = $sku['market_price'];
        unset($data['price']);
        unset($data['unit']);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

    /**
     * 商品列表
     */
    public function doList() {
        $cid = $this->_request->cid;
        $q = $this->_request->q;
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        if( !$limit || !$page) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $select = M('Goods')->alias('g')
            ->columns('g.id, g.title, g.thumb, g.notes, g.sup, g.thumb')
            ->where('g.is_selling = 1 AND g.is_checked = 2 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time())
            ->paginator((int)$limit, (int)$page);

        //按关键词搜索
        if ($q) {
            //搜索分类
            $cate = M('Goods_Category')->select()
                ->where('name = ?', $q)
                ->fetchRow();
            if ($cate->exists()) {
                $ids = $cate->getChildIds();
                $select->where('(g.category_id IN ('.($ids ? $ids : 0).') OR reverse(g.title) LIKE '. 'reverse("%'.$q.'%")'.' OR reverse(g.tags) like '. 'reverse("%'.$q.'%"))');
            } else {
                $select->where('reverse(g.title) LIKE '. 'reverse("%'.$q.'%")');
            }
        }
        //按分类搜索
        if ($cid) {
            $ids = M('Goods_Category')->getChildIds((int)$cid);
            $select->where('g.category_id IN ('.($ids ? $ids : 0).')');
        }

        $datalsit = $select->fetchRows();
            /*->AppHasManySku();*/
        $data = $datalsit->toArray();
        foreach($data as $key=> &$row) {
            if($row['thumb']) {
                $row['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
            }
            $arrs = M('Goods_Sku')->select('point1,point2,point3,point4,point5,exts,market_price')
                ->where('goods_id ='.(int)$row['id'])
                ->fetchRow()
                ->toArray();
            $k_v = array();
            if($arrs['point1']) {
                $k_v[$key]['name'] = '快乐积分';
                $k_v[$key]['value'] = $arrs['point1'];
            }
            if($arrs['point2']) {
                $k_v[$key+1]['name'] = '帮帮币';
                $k_v[$key+1]['value'] = $arrs['point2'];
            }
            if($arrs['point3']) {
                $k_v[$key+2]['name'] = '积分币';
                $k_v[$key+2]['value'] = $arrs['point3'];
            }
            if($arrs['point4']) {
                $k_v[$key+3]['name'] = '抵用券';
                $k_v[$key+3]['value'] = $arrs['point4'];
            }
            if($arrs['point5']) {
                $k_v[$key+4]['name'] = '现金';
                $k_v[$key+4]['value'] = $arrs['point5'];
            }
            if($arrs['exts']) {
                if($arrs['exts']['ext1']['cash'] && $arrs['exts']['ext1']['point']) {
                    $k_v[$key+5]['name'] = '现金+帮帮币';
                    $k_v[$key+5]['value'] = $arrs['exts']['ext1']['cash'].'+'.$arrs['exts']['ext1']['point'];
                }
                if($arrs['exts']['ext2']['cash'] && $arrs['exts']['ext2']['point']) {
                    $k_v[$key+6]['name'] = '现金+积分币';
                    $k_v[$key+6]['value'] = $arrs['exts']['ext2']['cash'].'+'.$arrs['exts']['ext2']['point'];
                }
                if($arrs['exts']['ext3']['cash'] && $arrs['exts']['ext3']['point']) {
                    $k_v[$key+7]['name'] = '现金+抵用券';
                    $k_v[$key+7]['value'] = $arrs['exts']['ext3']['cash'].'+'.$arrs['exts']['ext3']['point'];
                }
            }

            $row['market_price'] = $arrs['market_price'];
            $row['prices'] = array_slice(array_values($k_v),0,2);
        }
        unset($data['price']);
        unset($data['unit']);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商品品牌页---侧边栏
     */
    public function doCategory() {
        $cid = $this->_request->cid;
        if(!$cid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cates = M('Goods_Category')->select('id,name')
            ->where('parent_id = '.(int)$cid.' and is_enabled<>0')
            ->order('rank ASC, id ASC')
            ->fetchRows();
        $brand = M('Brand')->select('id,category_id,title,thumb,ref_img')->where('category_id='.(int)$cid)->fetchRow();
        if (!$brand->exists()) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求资源不存在');
            die();
        }
        $data = array(
            'cates' => $cates->toArray(),
            'brand' => $brand->toArray()
        );
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商品分类列表
     */
    public function doCategoryList() {
        $cid = $this->_request->cid;
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        if(!$cid || !$limit || !$page) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cates = M('Goods_Category')->select('id,name')
            ->where('parent_id = '. (int)$cid.' and is_enabled<>0')
            ->order('rank ASC, id ASC')
            ->paginator((int)$limit, (int)$page)
            ->fetchRows();
        $data = array();
        foreach($cates as $key => $cate) {
            $childs = $cates = M('Goods_Category')->select('id,name,thumb')
                ->where('parent_id = '. (int)$cate['id'].' and is_enabled<>0')
                ->order('rank ASC, id ASC')
                ->fetchRows()->toArray();
            foreach($childs as &$row) {
                if($row['thumb']) {
                    $row['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
                }
            }
            $data[$key]['name'] = $cate['name'];
            $data[$key]['childs'] =$childs;
        }

        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

    /**
     * 品牌墙列表
     */
    public function doBrandList() {
        $cid = $this->_request->cid;
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        if(!$cid || !$limit || !$page) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $data = M('Brand')->select('id,name,thumb')
            ->where('category_id='.(int)$cid)
            ->order('id ASC')
            ->paginator((int)$limit, (int)$page)
            ->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 判断规格的库存量
     */
    public function doGetgoodsku() {
        $param = $this->_request->param;
        if(!$param) {
            echo  self::_error_data(API_MISSING_PARAMETER,'请选择规格');
            die();
        }
        $param1 = '';
        if(strpos($param,',')) {
            $param1 = explode(',',$param);
            $param1 = $param1[1].','.$param1[0];
        }
        $good_id = (int)$this->_request->good_id;
        $quantity = $sku_id = 0;
        $arrs = M('Goods_Sku')->select()
            ->where('goods_id = '.$good_id.' and (reverse(spec) LIKE '. 'reverse("%'.$param.'%") or reverse(spec) LIKE '.'reverse("%'.$param1.'%")'.' )')
            ->fetchRows()->toArray();
        if(count($arrs)) {
            foreach ($arrs as $arr) {
                $quantity += $arr['quantity'];
                $sku_id = $arr['id'];
            }
        } else {
            $quantity = 'error';
        }
        $data = array('quantity'=>$quantity,'sku_id'=>$sku_id);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商品评价
     */
    public function doComment() {
        $good_id = $this->_request->good_id;
        $shop = M('Goods')->select()->where('id='.(int)$good_id)->fetchRow()->toArray();
        if(!$shop) {
            echo  self::_error_data(API_GOOD_NOT_FOUND,'商品不存在');
            die();
        }
        $data = M('Goods_Comment')->select('comment,photos,create_time')->where('good_id='.(int)$good_id.' and is_show <> 0')->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

    /**
     * 添加商品评论
     */
    public function doAddComment() {
        $this->user = $this->_auth();
        $uid = $this->user->id;
        $good_id = $this->_request->goods_id;
        $order_id = $this->_request->order_id;
        $sku_id = $this->_request->sku_id;
        $spec = $this->_request->spec;
        $comment = $this->_request->comment;
        $ext1 = $this->_request->ext1;
        $ext2 = $this->_request->ext2;
        $ext3 = $this->_request->ext3;
        if(!$good_id || !$order_id || !$sku_id || !$comment) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->select()->where('id='.(int)$order_id)->fetchRow()->toArray();
        if(!$order) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        $good = M('Goods')->select()->where('id='.(int)$good_id)->fetchRow()->toArray();
        if(!$good) {
            echo  self::_error_data(API_GOOD_NOT_FOUND,'评价的商品不存在');
            die();
        }
        $sku = M('Goods_Sku')->select()->where('id='.(int)$sku_id)->fetchRow()->toArray();
        if(!$sku) {
            echo  self::_error_data(API_GOOD_SKU_NOT_FOUND,'此商品的规格不存在');
            die();
        }
        if(!$comment) {
            echo  self::_error_data(API_COMMENT_NOT_NULL,'评论不能为空');
            die();
        }
        //ext1 服务态度 ext2 店铺环境   ext3 价格合理

        $extr = array('ext1'=>$ext1,'ext2'=>$ext2,'ext3'=>$ext3);
        $data['goods_id'] = $good_id;
        $data['buyer_id'] = $uid;
        $data['order_id'] = $order_id;
        $data['sku_id'] = $sku_id;
        $data['spec'] = $spec;
        $data['comment'] = $comment;
        $data['create_time'] = time();
        $data['extr'] = json_encode($extr);
        $image = $this->Upload();
        $data['photos'] = 'http://'.$_SERVER['HTTP_HOST'].$image['src'];
        $insert = M('Goods_Comment')->insert($data);
        if(!$insert) {
            echo  self::_error_data(API_COMMENT_FAIL,'评价失败');
            die();
        }
        $data = array_merge($data,$extr);
        //echo $this->_encrypt_data($data);
        echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    public function doDesc() {
        $good_id = $this->_request->id;
        $view = $this->_initView();
        $good = M('Goods')->select('description')->where('id='.(int)$good_id)->fetchRow()->toArray();
        if(!$good_id || !$good) {
            $view->render('views/app/404.php');
        } else {
            $view->desc = $good['description'];
            $view->render('views/app/products.php');
        }
    }
    /**
     * 领取红包
     */
    public function doReceive() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->getById((int)$oid);
        if(!$order) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        if ($order['total_earn_points']) {
            $order->buyer->credit($order['total_earn_points'], '消费'.$order['total_amount'].'元，领取积分红包'.$order['total_earn_points'].'点');
        } else {
            echo  self::_error_data(API_MISSING_PARAMETER,'请求有误，请联系管理员');
            die();
        }
        $order->is_receive = 1;
        $order->save();
        $data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 取消订单
     */
    public function doCancelOrder() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->getById((int)$oid);
        if(!$order) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        $order->CancelOrder();
        $desc = date('Y-m-d H:i:s',time()).'-买家取消订单';
        $order->cancel($desc);
        $data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 删除订单
     */
    public function doDelOrder() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->getById((int)$oid);
        if(!$order) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        M('Order')->deleteById($oid);
        $data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 确认收货
     */
    public function doConfirm() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->getById((int)$oid);
        if(!$order) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        $order->confirm(date(DATETIME_FORMAT)." - 买家确认签收\r\n");
        $data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 查看物流
     * h5页面
     */
    public function doExpress() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->getById((int)$oid);
        if(!$order) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
    }
}