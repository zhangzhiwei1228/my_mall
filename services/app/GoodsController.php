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
        $data['prices'] = is_array($k_v) ? array_values($k_v) : $k_v;
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
        $i = 0;
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
            $sup1 = json_decode($row['sup']);
            foreach($sup1 as $k => $v) {
                $sup[$i]['name'] = $k;
                $sup[$i]['value'] = $v;
                $i++;
            }
            $row['sup'] =array_values($sup);
            unset($sup);
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
        //echo $this->_encrypt_data($data);
        echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商品评价
     */
    public function doCommentList() {
        $good_id = $this->_request->good_id;
        $shop = M('Goods')->select()->where('id='.(int)$good_id)->fetchRow()->toArray();
        if(!$shop) {
            echo  self::_error_data(API_GOOD_NOT_FOUND,'商品不存在');
            die();
        }
        $data = M('Goods_Comment')->select('buyer_id,comment,photos,create_time')->where('goods_id='.(int)$good_id.' and is_show <> 0')->fetchRows()->toArray();
        foreach($data as  &$row) {
            $user = M('User')->select('avatar,nickname')->where('id = '.(int)$row['buyer_id'])->fetchRow()->toArray();
            $row['avatar'] = 'http://'.$_SERVER['HTTP_HOST'].$user['avatar'];
            $row['nickname'] = $user['nickname'];
            $src = json_decode($row['photos']);
            foreach($src as $key =>$val) {
                $d = get_object_vars($val);
                $row['src'][] =  $d['src'];
            }
            unset($row['photos']);
        }
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
    /**
     * 添加商家评论--多图
     */
    public function doAddComments() {
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
        $data['is_show'] = 1;
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
        $insert = M('Goods_Comment')->insert($data);
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
        $data = array('point'=>$order['total_earn_points']);
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
        //$order->CancelOrder();
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
        $delivery = M('Order_Delivery')->select()->where('order_id='.(int)$oid)->fetchRow()->toArray();
        $view = $this->_initView();
        $view->delivery = $delivery;
        $view->kuaidi100 = M('Kuaidi100')->toArray();

        $view->render('views/app/order.php');
    }
    /**
     * 订单列表
     */
    public function doOrderList() {
        $this->user = $this->_auth();
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        $status = $this->_request->status; //0废单,1待付款,2待发货,3待签收,4已完成，5处理超时
        $select = M('Order')->alias('o')
            ->leftJoin(M('User')->getTableName().' AS b', 'o.buyer_id = b.id')
            ->leftJoin(M('User')->getTableName().' AS s', 'o.seller_id = s.id')
            ->leftJoin(M('Payment')->getTableName().' AS p', 'o.payment_id = p.id')
            ->leftJoin(M('Shipping')->getTableName().' AS d', 'o.shipping_id = d.id')
            ->columns('o.area_id,o.id,o.shipping_id,o.total_credit,o.total_credit_happy,o.total_credit_coin,o.total_vouchers,o.total_weight,o.total_quantity,o.order_json,o.total_amount,o.total_earn_points,o.is_receive ')
            ->where('o.buyer_id = '. $this->user->id.' and o.expiry_time != 0 AND o.expiry_time >= '.time())
            ->order('id DESC')
            ->paginator($limit, $page);
        if($status && $status != 6) {
            $select->where('o.status = '.(int)$status);
        }

        switch ($this->_request->sm) {
            case 'code':
                $this->_request->keyword && $select->where('o.code = ?', $this->_request->keyword);
                break;
            case 'consignee':
                $this->_request->keyword && $select->where('o.consignee LIKE ?', '%'.$this->_request->keyword.'%');
                break;
            case 'buyer_account':
                $this->_request->keyword && $select->where('b.username LIKE ?', '%'.$this->_request->keyword.'%');
                break;
        }
        if ($this->_request->begin_time) {
            $select->where('o.create_time >= ?', strtotime($this->_request->begin_time));
        }
        if ($this->_request->end_time) {
            $select->where('o.create_time <= ?', strtotime($this->_request->end_time) + (3600 * 24));
        }
        $datas = $select->fetchRows()->toArray();

        foreach($datas as $key2=> &$row) {
            $area_id = $row['area_id'];
            $shipping_id = $row['shipping_id'];
            $order_json = json_decode($row['order_json']);
            foreach($order_json as $key =>&$val) {
                $val = get_object_vars($val);
                unset($val['thumb']);
                unset($val['points']);
                if(strpos($val['skus_id'],',')) {

                    $sku_ids = explode(',',$val['skus_id']);
                    foreach($sku_ids as $k => $sku_id) {
                        $sku = M('Goods_Sku')->select()->where('id = ?', (int)$sku_id)->fetchRow()->toArray();
                        $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                        $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                        $good['price_text'] = $val['price_text']->$sku_id;

                        $spec = explode(',',$sku['spec']);
                        $arr = array();
                        foreach($spec as $key1=>$val1) {
                            $val1 = substr($val1,0,strlen($val1)-1);
                            $val1 = substr($val1,1);
                            $val1 = explode(':',$val1);
                            $arr[$key1]['name'] = $val1[0];
                            $arr[$key1]['value'] = $val1[1];
                        }
                        $good['spec'] = $arr;
                        unset($good['price']);
                        unset($good['unit']);
                        $good['sku_id'] = $sku_id;
                        $val['goods'][$k] = $good;
                    }
                } else {
                    $sku = M('Goods_Sku')->select()->where('id = ?', (int)$val['skus_id'])->fetchRow()->toArray();
                    $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                    $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                    $good['price_text'] = $val['price_text'];
                    $spec = explode(',',$sku['spec']);
                    $arr = array();
                    foreach($spec as $key1=>$val1) {
                        $val1 = substr($val1,0,strlen($val1)-1);
                        $val1 = substr($val1,1);
                        $val1 = explode(':',$val1);
                        $arr[$key1]['name'] = $val1[0];
                        $arr[$key1]['value'] = $val1[1];
                    }
                    $good['spec'] = $arr;
                    unset($good['price']);
                    unset($good['unit']);
                    $good['sku_id'] = $val['skus_id'];
                    $val['goods'][] = $good;
                }
                unset($row['order_json']);
                $order['shipping_id'] = $shipping_id;
                $order['area_id'] = $area_id;
                $postage = $this->doPostAge($order, $val['total'], $val['weight']);
                $val['total_postage'] = $postage;
                $row['packages'] = $order_json;
            }
        }

        echo $this->_encrypt_data($datas);
        //echo $this->show_data($this->_encrypt_data($datas));
        die();
    }
    /**
     * 订单列表
     */
    public function doOrderList1() {
        $this->user = $this->_auth();
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        $status = $this->_request->status; //0废单,1待付款,2待发货,3待签收,4已完成，5处理超时
        $select = M('Order')->alias('o')
            ->leftJoin(M('User')->getTableName().' AS b', 'o.buyer_id = b.id')
            ->leftJoin(M('User')->getTableName().' AS s', 'o.seller_id = s.id')
            ->leftJoin(M('Payment')->getTableName().' AS p', 'o.payment_id = p.id')
            ->leftJoin(M('Shipping')->getTableName().' AS d', 'o.shipping_id = d.id')
            ->columns('o.area_id,o.id,o.shipping_id,o.total_credit,o.total_credit_happy,o.total_credit_coin,o.total_vouchers,o.total_weight,o.total_quantity,o.order_json,o.total_amount,o.status,o.code,o.create_time,o.expiry_time,o.pay_time,o.delivery_time,o.confirm_time,o.total_earn_points,o.is_receive,o.is_return ')
            ->where('o.buyer_id = '. $this->user->id)//. ' and o.is_return = 0'
            ->order('id DESC')
            ->paginator($limit, $page);
        if($status && $status != 6) {
            $select->where('o.status = '.(int)$status.' and is_return <> 1'.' and o.expiry_time != 0 AND o.expiry_time >= '.time());
        }
        if($status && $status == 6) {
            $select->where('o.status > 0 and o.status <= 4');
        }

        switch ($this->_request->sm) {
            case 'code':
                $this->_request->keyword && $select->where('o.code = ?', $this->_request->keyword);
                break;
            case 'consignee':
                $this->_request->keyword && $select->where('o.consignee LIKE ?', '%'.$this->_request->keyword.'%');
                break;
            case 'buyer_account':
                $this->_request->keyword && $select->where('b.username LIKE ?', '%'.$this->_request->keyword.'%');
                break;
        }
        if ($this->_request->begin_time) {
            $select->where('o.create_time >= ?', strtotime($this->_request->begin_time));
        }
        if ($this->_request->end_time) {
            $select->where('o.create_time <= ?', strtotime($this->_request->end_time) + (3600 * 24));
        }
        $datas = $select->fetchRows()->toArray();
        
        $sku_ids = array();
        $pageage = 0;
        $price_text = array();
        $type = array();
        foreach($datas as $key2=> &$row) {
            $area_id = $row['area_id'];
            $shipping_id = $row['shipping_id'];
            $order_json = json_decode($row['order_json']);

            foreach($order_json as $key =>$val) {
                $val = get_object_vars($val);
                unset($val['thumb']);
                unset($val['points']);

                if(strpos($val['skus_id'],',')) {

                    $ids = explode(',',$val['skus_id']);
                    foreach($ids as $id) {
                        $sku = M('Goods_Sku')->select()->where('id = ?', (int)$id)->fetchRow()->toArray();
                        $sku['price_type'] = $val['price_type']->$id;
                        $sku['exts'] = json_encode($sku['exts']);
                        $price_type = M('User_Cart')->price_type($sku);
                        $type[$id] = $price_type['price_text'];
                        $sku_ids[] = $id;
                        $price_text[$id] = $val['price_text']->$id;
                    }

                } else {

                    $sku = M('Goods_Sku')->select()->where('id = ?', (int)$val['skus_id'])->fetchRow()->toArray();
                    $sku['price_type'] = $val['price_type'];
                    $sku['exts'] = json_encode($sku['exts']);
                    $price_type = M('User_Cart')->price_type($sku);
                    $type[$val['skus_id']] = $price_type['price_text'];
                    $sku_ids[] = $val['skus_id'];
                    $price_text[$val['skus_id']] = $val['price_text'];
                }

                $order['shipping_id'] = $shipping_id;
                $order['area_id'] = $area_id;
                $postage = $this->doPostAge($order, $val['total'], $val['weight']);
                $pageage += $postage;
            }
            //$row['sku_ids'] = $sku_ids;
            unset($row['order_json']);
            foreach($sku_ids as $value) {
                //print_r($value);
                $sku = M('Goods_Sku')->select()->where('id = ?', (int)$value)->fetchRow()->toArray();

                $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                $comments = M('Goods_Comment')->select('id')->where('goods_id = '.(int)$good['id'].' and buyer_id = '.(int)$this->user->id)->fetchRow()->toArray();
                $good['is_comments'] = $comments ? 1 : 0;
                $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                $good['price_text'] = $val['price_text'];
                $spec = explode(',',$sku['spec']);
                $arr = array();
                foreach($spec as $key1=>$val1) {
                    $val1 = substr($val1,0,strlen($val1)-1);
                    $val1 = substr($val1,1);
                    $val1 = explode(':',$val1);
                    $arr[$key1]['name'] = $val1[0];
                    $arr[$key1]['value'] = $val1[1];
                }
                $good['spec'] = $arr;
                unset($good['price']);
                unset($good['unit']);
                $good['sku_id'] = $val['skus_id'];
                $good['price_text'] = $price_text[$value];
                $good['price_type'] = $type[$value];
                $row['goods'][] = $good;
            }
            unset($sku_ids);
            $row['total_postage'] = $pageage;

        }

        echo $this->_encrypt_data($datas);
        //echo $this->show_data($this->_encrypt_data($datas));
        die();
    }
    /**
     * @param $order
     * @param int $total
     * @param int $weight
     * @return float
     */
    public function doPostAge($order, $total=0, $weight=0) {
        //计算邮费
        $total_quantity = $total ? $total : round($order['total_weight'],2);
        $total_weight = $weight ? $weight : round($order['total_quantity'],2);
        $user_adder_area_id = (int)$order['area_id'];
        $shipping_id = (int)$order['shipping_id'];
        $region = M('Region')->getById($user_adder_area_id);
        //$region_parent_id = (int)$region['parent_id'];

        $region_path_ids = $region['path_ids'];
        $region_path_ids = explode(',',$region_path_ids);
        $region_province = $region_path_ids[2];//获取省
        $region_city = $region_path_ids[3];//获取市
        //在存运费的时候，如果存的是全省中的，那destination的值是省id，如果是某些市，那会是市id
        $shipping_freight = M('Shipping_Freight')->select()
            ->where('shipping_id = '. $shipping_id.' and (destination like '.'"%'.$region_province.'%" or destination like '.'"%'.$region_city.'%")')->fetchRow();

        $first_weight = (int)$shipping_freight['first_weight'];//首重
        $first_freight = round($shipping_freight['first_freight'],2);//一千克首重价格
        $second_weight = (int)$shipping_freight['second_weight'];//继重
        $second_freight = round($shipping_freight['second_freight'],2);//一千克继重价格
//		$one_weight = round($total_weight/$total_quantity,3);
//		$one_weight = ceil($total_weight/$total_quantity);//向上取正
        $one_weight = ceil($total_weight);//向上取正

        if($one_weight > $first_weight) {
            $total_postage = $first_weight*$first_freight+($one_weight-$first_weight)*$second_weight*$second_freight;
        } else {
            $total_postage = $first_weight*$first_freight;
        }
//		return round($total_quantity*$total_postage,2);
        return round($total_postage,2);
    }
    /**
     * 退款
     */
    public function doRefund() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->select()->where('id = '.$oid.' and buyer_id = '.$this->user->id)->fetchRow();
        if(!$order ) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        if($order['is_return'] || ( (!$order['status'] || ($order['status'] && $order['status'] != 2)) )) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        $order->refund(date(DATETIME_FORMAT)." - 买家申请退款\r\n",$this->user->id);
        $data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 订单详情
     */
    public function doOrderDetail() {
        $this->user = $this->_auth();
        $oid = $this->_request->oid;
        if(!$oid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $order = M('Order')->select('id,area_id,shipping_id,total_credit,total_credit_happy,total_credit_coin,total_vouchers,total_weight,total_quantity,order_json,total_amount,status,code,create_time,expiry_time,pay_time,delivery_time,confirm_time,consignee,area_text,address,phone,total_earn_points,is_receive ')->where('id = '.$oid.' and buyer_id = '.$this->user->id)->fetchRow()->toArray();
        if(!$order ) {
            echo  self::_error_data(API_ORDER_NOT_FOUND,'此订单不存在');
            die();
        }
        if((!$order['status'] || $order['status'] == 5) ) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        $area_id = $order['area_id'];
        $shipping_id = $order['shipping_id'];
        $order_json = json_decode($order['order_json']);
        $age = 0;
        foreach($order_json as $key =>&$val) {
            $val = get_object_vars($val);
            unset($val['thumb']);
            unset($val['points']);
            if(strpos($val['skus_id'],',')) {

                $sku_ids = explode(',',$val['skus_id']);
                foreach($sku_ids as $k => $sku_id) {
                    $sku = M('Goods_Sku')->select()->where('id = ?', (int)$sku_id)->fetchRow()->toArray();
                    $sku['price_type'] = $val['price_type']->$sku_id;
                    $sku['exts'] = json_encode($sku['exts']);
                    $price_type = M('User_Cart')->price_type($sku);
                    $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                    $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                    $good['price_text'] = $val['price_text']->$sku_id;
                    $good['qty'] = $val['qty']->$sku_id;
                    $good['price_type'] = $price_type['price_text'];
                    $spec = explode(',',$sku['spec']);
                    $arr = array();
                    foreach($spec as $key1=>$val1) {
                        $val1 = substr($val1,0,strlen($val1)-1);
                        $val1 = substr($val1,1);
                        $val1 = explode(':',$val1);
                        $arr[$key1]['name'] = $val1[0];
                        $arr[$key1]['value'] = $val1[1];
                    }
                    $good['spec'] = $arr;
                    unset($good['price']);
                    unset($good['unit']);
                    $good['sku_id'] = $sku_id;
                    $val['goods'][$k] = $good;
                }
            } else {
                $sku = M('Goods_Sku')->select()->where('id = ?', (int)$val['skus_id'])->fetchRow()->toArray();
                $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                $sku['price_type'] = $val['price_type'];
                $sku['exts'] = json_encode($sku['exts']);
                $price_type = M('User_Cart')->price_type($sku);
                $good['price_type'] = $price_type['price_text'];
                $good['price_text'] = $val['price_text'];
                $good['qty'] = $val['qty'];
                $spec = explode(',',$sku['spec']);
                $arr = array();
                foreach($spec as $key1=>$val1) {
                    $val1 = substr($val1,0,strlen($val1)-1);
                    $val1 = substr($val1,1);
                    $val1 = explode(':',$val1);
                    $arr[$key1]['name'] = $val1[0];
                    $arr[$key1]['value'] = $val1[1];
                }
                $good['spec'] = $arr;
                unset($good['price']);
                unset($good['unit']);
                $good['sku_id'] = $val['skus_id'];
                $val['goods'][] = $good;
            }
            unset($val['price_type']);
            unset($val['price_text']);
            unset($val['qty']);
            unset($order['order_json']);
            $da['shipping_id'] = $shipping_id;
            $da['area_id'] = $area_id;
            $postage = $this->doPostAge($da, $val['total'], $val['weight']);
            $age  += $postage;
            $val['total_postage'] = $postage;
            $order['packages'] = $order_json;
        }
        $order['total_postage'] = $age;
        echo $this->_encrypt_data($order);
        //echo $this->show_data($this->_encrypt_data($order));
        die();
    }

}