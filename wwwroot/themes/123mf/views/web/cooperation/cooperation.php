<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    	<div class="bg">
    		<div class="cooper">
    			<h2>申请合作</h2>
    			<table>
    				<tr>
    					<td>
    						<span class="coop-sp">公司或商家名称：</span>
    					</td>
    					<td><input class="input-a" type="text"></td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">经营类别：</span>
    					</td>
    					<td>
    						<select class="select-a" name="" id="">
    							<option value="">酒店</option>
    							<option value="">酒店</option>
    							<option value="">酒店</option>
    							<option value="">酒店</option>
    						</select>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">主营项目：</span>
    					</td>
    					<td><input class="input-a" type="text"></td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">场所面积：</span>
    					</td>
    					<td>
    					<input style="width:293px;" class="input-a" type="text">
    					<span class="coop-sp-a">平方米</span>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">经营地址：</span>
    					</td>
    					<td>
    						<select class="select-b" name="" id="">
    							<option value="">省</option>
    							<option value="">省</option>
    							<option value="">省</option>
    							<option value="">省</option>
    						</select>
    						<select class="select-b" name="" id="">
    							<option value="">市</option>
    							<option value="">市</option>
    							<option value="">市</option>
    							<option value="">市</option>
    						</select>
    						<select class="select-b" name="" id="">
    							<option value="">县</option>
    							<option value="">县</option>
    							<option value="">县</option>
    							<option value="">县</option>
    						</select>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp"></span>
    					</td>
    					<td><input value="详细地址" class="input-a" type="text"></td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">日营业额：</span>
    					</td>
    					<td>
    						<input type="text"><span class="coop-sp-a">元</span><span class="coop-sp-a">日客流量</span><input type="text"><span class="coop-sp-a">人</span>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">自评生意情况：</span>
    					</td>
    					<td>
    						<div class="table-js">
    							<span></span>很好
    						</div>
    						<div class="table-js">
    							<span></span>好
    						</div>
    						<div class="table-js">
    							<span></span>一般
    						</div>
    						<div class="table-js">
    							<span></span>差
    						</div>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">联系人：</span>
    					</td>
    					<td>
    						<input class="input-a" type="text">
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<span class="coop-sp">联系电话：</span>
    					</td>
    					<td>
    						<input class="input-a" type="text">
    					</td>
    				</tr>
    				<tr>
    					<td valign="top">
    						<span class="coop-sp">客户留言：</span>
    					</td>
    					<td>
    						<textarea value="你想要的需求" class="textarea-a" name="" id="" cols="30" rows="10"></textarea>
    					</td>
    				</tr>
    				<tr>
    					<td></td>
    					<td>
    						<input value="申请合作" class="input-c" type="submit">
    					</td>
    				</tr>
    			</table>
    		</div>
    	</div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
</body>
<script>
	$(function(){
		$(".table-js").click(function(){
			$(this).children('span').addClass('cur').parent(".table-js").siblings('.table-js').children('span').removeClass('cur');
		})
	})
</script>
</html>