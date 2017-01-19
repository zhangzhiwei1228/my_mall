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
    			<div class="tit">抵用卷提现</div>
                <div class="acc">
                    <table>
                        <tr>
                            <td><span class="spana">账号：</span></td>
                            <td><input class="inputa" type="text"></td>
                        </tr>
                        <tr>
                            <td><span class="spana">绑定手机：</span></td>
                            <td>
                                <input class="inputa" type="text">
                                <span class="spanb">已验证</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="spana">姓名：</span></td>
                            <td><input class="inputa" type="text"></td>
                        </tr>
                        <tr>
                            <td><span class="spana">性别：</span></td>
                            <td>
                                <div class="radio">
                                    <input name="a1" type="radio">
                                    男
                                </div>
                                <div class="radio">
                                    <input name="a1" type="radio">
                                    女
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="spana">生日：</span></td>
                            <td><input class="inputa" type="datetime"></td>
                        </tr>
                        <tr>
                            <td><span class="spana">所在城市：</span></td>
                            <td>
                                <select name="" id="">
                                    <option value="">浙江省</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                                <select name="" id="">
                                    <option value="">杭州市</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </td>
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