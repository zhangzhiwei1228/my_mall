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
        echo $this->_encrypt_data($rows);
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
        $goods = M('Goods')->alias('g')
            ->columns('g.id,g.title,g.thumb,g.notes')
            ->where('g.is_selling = 1 AND g.is_checked = 2 and ((g.quantity-g.quantity_warning) > 0) and is_select = 1 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time())
            ->limit(4)->order('g.create_time DESC')->fetchRows()->toArray();
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
            $goods[$key]['market_price'] = $arrs['market_price'];
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
        $data = M('Jpush')->push(array('266'));
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

}