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

    public function doDefault()
    {
        var_dump("1111");
        die();
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
        $filed = 'id,category_id,title,sup,description,thumb,ref_img,notes,shipping_id,sales_num,quantity';
        $good = M('Goods')->select($filed)->where('id='.(int)$id)->fetchRow();
        $getSkuOpts = $good->AppGetSkuOpts();
        $sku = M('Goods_Sku')->select('market_price,point1,point2,point3,point4,point5,exts')
            ->where('goods_id = ?', (int)$id)
            ->order('id desc')
            ->fetchRow()->toArray();
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
        $data['sku'] = $sku;
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

        $datalsit = $select->fetchRows()
            ->AppHasManySku();
        $data = $datalsit->toArray();
        foreach($data as &$row) {
            if($row['thumb']) {
                $row['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
            }
        }
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
            ->fetchRows()->toArray();
        $brand = M('Brand')->select('id,category_id,title,thumb,ref_img')->where('category_id='.(int)$cid)->fetchRow()->toArray();
        $data = array(
            'cates' => $cates,
            'brand' => $brand
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
            $data['cates'][$key]['name'] = $cate['name'];
            $data['cates'][$key]['childs'] =$childs;
        }

        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
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
     * 商品评价
     */
    public function doComment() {

    }

    /**
     * 添加商品评论
     */
    public function doAddComment() {
        $data = $this->_request->post();
        M('Goods_Comment')->insert(array_merge($data, $this->_request->getFiles()));
    }

}