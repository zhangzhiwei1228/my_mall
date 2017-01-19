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
    			<div class="tit">获取免费积分记录</div>
                
    			<div class="big-box">
    				<div class="bot">
    					<div class="laydate">
	    					<span>充值日期</span>
	    					<input id="start" class="inline laydate-icon" type="text"> —
	    					<input id="end" class="inline laydate-icon" type="text">
	    					<input value="查询" class="sub" type="submit">
    					</div>
    					<table width="100%">
    						<tr>
    							<th>充值时间</th>
    							<th>获得途径</th>
    							<th>获得免费积分</th>
                                <th></th>
    						</tr>
    						<tr>
    							<td>2015-8-20</td>
    							<td>邀请会员激活</td>
    							<td>20</td>
                                <td></td>
    						</tr>
    						<tr>
                                <td>2015-8-20</td>
                                <td>邀请会员激活</td>
                                <td>20</td>
                                <td><a class="d_recording" href="">评价</a></td>
                            </tr>
    						<tr>
                                <td>2015-8-20</td>
                                <td>邀请会员激活</td>
                                <td>20</td>
                                <td><a class="d_recording" href="">评价</a></td>
                            </tr>
    					</table>
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
		$(".big-box .bot table ")

		var start = {
		    elem: '#start',
		    format: 'YYYY/MM/DD hh:mm:ss',
		    // min: laydate.now(), //设定最小日期为当前日期
		    max: '2099-06-16 23:59:59', //最大日期
		    istime: true,
		    istoday: false,
		    choose: function(datas){
		         end.min = datas; //开始日选好后，重置结束日的最小日期
		         end.start = datas //将结束日的初始值设定为开始日
		    }
		};
		var end = {
		    elem: '#end',
		    format: 'YYYY/MM/DD hh:mm:ss',
		    min: laydate.now(),
		    max: '2099-06-16 23:59:59',
		    istime: true,
		    istoday: false,
		    choose: function(datas){
		        start.max = datas; //结束日选好后，重置开始日的最大日期
		    }
		};
		laydate(start);
		laydate(end);
	})
</script>
</html>