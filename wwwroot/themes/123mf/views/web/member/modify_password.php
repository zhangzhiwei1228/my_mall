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
    			<div class="tit">我的订单</div>
    			<div class="modify-password">
    				<table>
                        <tr>
                            <td>当前密码：</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>新密码：</td>
                            <td><input type="text"></td>
                        </tr>
                        <tr>
                            <td>再次确认新密码：</td>
                            <td><input type="text"></td>
                        </tr>  
                        <tr>
                            <td></td>
                            <td><input value="保 存" class="submita" type="submit"></td>
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
    
	})
</script>
</html>