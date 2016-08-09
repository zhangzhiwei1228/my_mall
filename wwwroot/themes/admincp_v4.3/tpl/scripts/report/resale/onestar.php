<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午5:12
 */

if(!defined('APP_KEY')) { exit('Access Denied'); }
switch($this->user['resale_grade']) {
    case 1: $this->head()->setTitle('创业一星分销商'); break;
    case 2: $this->head()->setTitle('创业二星分销商'); break;
    case 3: $this->head()->setTitle('创业三星分销商'); break;
    case 4: $this->head()->setTitle('创业四星分销商'); break;
}

$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-page-header">
    <h1> <?=$this->head()->getTitle()?></h1>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">当月推广效果</div>
            <?php include WWW_DIR.'themes/admincp_v4.3/tpl/scripts/report/resale/detail_heard.php'?>
            <div class="panel-body">
                <div class="staff-jifen bgwhite"><p class="w90">本月发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['num']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">本月激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['vip']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">历史发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['num']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">历史激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['vip']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">本月发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['num']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">本月激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['vip']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">历史发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['num']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">历史激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['vip']?></em>&nbsp;个</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">我的一级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin1']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
                <div class="staff-jifen bgwhite"><p class="w90">我的二级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin2']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
                <?php if ($this->user['resale_grade'] >= 2) { ?>
                    <div class="staff-jifen bgwhite"><p class="w90">我的商家的一级会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin3']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
                    <div class="staff-jifen bgwhite"><p class="w90">我的商家的二级会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin4']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
                <?php } ?>
                <div class="staff-jifen bgwhite">
                    <a href="<?=$this->url('/index/shoplist')?> ">
                        <p class="w90">发展的商家本月使用免费积分(点击查看)：<span class="fr"><em><?=(float)$this->bonus['seller']['credit']['total']?></em>&nbsp;分</span></p>
                    </a>
                </div>
                <div class="month-income bgwhite">
                    <p class="income01 w90">我的本月收益</p>
                    <p class="income01 w90"><span><?=$this->bonus['amount']?></span>&nbsp;元</p>
                </div>

            </div>
        </div>
    </div>
</div>
