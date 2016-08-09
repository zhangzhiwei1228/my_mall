<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午5:13
 */

if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('');
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
                <?php if ($this->user['role'] == 'agent') { ?>
                    <div class="staff-jifen bgwhite"><p class="w90">我代理地区商家本月使用免费积分：<span class="fr"><em><?=(float)$this->bonus['area']['seller']['t_credit']?></em>&nbsp;分</span></p></div>
                    <div class="staff-jifen bgwhite"><p class="w90">我代理地区会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['area']['member']['t_coin']?></em>&nbsp;币</span></p></div>
                    <div class="month-income bgwhite">
                        <p class="income01 w90">我的本月收益</p>
                        <p class="income01 w90"><span><?=(float)$this->bonus['amount']?></span>&nbsp;元</p>
                    </div>
                <?php } elseif ($this->user['role'] == 'seller') { ?>
                    <div class="staff-jifen bgwhite"><p class="w90">我员工发展的一级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin1']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
                    <div class="staff-jifen bgwhite"><p class="w90">我员工发展的二级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin2']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
                    <div class="month-income bgwhite">
                        <p class="income01 w90">我的本月收益</p>
                        <p class="income01 w90"><span><?=(float)$this->bonus['amount']?></span>&nbsp;元</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>