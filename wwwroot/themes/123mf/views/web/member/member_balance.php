<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
	<div class="bg">
		<div class="bread w1190">
    		<ul>
    			<li><a href="">首页</a></li>
    			<li>></li>
    			<li><a href="">个人中心</a></li>
    		</ul>
    	</div>
    	<div class="recharge-game-money w1190">
    		<?php include_once VIEWS.'inc/member_side.php'; ?>
    		<div class="fr">
    			<div class="balance-head">
                    <span class="spl">
                        <img src="<?php echo static_file('web/img/img-48.jpg'); ?> " alt="">
                    </span>   
                    <span class="spr">
                        <span class="top">
                            <p class="p1">555555</p>
                            <p class="p2">(55)
                            <img src="<?php echo static_file('web/img/img-36.png'); ?> " alt=""></p>
                            <p class="p3">欢迎您！</p>
                        </span>
                        <span class="bot">
                            <p class="p1">我的手机号：1586881334</p>
                        </span>
                    </span>
                </div> 
                <div class="balance-ul">
                    <ul>
                        <li>
                            <p class="top">我的免费积分（分）</p>
                            <p class="bot">10000</p>
                        </li>
                        <li>
                            <p class="top">我的积分币（币）</p>
                            <p class="bot">10000</p>
                        </li>
                        <li>
                            <p class="top">我的抵用劵（张）</p>
                            <p class="bot">10000</p>
                        </li>
                        <li>
                            <p class="top">我的快乐积分（分）</p>
                            <p class="bot">10000</p>
                        </li>
                    </ul>
                </div>
                <div class="balance-box">
                    <div class="top">
                        <span>充值积分币记录</span>
                        <a href="">更多>></a>
                    </div>
                    <table>
                        <tr>
                            <th>日期</th>
                            <th>充值金额</th>
                            <th>获得积分币</th>
                        </tr>
                        <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                    </table>
                </div>
                <div class="balance-box">
                    <div class="top">
                        <span>充值抵用劵记录</span>
                        <a href="">更多>></a>
                    </div>
                    <table>
                        <tr>
                            <th>日期</th>
                            <th>充值金额</th>
                            <th>获得积分币</th>
                        </tr>
                        <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                    </table>
                </div>
                <div class="balance-box">
                    <div class="top">
                        <span>获得免费积分记录</span>
                        <a href="">更多>></a>
                    </div>
                    <table>
                        <tr>
                            <th>日期</th>
                            <th>充值金额</th>
                            <th>获得积分币</th>
                        </tr>
                        <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                    </table>
                </div>
                <div class="balance-box">
                    <div class="top">
                        <span>获得快乐积分记录</span>
                        <a href="">更多>></a>
                    </div>
                    <table>
                        <tr>
                            <th>日期</th>
                            <th>充值金额</th>
                            <th>获得积分币</th>
                        </tr>
                        <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                         <tr>
                            <td>2015-12-12</td>
                            <td>500</td>
                            <td><span>1000</span></td>
                        </tr>
                    </table>
                </div>
    		</div>
    	</div>
	</div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
	$(function(){
		$(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');

        $(".balance-ul li").eq(3).css("background","none")
    
	})
</script>
</html>