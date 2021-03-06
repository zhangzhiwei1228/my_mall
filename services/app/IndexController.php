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
        $pro = array();
        $city1 = array();
        $area1 = array();
        foreach($user as $key=>$row) {
            //$rows['more'][$key] = $row;
            $pro[$row['id']] = $row;
            $city  = M('Region')->select('*')->where('parent_id='.(int)$row['id'])->fetchRows()->toArray();
            foreach($city as $key1=>$c) {
                /*$rows['more'][$key]['more'][$key1] = $c;
                $areas = M('Region')->select('*')->where('parent_id='.(int)$c['id'])->fetchRows()->toArray();
                foreach($areas as $key2=>$area) {
                    $rows['more'][$key]['more'][$key1]['more'][$key2] = $area;
                }*/
                $city1[$c['id']] = $c;
                $areas = M('Region')->select('*')->where('parent_id='.(int)$c['id'])->fetchRows()->toArray();
                foreach($areas as $key2=>$area) {
                    $area1[$area['id']] = $area;
                }
            }
        }
        $rows = array_merge($pro,$city1,$area1);
        echo json_encode($rows);
        die();
        //echo $this->_encrypt_data($rows);
        //echo $this->show_data($this->_encrypt_data($rows));
        die();
    }

    /**
     * 商场类别
     */
    public function doGoodsCategory() {
        $datas = M('Navigate')->select('id,name,icon,redirect')
            ->where('parent_id = 0 AND type = ? AND is_enabled <> 0', 'main')
            ->order('rank ASC, id ASC')
            ->fetchRows()->toArray();
        foreach($datas as &$row) {
            $row['cid'] = $this->convertUrlQuery($row['redirect']);
            if($row['icon']) {
                $row['icon'] = 'http://'.$_SERVER['HTTP_HOST'].$row['icon'];
            }
            unset($row['redirect']);
        }
        /*$datas = M('Goods_Category')->select('id,name')
                ->where('parent_id = 0 and is_enabled<>0')
                ->order('rank ASC, id ASC')
                ->fetchRows()->toArray();*/
        echo $this->_encrypt_data($datas);
        //echo $this->show_data($this->_encrypt_data($datas));
        die();
    }

    /**
     * @param $query
     * @return array
     */
    public function convertUrlQuery($query)
    {
        $queryParts = explode('?', $query);
        $params = 0;
        $item = explode('=', $queryParts[1]);
        if($item[0] == 'cid') {
            $params = $item[1];
        }
        return $params;
    }
    /**
     * 首页头部、首页中上部、中左部、中右部广告位
     */
    public function doAdvertise() {
        $data = array('AppHomeHeard','AppCenterTop','AppCenterLeft','AppCenterRight','AppHotMarket');
        $home_heard = M('Advert')->getAppRowsByCode($data);
        echo $this->_encrypt_data($home_heard);
        //echo $this->show_data($this->_encrypt_data($home_heard));
        die();
    }
    /**
     * 合作商家
     */
    public function doTeamShop() {
        $type_ids = M('Coltypes')->select('id,name')->where('english='."'shop"."'")->fetchRows()->toArray();
        $data = array();
        $i = 0;
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
                $data[$i]['shop'] = $shops ;
                $data[$i]['name'] = $row['name'];
                $data[$i]['is_special'] = $key;
            }
            $i++;
        }
        $data = array_values($data);
        echo $this->_encrypt_data($data);

        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 本月精选
     */
    public function doSelectGoods() {
        $limit  = $this->_request->limit ? $this->_request->limit : 4;
        $page  = $this->_request->page ? $this->_request->page : 1 ;
        $goods = M('Goods')->alias('g')
            ->columns('g.id,g.title,g.thumb,g.notes,g.sup')
            ->where('g.is_selling = 1 AND g.is_checked = 2 and ((g.quantity-g.quantity_warning) > 0) and is_select = 1 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time())
            ->paginator((int)$limit, (int)$page)
            ->order('g.create_time DESC')
            ->fetchRows()
            ->toArray();
        $sup = array();
        $i = 0;
        foreach($goods as $key=>$row) {
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
            if($arrs['point5'] > 0) {
                $k_v[$key+4]['name'] = '现金';
                $k_v[$key+4]['value'] = $arrs['point5'];
            }
            if($arrs['exts']) {
                if($arrs['exts']['ext1']['cash'] && $arrs['exts']['ext1']['point']) {
                    $k_v[$key+5]['name'] = '现金+帮帮币';
                    $k_v[$key+5]['value'] = '￥'.number_format($arrs['exts']['ext1']['cash'],2).'+'.$arrs['exts']['ext1']['point'];
                }
                if($arrs['exts']['ext2']['cash'] && $arrs['exts']['ext2']['point']) {
                    $k_v[$key+6]['name'] = '现金+积分币';
                    $k_v[$key+6]['value'] = '￥'.number_format($arrs['exts']['ext2']['cash'],2).'+'.$arrs['exts']['ext2']['point'];
                }
                if($arrs['exts']['ext3']['cash'] && $arrs['exts']['ext3']['point']) {
                    $k_v[$key+7]['name'] = '现金+抵用券';
                    $k_v[$key+7]['value'] = '￥'.number_format($arrs['exts']['ext3']['cash'],2).'+'.$arrs['exts']['ext3']['point'];
                }
            }
            $goods[$key]['market_price'] = $arrs['market_price'];
            $sup1 = json_decode($row['sup']);
            foreach($sup1 as $k => $v) {
                $sup[$i]['name'] = $k;
                $sup[$i]['value'] = $v;
                $i++;
            }
            $goods[$key]['sup'] =array_values($sup);
            unset($sup);
            $goods[$key]['prices'] = array_slice(array_values($k_v),0,2);
            $goods[$key]['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
        }
        echo $this->_encrypt_data($goods);
        //echo $this->show_data($this->_encrypt_data($goods));
        die();
    }
    /**
     * 消息
     */
    public function doNewsList() {
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        if(!$limit || !$page) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cid = 14;
        $select = M('Article')->select('id,title,create_time')
            ->where('is_checked = 2')
            ->paginator((int)$limit, (int)$page);
        $ids = M('Article_Category')->getChildIds((int)$cid);
        $select->where('category_id IN ('.($ids ? $ids : 0).') and category_id <> 15');
        $select->order('id DESC');
        $data = $select->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 消息详情
     */
    public function doNewDetail() {
        $id = $this->_request->id;
        if( !$id ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $news = M('Article')->select('content')->where('id='.(int)$id)->fetchRow()->toArray();
        if(!$news) {
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        M('Article')->updateById(array('is_looked' => 1), (int)$id);
        $view = $this->_initView();
        $view->content = $news['content'];
        $view->render('views/app/news_info.php');

    }
    /**
     * 推送
     */
    public function doPush() {
        $this->user = $this->_auth();
        $artice = M('Article')->getById(94);
        $exts = array (
            'title' => $artice['title'],
            'content' => strip_tags($artice['content']),
            'extras' => array(
                'id' => 94,
                'type' => 1//1是消息2是新品3是新品发货
            )
        );
        $data = M('Jpush')->push($this->user->id,1,$exts);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 常见问题
     */
    public function doQuestion() {
        $limit = $this->_request->limit ? $this->_request->limit : 10;
        $page = $this->_request->page ? $this->_request->page : 1;
        $news = M('Article')->select('id,title,content')->where('category_id = 22')->paginator($limit, $page)->fetchRows();
        $data = $news->toArray();
        $cutstr = new Suco_Helper_Cutstr();
        foreach ($data as &$row) {
            $row['desc'] = $cutstr->cutstr(strip_tags($row['content']),50);
        }
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     *  商城须知
     */
    public function doShopMall() {
        $view = $this->_initView();
        $view->description = M('Page')->getByCode('description')->toArray();
        $view->title = '商城须知';
        $view->render('views/app/shopping_mall.php');
    }
    /**
     *  常见问题详情页
     */
    public function doQuestionDetail() {
        $id = $this->_request->id;
        $news = M('Article')->select('title,content')->where('id = '.(int)$id)->fetchRow()->toArray();
        if( !$id ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        if(!$news) {
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        $view = $this->_initView();
        $view->description = $news;
        $view->title = $news['title'];
        $view->render('views/app/shopping_mall.php');
    }
    /**
     * 版本控制
     */
    public function doVersionControl() {
        $data = M('App')->select()->order('create_time desc')->fetchRow();
        $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].$data['url'];
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data->toArray()));
        die();
    }
    /**
     * 分享下载
     *
     */
    public function doDownload() {
        $view = $this->_initView();
        $view->render('views/app/download.php');
    }
    /**
     * neigou
     */
    public function doUrl() {
        if(kVerifyReceipt) {
            $data['url'] = 'https://buy.itunes.apple.com/verifyReceipt';
        } else {
            $data['url'] = 'https://sandbox.itunes.apple.com/verifyReceipt';
        }
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 服务协议
     */
    public function doServer() {
        $view = $this->_initView();
        $view->description =M('Page')->getById(55)->toArray();
        //$view->title = '服务协议';
        $view->render('views/app/shopping_mall.php');
    }
    /**
     * 邀请码说明
     */
    public function doInviteDesc() {
        $view = $this->_initView();
        $view->description =M('Page')->getById(98)->toArray();
        //$view->title = '邀请码说明';
        $view->render('views/app/shopping_mall.php');
    }

}