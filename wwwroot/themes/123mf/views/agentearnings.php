<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-10
 * Time: 上午11:01
 */
?>
<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
<?php //include_once VIEWS.'inc/header.php'; ?>
<div class="z-jifenheader bgcolor<">
    <div class="header-merchants">
        <div class="main"><a href="<?=$this->url('index')?>"><img src="<?php echo static_file('m/img/icon18.png'); ?> "></a>
                创业代理商管理员
        </div>
    </div>
</div>
<div class="admin-box bgwhite">
    <div class="merc-admin w90">
        <div class="pic fl">
            <!--				<img src="--><?php //echo static_file('m/img/pic17.png'); ?><!-- ">-->
            <img src="<?php echo $this->baseUrl('uploads/avatar/6.png'); ?> ">
        </div>
        <div class="intro fl">
            <p class="admin-p">
                创业代理商管理员
            </p>
            <p class="admin-name"><?=$this->user['nickname']?></p>
            <p class="login-info"><a href="<?=$this->url('passport/logout')?>">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a></p>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="staff-jifen bgwhite">
    <p class="w90">
        我代理地区商家本月使用免费积分：
        <span class="fr">
            <em><?=(float)$this->bonus['seller']['credit']['total']?></em>&nbsp;分
        </span>
    </p>
</div>
<div class="staff-jifen bgwhite">
    <p class="w90">
        我代理地区会员本月消费积分币：
        <span class="fr">
            <em><?=(float)$this->bonus['userarea']['credit_coin']['total']?></em>&nbsp;币
        </span>
    </p>
</div>
<div class="staff-jifen bgwhite">
    <p class="w90">
        我代理地区商家本月使用抵用券：
        <span class="fr">
            <em><?=(float)$this->bonus['seller_v']['vouchers']['total']?></em>&nbsp;币
        </span>
    </p>
</div>
<div class="staff-jifen bgwhite">
    <p class="w90">
        我代理地区商家本月收到抵用金：
        <span class="fr">
            <em><?=(float)$this->bonus['seller_w']['worth_gold']['total']?></em>&nbsp;币
        </span>
    </p>
</div>
<div class="staff-jifen bgwhite">
    <p class="w90">
        我代理地区会员本月商城消费使用抵用券：
        <span class="fr">
            <em><?=(float)$this->bonus['userarea_v']['vouchers']['total']?></em>&nbsp;币
        </span>
    </p>
</div>
<div class="month-income bgwhite">
    <p class="income01 w90">我的本月收益</p>
    <p class="income01 w90"><span><?=(float)$this->bonus['amount']?></span>&nbsp;元</p>
</div>
<?php include_once VIEWS.'inc/footer_fourstar.php'; ?>
<?php
echo static_file('web/js/main.js');
?>
</body>
</html>



