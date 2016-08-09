<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午4:54
 */

if(!defined('APP_KEY')) { exit('Access Denied'); }
if ($this->parent['role'] == 'agent') {
    $this->head()->setTitle('创业代理商员工');
 } elseif ($this->parent['role'] == 'seller') {
    $this->head()->setTitle('创业商家员工');
 } elseif ($this->parent['role'] == 'resale') {
    $this->head()->setTitle('创业四星分销商员工');
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
                <p>
                    <span class="n-dealer-span1">本月发展的一级会员总数：</span>
                    <span class="n-dealer-span2"><?=(int)$this->bonus['last1']['num']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">本月激活的一级会员总数：</span>
                    <span class="n-dealer-span2"><?=(int)$this->bonus['last1']['vip']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">历史发展的一级会员总数：</span>

                    <span class="n-dealer-span2"><?=(int)$this->bonus['history1']['num']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">历史激活的一级会员总数：</span>

                    <span class="n-dealer-span2"><?=(int)$this->bonus['history1']['vip']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">本月发展的二级会员总数：</span>

                    <span class="n-dealer-span2"><?=(int)$this->bonus['last2']['num']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">本月激活的二级会员总数：</span>

                    <span class="n-dealer-span2"><?=(int)$this->bonus['last2']['vip']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">历史发展的二级会员总数：</span>

                    <span class="n-dealer-span2"><?=(int)$this->bonus['history2']['num']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">历史激活的二级会员总数：</span>

                    <span class="n-dealer-span2"><?=(int)$this->bonus['history2']['vip']?></span>
                    <span class="n-dealer-span3">个</span>
                </p>
                <p>
                    <span class="n-dealer-span1">我的一级会员本月消费积分币：</span>

                    <span class="n-dealer-span2"><?=(float)$this->bonus['coin1']['credit_coin']['total']?></span>
                    <span class="n-dealer-span3">币</span>
                </p>
                <p>
                    <span class="n-dealer-span1">我的二级会员本月消费积分币：</span>

                    <span class="n-dealer-span2"><?=(float)$this->bonus['coin2']['credit_coin']['total']?></span>
                    <span class="n-dealer-span3">币</span>
                </p>
                <?php if ($this->parent['role'] == 'agent' || $this->parent['role'] == 'resale') { ?>
                    <p>
                        <span class="n-dealer-span1">我的商家的一级会员本月消费积分币：</span>

                        <span class="n-dealer-span2"><?=(float)$this->bonus['coin3']['credit_coin']['total']?></span>
                        <span class="n-dealer-span3">币</span>
                    </p>
                    <p>
                        <span class="n-dealer-span1">我的商家的二级会员本月消费积分币：</span>

                        <span class="n-dealer-span2"><?=(float)$this->bonus['coin4']['credit_coin']['total']?></span>
                        <span class="n-dealer-span3">币</span>
                    </p>
                    <!--<p style="height:auto;line-height:20px;">
                        <a href="<?/*=$this->url('/index/shoplist')*/?>" style="display:block;">
                            <span class="n-dealer-span1">发展的商家本月使用免费积分(点击查看)：</span>
                            <span class="n-dealer-span2"><?/*=(float)$this->bonus['coin5']['credit']['total']*/?></span>
                            <span class="n-dealer-span3">分</span>
                        </a>
                    </p>-->
                <?php } ?>
                <p>
                    <span>我的本月收益</span>
                    <span><?=$this->bonus['amount']?></span>
                    <span>元</span>
                </p>

            </div>
        </div>
    </div>
</div>