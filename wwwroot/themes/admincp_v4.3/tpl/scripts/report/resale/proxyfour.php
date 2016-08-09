<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午5:08
 */
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('创业四星分销商管理员');
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
                <p>
                    <span class="n-dealer-span1">
                        <p>我代理地区我下线的</p>
                        <p>商家本月使用免费积分：</p>
                    </span>
                    <span class="n-dealer-span3">分</span>
                    <span class="n-dealer-span2"><?=(float)$this->bonus['area']['seller']['t_credit']?></span>
                </p>
                <p>
                    <span class="n-dealer-span1">
                        <p>我代理地区我下线的</p>
                        <p>会员本月消费积分币：</p>
                    </span>
                    <span class="n-dealer-span3">币</span>
                    <span class="n-dealer-span2"><?=(float)$this->bonus['area']['member']['t_coin']?></span>
                </p>

                <p>
                    <span>我的本月收益</span>
                    <span><?=$this->bonus['amount']?></span>
                    <span>元</span>
                </p>
            </div>
        </div>
    </div>
</div>