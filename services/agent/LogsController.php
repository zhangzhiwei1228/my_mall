<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-9
 * Time: 上午11:47
 */


class Agent_LogsController extends Agent_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
    }
    //充值记录
    public function doRecharge()
    {
        $uid = $this->user->id;
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $dayBegin = $this->_request->start_time ? strtotime($this->_request->start_time) : mktime(0,0,0,$month,$day,$year);//当天开始时间戳
        $dayEnd = $this->_request->end_time ? strtotime($this->_request->end_time) : mktime(23,59,59,$month,$day,$year);//当天结束时间戳

        $view = $this->_initView();
        $view->datalist = M('User_Credit')->select()
            ->where('user_id = '.(int)$uid." and type='".$this->_request->t."'".' and credit>0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
            ->order('id DESC')
            ->paginator(20, $this->_request->page)
            ->fetchRows();
        $view->render('views/logs/recharge.php');
    }

    //使用记录
    public function doEmploy()
    {
        $uid = $this->user->id;
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $dayBegin = $this->_request->start_time ? strtotime($this->_request->start_time) : mktime(0,0,0,$month,$day,$year);//当天开始时间戳
        $dayEnd = $this->_request->end_time ? strtotime($this->_request->end_time) : mktime(23,59,59,$month,$day,$year);//当天结束时间戳

        $view = $this->_initView();
        $view->datalist = M('User_Credit')->select()
            ->where('user_id = '.(int)$uid." and type='".$this->_request->t."'".' and credit<0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
            ->order('id DESC')
            ->paginator(20, $this->_request->page)
            ->fetchRows();
        $view->render('views/logs/employ.php');
    }
}