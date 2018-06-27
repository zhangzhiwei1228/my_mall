<!DOCTYPE html>
<head>
<?php include_once VIEWS.'web/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'web/inc/header_login.php'; ?>
    <div class="bg">
    	<div class="reg">
    		<h2 class="h2">会员注册</h2>
    		<div class="fl">
    			<table>
    				<tr>
    					<td><span class="tit">手机号码：</span></td>
    					<td>
    						<input placeholder="请输入手机号" class="reg-input-a" type="text">
    					</td>
    				</tr>
    				<tr>
    					<td><span class="tit">验证码：</span></td>
    					<td>
    						<input placeholder="请输入手机号" class="reg-input-b" type="text">
    						<input value="发送验证码" class="reg-input-c" type="submit">
    					</td>
    				</tr>
    				<tr>
    					<td><span class="tit">密码：</span></td>
    					<td>
    						<input placeholder="请输入6-12位密码" class="reg-input-a" type="password">
    					</td>
    				</tr>
    				<tr>
    					<td><span class="tit">确认密码：</span></td>
    					<td>
    						<input placeholder="请再次输入密码" class="reg-input-a" type="password">
    					</td>
    				</tr>
    				<tr>
    					<td><span class="tit">邀请码：</span></td>
    					<td>
    						<input placeholder="请输入邀请人号码（填写获得20会员积分）" class="reg-input-a" type="text">
    					</td>
    				</tr>
    				<tr>
    					<td></td>
    					<td><input value="立即注册" class="reg-input-d" type="submit"></td>
    				</tr>
    			</table>
    		</div>
    		<div class="fr">
    			<p><font style="font-size:14px;">邀请码说明</font>(<font style="color:#b30000;">分2种情况</font>)：</p>
    			<p>A；无邀请码注册，会员激活后获<font style="color:#b30000;">10积分。</font></p>
    			<p>B；有邀请码注册，会员激活后获<font style="color:#b30000;">20积分</font>，同时邀请码推荐人也获20积分（邀请码就是你推荐人的手机号，你注册成功后用你的邀请码推荐朋友，注册成功激活后，你就可以得到<font style="color:#b30000;">20积分</font>，你朋友也得<font style="color:#b30000;">20积分</font>，以此类推）。</p>
    		</div>
    	</div>
    </div>
    <?php include_once VIEWS.'web/inc/footer_login.php'; ?>
</body>
</html>    