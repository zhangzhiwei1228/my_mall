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
    			<div class="my_order">
    				<div class="five-input">
                        <span>订单号</span>
                        <input type="text">
                        <span>收件人</span>
                        <input type="text">
                        <span>购买日期</span>
                        <input class="laydate-icon" id="start" type="text">
                        —
                        <input class="laydate-icon" id="end" type="text">
                        <input class="sp-input" value="查询" type="submit">            
                    </div>
    			</div>
                <div class="my-order-table">
                    <ul>
                        <li>全部订单</li>
                        <li>未付款</li>
                        <li>代发货</li>
                        <li>代发货</li>
                        <li>已收货</li>
                    </ul>
                </div>
                <div class="my-order-ajax-box">
                    <table>
                        <tr>
                            <th style="text-align: left;">产品名称</th>
                            <th>收件人</th>
                            <th>时间</th>
                            <th>金额</th>
                            <th>订单状态</th>
                            <th style="text-align: right;" width="93">操作</th>
                        </tr>
                        <tr>
                            <td colspan="6"><div class="my-tit">订单编号：56598765</div></td>
                        </tr>
                        <tr>   
                            <td class="hasline" style="text-align:left;" valign="center">
                                <div class="my-img-box">
                                    <div class="my-order-pro"><img src="<?php echo static_file('web/img/img-51.jpg'); ?> " alt=""></div>
                                </div>
                                <span class="my-spa">银饰头饰</span>
                            </td>
                            <td class="hasline" valign="center">
                                <span class="my-spaa">徐少波</span>
                            </td>
                            <td class="hasline" valign="center">
                                <span class="my-spaa">2015-5-30</span>
                            </td>
                            <td class="hasline" valign="center">
                                <span class="my-spaa">188免费积分</span>
                            </td>
                            <td class="hasline" valign="center">
                                <span class="my-spaa">已付款</span>
                            </td>
                            <td  class="hasline">
                                <input class="my-input" value="确认收货" type="submit">
                                <span class="my-spaaa">订单详情</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><div class="my-tit">订单编号：56598765</div></td>
                        </tr>
                        <tr>   
                            <td class="hasline" style="text-align:left;" valign="center">
                                <div class="my-img-box">
                                    <div class="my-order-pro"><img src="<?php echo static_file('web/img/img-51.jpg'); ?> " alt=""></div>
                                    <div class="my-order-pro"><img src="<?php echo static_file('web/img/img-51.jpg'); ?> " alt=""></div>
                                </div>
                                <span class="my-spa">银饰头饰</span>
                            </td>
                            <td valign="top" class="hasline">
                                <span class="my-spaa">徐少波</span>
                            </td>
                            <td valign="top" class="hasline">
                                <span class="my-spaa">2015-5-30</span>
                            </td>
                            <td valign="top" class="hasline">
                                <span class="my-spaa">188免费积分</span>
                            </td>
                            <td valign="top" class="hasline">
                                <span class="my-spaa">已付款</span>
                            </td>
                            <td  class="hasline">
                                <input class="my-input" value="确认收货" type="submit">
                                <span class="my-spaaa">订单详情</span>
                            </td>
                        </tr>
                    </table>
                    <div class="page-n">
                        <img src="<?php echo static_file('web/img/img-45.png'); ?> " alt="">
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
        var start = {
            elem: '#start',
            max: '2099-06-16', //最大日期
            istime: true,
            istoday: false,
            choose: function(datas){
                 end.min = datas; //开始日选好后，重置结束日的最小日期
                 end.start = datas //将结束日的初始值设定为开始日
            }
        };
        var end = {
            elem: '#end',
           
            min: laydate.now(),
            max: '2099-06-16',
            istime: true,
            istoday: false,
            choose: function(datas){
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };

        laydate(start);
        laydate(end);

		$(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');

        $(".my-order-table li").click(function(){
            $(this).addClass('cur').siblings('li').removeClass('cur');
        })


        $(".my-order-ajax-box tr").each(function(){
            if ($(this).find('.my-order-pro').length >= 2) {
                var num = $(this).find('.my-order-pro').length;
                var new_height = num * 87 - 18;
                $(this).find('.my-spa').css("line-height" ,new_height+"px");
                $(this).find('.my-spaa').css("line-height" ,new_height+"px");
            }else{
                $(this).find('.my-spa').css("line-height" ,"69px");
                $(this).find('.my-spaa').css("line-height" ,"69px");
            }
        })

        $(".my-order-table li").eq(0).addClass('cur');


        var url = <?php echo "'".site_url('ajax/order-ajax')."'"; ?> ;
        $(".my-order-ajax-box").load(url)


        $(".my-order-table li").click(function(){
                var Html = $.ajax({
                url   : '<?php echo site_url('ajax/order-ajax'); ?> ',
                async : false
                })
        $(".my-order-ajax-box").html(Html.responseText)

        })
        $(".sp-input").click(function(){
                var Html = $.ajax({
                url   : '<?php echo site_url('ajax/order-ajax'); ?> ',
                async : false
                })
        $(".my-order-ajax-box").html(Html.responseText)

        })

    
	})
</script>
</html>