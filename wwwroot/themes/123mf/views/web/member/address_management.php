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
    			<div class="tit">抵用卷转换</div>
                <form action="<?php echo site_url(''); ?> ">
                    <table class="address-table">
                        <tr>
                            <td><span class="spa">所在地区:</span></td>
                            <td>
                                <select name="" id="">
                                    <option value="">浙江省</option>
                                    <option value="">浙江省</option>
                                </select>
                                <select name="" id="">
                                    <option value="">杭州市</option>
                                    <option value="">杭州市</option>
                                </select>
                                <select name="" id="">
                                    <option value="">拱墅区</option>
                                    <option value="">拱墅区</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><span class="spa">街区地址:</span></td>
                            <td>
                                <textarea name="" id="" cols="30" rows="10"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="spa">邮政编码：</span></td>
                            <td>
                                <input class="inputa" type="text">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="spa">收货人姓名:</span></td>
                            <td>
                                <input class="inputa" type="text">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="spa">手机:</span></td>
                            <td>
                                <input class="inputa" type="text">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input class="radioa" type="radio">
                                <span class="spanb">设为默认地址</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input value="保存" class="submita" type="submit"></td>
                        </tr>
                    </table>
                </form>
                <div class="a16-ajax">
                    
                    <p>已经保存的有效收货地址</p>
                    <div class="form-end">
                        
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

        $(".remove").click(function(){
            $(this).parents("tr").remove();
        })

        $(".form-end table tr").click(function(){
            $(this).find('.bt').show();
            $(this).siblings('tr').find('.bt').hide();
        });

        $(".form-end table tr .bt").click(function(){
            $(this).html("默认地址");
            $(this).addClass('cur');
            $(this).parents("tr").siblings("tr").find('.bt').removeClass('cur');
            $(this).parents("tr").siblings("tr").find('.bt').html("设为默认");
        });

        $(".form-end table tr .bt").eq(0).show();

        var url = <?php echo "'".site_url('ajax/manage-ajax')."'"; ?> ;
        $(".form-end").load(url)

        $(".address-table .submit").click(function(){
            $(".scllar-te li").click(function(){
                    var Html = $.ajax({
                    url   : '<?php echo site_url('ajax/manage-ajax'); ?> ',
                    async : false
                    })
            $(".form-end").html(Html.responseText)

            })
        })

    
	})
</script>
</html>