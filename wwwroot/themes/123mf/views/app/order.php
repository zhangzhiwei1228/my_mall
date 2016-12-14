<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
    <div class="n-order">
    	<div class="top">
    		<div class="p">
    			<strong>承运来源：</strong><?php echo $this->kuaidi100[$this->delivery['com']]?>
    		</div>
    		<div class="p">
    			<strong>运单编号：</strong><?php echo $this->delivery['code']?>
    		</div>
    	</div>

    	<div class="n-line"></div>
	    <div class="n-order-list">
	    	<ul class="express">
				<li><p class="loading">正在查询,请稍后...</p></li>
	    	</ul>
	    </div>
    </div>
    
    <?php include_once VIEWS.'app/inc/footer.php'; ?>
<?php
	echo static_file('app/js/main.js');
?>
<script>
$(function(){

})
</script>
	<script>
		$(function(){
			var com = '<?php echo $this->delivery['com']?>';
			var nu = '<?php echo $this->delivery['code']?>';
			$.getJSON('/callback/kuaidi100/',{com:com,nu:nu}).done(function(data){
				$('#com').html(data.com);
				$('#nu').html(data.nu);
				var html = '';
				for(var i in data.data){
					var datas=data.data[i];
					if(i == 0) {
						html+=  '<li>';
						html+=  '<div class="em"></div>';
						html+=  '<div class="p">'+ datas.context +' </div>';
						html+=  '<div class="t"> '+ datas.time +' </div>';
						html+=  '</li>';
					} else {
						html+=  '<li>';
						html+=  '<div class="em2"></div>';
						html+=  '<div class="p">'+ datas.context +' </div>';
						html+=  '<div class="t"> '+ datas.time +' </div>';
						html+=  '</li>';
					}

				}
				$('.express').html(html);

			});

		})

	</script>
</body>
</html>