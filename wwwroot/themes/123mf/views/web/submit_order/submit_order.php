<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="bg">
    	<div class="submit-order">
    		<div class="zt-img"><img src="<?php echo static_file('web/img/img-46.png'); ?> " alt=""></div>
    		<div class="submit-order-tit">
    			<span>地区选择</span>
    		</div>
    		<div class="choose-table">
    			<table>
    				<tr>
    					<td><span class="front-span">所在地区<font style="color:#fd6500;">*</font></span></td>
    					<td>
    						<span class="behind-span">
    							<select name="" id="">
    								<option value=""></option>
    								<option value=""></option>
    							</select>
    							<select name="" id="">
    								<option value=""></option>
    								<option value=""></option>
    							</select>
    							<select name="" id="">
    								<option value=""></option>
    								<option value=""></option>
    							</select>
    						</span>
    					</td>
    				</tr>
    				<tr>
    					<td valign="top"><span class="front-span">详细地址<font style="color:#fd6500;">*</font></span></td>
    					<td>
    						<span class="behind-span">
    							<textarea name="" id="" cols="30" rows="10"></textarea>
    						</span>
    					</td>
    				</tr>
    				<tr>
    					<td><span class="front-span">邮政编码</span></td>
    					<td>
    						<span class="behind-span">
    							<input class="frist-input" type="text">
    						</span>
    					</td>
    				</tr>
    				<tr>
    					<td><span class="front-span">收货人姓名<font style="color:#fd6500;">*</font></span></td>
    					<td>
    						<span class="behind-span">
    							<input class="frist-input" type="text">
    						</span>
    					</td>
    				</tr>
    				<tr>
    					<td><span class="front-span">手机号码</span></td>
    					<td>
    						<span class="behind-span">
    							<input class="frist-input" type="text">
    							<span class="between-span">或固定电话</span>
    							<input class="frist-input" type="text">
    						</span>
    					</td>
    				</tr>
    				<tr>
    					<td></td>
    					<td>
    						<span class="behind-span">
    							<input type="checkbox">
    							<span>设置为默认收货地址</span>
    						</span>
    					</td>
    				</tr>
    				<tr>
    					<td></td>
    					<td>
    						<span class="behind-span">
    							<input value="保存" class="sec-input" type="submit">
    						</span>
    					</td>
    				</tr>
    			</table>
    		</div>
    		<div class="submit-order-ajax">
	    		
	    	</div>
    		<div class="bg">
    			<div class="shopcar-box">
    			<table>
    				<tr>
    					<th width="442">商品</th>
    					<th width="180">单价</th>
    					<th width="200">数量</th>
    					<th width="180">小计</th>
    				</tr>
    				<tr>
    					<td>
    						<div class="ex-box1">
	    						<img style="margin-left:23px;" src="<?php echo static_file('web/img/img-44.jpg'); ?> " alt="">
	    						<span class="frist-sp">
	    							<p class="top">毛衣男韩版圆领套头修身纯色针织衫复古提花秋冬毛衫青年学生男装</p>
	    							<p class="bot">分类</p>
	    						</span>
    						</div>
    					</td>
    					<td>
	    					<div class="ex-box">
	    						<span class="second-sp">
	    							<p>免费积分：<font class="free">1000</font></p>
	    							<p>积分币：<font class="game">1000</font></p>
	    							<p>商城现金：<font class="money">10000</font></p>
	    						</span>
	    					</div>
    					</td>
    					<td>
	    					<div class="ex-box">
	    						<div class="shopcar-num">
									<span class="but-cut">-</span>
			    					<input id="num-inp" class="but-num" value="1" type="text">
			    					<span class="but-add">+</span>
								</div>
							</div>
    					</td>
    					<td>
	    					<div class="ex-box sub-second-sp">
	    						<span class="second-sp">
	    							<p>免费积分：<font class="j-free">1000</font></p>
	    							<p>积分币：<font class="j-game">1000</font></p>
	    							<p>商城现金：<font class="j-money">10000</font></p>
	    						</span>
	    					</div>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<div class="ex-box1">
	    						<img style="margin-left:23px;" src="<?php echo static_file('web/img/img-44.jpg'); ?> " alt="">
	    						<span class="frist-sp">
	    							<p class="top">毛衣男韩版圆领套头修身纯色针织衫复古提花秋冬毛衫青年学生男装</p>
	    							<p class="bot">分类</p>
	    						</span>
    						</div>
    					</td>
    					<td>
	    					<div class="ex-box">
	    						<span class="second-sp">
	    							<p>免费积分：<font class="free">1000</font></p>
	    							<p>积分币：<font class="game">1000</font></p>
	    							<p>商城现金：<font class="money">10000</font></p>
	    						</span>
	    					</div>
    					</td>
    					<td>
	    					<div class="ex-box">
	    						<div class="shopcar-num">
									<span class="but-cut">-</span>
			    					<input id="num-inp" class="but-num" value="1" type="text">
			    					<span class="but-add">+</span>
								</div>
							</div>
    					</td>
    					<td>
	    					<div class="ex-box sub-second-sp">
	    						<span class="second-sp ">
	    							<p>免费积分：<font class="j-free">1000</font></p>
	    							<p>积分币：<font class="j-game">1000</font></p>
	    							<p>商城现金：<font class="j-money">10000</font></p>
	    						</span>
	    					</div>
    					</td>
    				</tr>
    			</table>
    			<div class="end-box">
    				<div class="fl">
    					<div class="fll">
    						<div class="submit-order-div">补充说明；</div>
    						<div class="submit-order-text">
    							<textarea name="" id="" cols="30" rows="10"></textarea>
    						</div>
    					</div>
    					
    				</div>
    				
    				<div class="fr">
    					<div class="thr-box">
    						<ul>
    					 	    <li>￥100</li>
    							<li>免费积分：<font class="end-free">0</font></li>
    							<li>积分币：<font class="end-game">0</font></li>
    							<li>商城现金：<font class="end-money">0</font></li>
    						</ul>
    					</div>
    					<div class="zzz">
    						<p>快递费用:</p>
    						<p>商品总计:</p>
    					</div>
    				</div>
    			</div>	
    			<div class="two-boxa">
    				<input value="提交订单" type="submit">
    			</div>	
    			</div>
    		</div>
    	</div>
        
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
</body>
<script>
	$(function(){

		var url = <?php echo "'".site_url('ajax/submit_order_ajax')."'"; ?> ;
		$(".submit-order-ajax").load(url)


		$(".behind-span .sec-input").click(function(){
				var Html = $.ajax({
                url   : '<?php echo site_url('ajax/submit_order_ajax'); ?> ',
                async : false
      			})
		$(".submit-order-ajax").html(Html.responseText)

		})

		

		$(".shopcar-num .but-add").click(function(){
        	var n = parseInt($(this).parent().find(".but-num").val());
        	$(this).siblings(".but-num").val(n+1);
        	var thisn = $(this).siblings(".but-num").val();

        	
        	var free = $(this).parents("td").siblings("td").find('.free').html();
        	var game = $(this).parents("td").siblings("td").find('.game').html();
        	var money = $(this).parents("td").siblings("td").find('.money').html();

        	var g_free = parseInt(free * thisn);
        	$(this).parents("td").siblings("td").find('.j-free').html(g_free);

        	var g_game = parseInt(game * thisn);
        	$(this).parents("td").siblings("td").find('.j-game').html(g_game);

        	var g_money = parseInt(money * thisn);
        	$(this).parents("td").siblings("td").find('.j-money').html(g_money);

        	limit()        	
    	});
    	//减 
	    $(".shopcar-num .but-cut").click(function(){
	        var n = parseInt($(this).parent().find(".but-num").val());

	        if(n > 1){
	         	$(this).siblings('.but-num').val(n-1);
	        }else{
	         	$(this).siblings('.but-num').val(1);
	        } 

			var nn = n-1
    		
			if (nn >=1) {
				var free = $(this).parents("td").siblings("td").find('.free').html();
	    		var game = $(this).parents("td").siblings("td").find('.game').html();
	    		var money = $(this).parents("td").siblings("td").find('.money').html();

	    		var g_free = parseInt(free * nn);
	        	$(this).parents("td").siblings("td").find('.j-free').html(g_free);
	        	
	        	var g_game = parseInt(game * nn);
	        	$(this).parents("td").siblings("td").find('.j-game').html(g_game);

	        	var g_money = parseInt(money * nn);
	        	$(this).parents("td").siblings("td").find('.j-money').html(g_money);
			
				}
				else{

				} 

				limit()				
	    })
	    limit();
	 	function limit() {
	   		var _zgame=0 , _zmoney = 0 ,_zfree = 0;
    		$(".shopcar-box table .sub-second-sp").each(function(){
    		var n_this = $(this);
    
    			var end_free =  parseInt(n_this.find('.j-free').html()); //件数
    			_zfree += end_free;

    			
    			
    			
    			var end_game =  parseInt(n_this.find('.j-game').html()); //件数
    			_zgame += end_game
				

    			var end_money =  parseInt(n_this.find('.j-money').html()); //件数
    			_zmoney += end_money		
    		
    			})
    		$(".end-free").html(_zfree);
    		$(".end-game").html(_zgame);
    		$(".end-money").html(_zmoney);
		}
});
</script>
</html>