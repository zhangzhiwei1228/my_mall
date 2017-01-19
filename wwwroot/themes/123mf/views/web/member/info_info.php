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
    		<?php include_once VIEWS.'inc/list_side.php'; ?>
    		<div class="fr">
    			<div class="tit">订单详情</div>
    			<div class="list-info">
                    <span class="list-info-tit">基本信息</span>
                    <table class="tablea">
                        <tr>
                            <td>订单号:1432411231</td>
                            <td>订单状态:<font class="jq">已发货</font></td>
                            <td style="text-align:right;">购买时间:2016-3-4</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <input value="确认收货" class="inputa" type="submit">
                                <span class="spana">总金额：9000积分</span>
                            </td>
                        </tr>
                    </table>
                    <span class="list-info-tit">商品信息：</span>
                    <table class="tableb">
                        <tr>
                            <th>产品名称</th>
                            <th>订单状态</th>
                            <th>购买价格</th>
                            <th>购买日期</th>
                            <th>数量</th>
                            <th style="text-align:right;">合计金额</th>
                        </tr>
                        <tr>
                            <td>银饰</td>
                            <td>购买成功</td>
                            <td>2000积分</td>
                            <td>2015-1-1</td>
                            <td>1</td>
                            <td style="text-align:right;">3000积分</td>
                        </tr>
                        <tr>
                            <td>银饰</td>
                            <td>购买成功</td>
                            <td>2000积分</td>
                            <td>2015-1-1</td>
                            <td>1</td>
                            <td style="text-align:right;">3000积分</td>
                        </tr>
                    </table>
                    <span class="list-info-tit">收货人信息：</span>
                    <ul class="ula">
                         <li>许少波  158541564654</li>
                         <li>浙江  杭州</li>
                         <li>浙江省杭州市莫干山路登云路某某地某某点</li>
                    </ul>
                    <span class="list-info-tit">收货人信息：</span>
                    <div class="ems-img">
                        <img src="<?php echo static_file('web/img/img-47.jpg'); ?> " alt="">
                    </div>
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

		
	})
</script>
</html>