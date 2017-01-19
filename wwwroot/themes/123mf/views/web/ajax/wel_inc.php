<div class="wel-js w1190">
	<div class="hd">
		<ul>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
	<div class="bd">
		<ul>
			<li><a href=""><img src="<?php echo static_file('web/img/img-15.jpg'); ?> " alt=""></a></li>
			<li><a href=""><img src="<?php echo static_file('web/img/img-15.jpg'); ?> " alt=""></a></li>
			<li><a href=""><img src="<?php echo static_file('web/img/img-15.jpg'); ?> " alt=""></a></li>
		</ul>
	</div>
</div>
<script>
    $(function(){
        jQuery(".wel-js").slide({mainCell:".bd ul",effect:"leftLoop",interTime:"5000",autoPlay:true});
    })
    
</script>