<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=800, initial-scale=1.0">
<?=$this->head()
	->setTitle($this->setting['sitename'])
	->addMeta(null, 'text/html; charset=utf-8', 'Content-Type')
	->addLink(array('./css/all.min.css'), 'text/css')
	->addLink(array('/assets/js/jquery-1.10.2.min.js', '/assets/js/seajs/sea.js', './js/all.min.js'), 'text/javascript');
?>

</head>
<body>
<div class="sui-container <?=$_COOKIE['sidebarCollapsed']?'sui-sidebar-collapsed':''?>">
	<div class="sui-user-panel">
		<div class="col-sm-6">
			<a class="pull-left avatar" href="<?=$this->url('controller=admin&action=avatar')?>"><i>编辑</i>
				<img src="<?=$this->admin['avatar'] ? $this->admin['avatar'] : './img/no-avatar.png'?>" alt="" class="img-circle" width="44"> </a>
			<div>
				<h4><?=$this->admin['username']?> 
				<span style="font-size:12px">[<?=$this->admin->group['name']?>]</span> </h4>
				<a href="<?=$this->url('controller=admin&action=profile')?>"><i class="fa fa-cog"></i> 设置</a>　
				<a href="<?=$this->url('controller=message&action=inbox')?>">
					<i class="fa fa-envelope"></i> 消息
					<sup class="badge my-msg-num" style="padding:2px 5px; background:#C00">0</sup></a>
			</div>
		</div>
		<ul class="col-sm-6" style="padding:12px 20px; text-align:right">
			<a href="<?=$this->url('module=default')?>" target="_blank"><i class="fa fa-exchange"></i> 访问前台</a>　|　
			<a href="<?=$this->url('controller=cache&action=clear')?>"><i class="fa fa-flash"></i> 清空缓存</a>　|　
			<a href="<?=$this->url('controller=admin_menu')?>"><i class="fa fa-list"></i> 系统菜单</a>　|　
			<a href="<?=$this->url('controller=passport&action=logout')?>"><i class="fa fa-sign-out"></i> 退出系统</a>
		</ul>
	</div>
	<div class="sui-sidebar">
		<div class="header">
			<a href="#" class="logo"><img src="./img/logo.png" height="22" /></a>
			<a href="#" class="sui-sidebar-collapse"> <i class="fa fa-align-justify"></i> </a>
			<a href="#" class="sui-nav-collapse"> <i class="fa fa-align-justify"></i> </a>
		</div>
		<ul class="nav rollbar" data-plugin="accordion">
			<?php function showMenu($arr, $paths, $user) {
				$html = '';
				foreach ($arr as $row) {
					$allow = explode(',', $row['allow_group']);
					if (!$row['is_enabled']) continue;
					if ($row['allow_group'] && @!in_array($user['group_id'], $allow)) continue;
					$url = new Suco_Helper_Url('controller=admin_menu&action=redirect&id='.$row['id']);
					$opened = isset($paths[$row['id']]) ? 'active' : '';
					if ($row['childs_num']) {
						$html .= '<li class="'.$opened.' has-childs"><a role="toggle">';
						if ($row['icon']) { $html .= '<i class="fa fa-'.$row['icon'].' fa-fw"></i> '; }
						$html .= '<span class="menu-text">'.$row['name'].'</span><b class="caret"></b>';
					} else {
						$html .= '<li class="'.$opened.'"><a href="'.$url.'">';
						if ($row['icon']) { $html .= '<i class="fa fa-'.$row['icon'].' fa-fw"></i> '; }
						$html .= '<span class="menu-text">'.$row['name'].'</span>';
					}
					$html .= '</a>';
					if (isset($row['childnotes'])) {
						$html .= '<ul id="snv-'.$row['id'].'" class="nav" role="collapse">'.showMenu($row['childnotes'], $paths, $user).'</ul>';
					}
				}
				return $html;
			}
			echo showMenu($this->nav, $this->paths->toArray(), $this->admin); ?>
		</ul>
	</div>
	<div class="sui-body clearfix">
		<ul class="breadcrumb">
			<li><a href="<?=$this->url('controller=index')?>"><i class="fa fa-home"></i>&nbsp;&nbsp;控制台</a></li>
			<?php foreach($this->paths as $row) { ?>
			<li><a href="<?=$this->url('controller=admin_menu&action=redirect&id='.$row['id'])?>">
				<?=$row['name']?>
				</a></li>
			<?php } ?>
		</ul>
		<?=$this->layout()->content?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="default-dialog" tabindex="-1" role="dialog" aria-hidden="false"></div>
<!-- /.modal -->


<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/suco-api.js"></script>
<script>
$('a[data-toggle=tooltip]').mouseover(function() { 
	$(this).tooltip('show');
});
$('.sui-sidebar-collapse').on('click', function(){
	if ($('.sui-container').hasClass('sui-sidebar-collapsed')) {
		$('.logo').hide();
		$('.sui-sidebar').animate({'width':'200px'}, function(){
			$('.logo').fadeIn();
		});
		$('.sui-body').animate({'left':'200px'});
		$('.sui-user-panel').animate({'padding-left':'200px'});
		$('.sui-container').removeClass('sui-sidebar-collapsed');
		$.setCookie("sidebarCollapsed", 0);
	} else {
		$('.sui-sidebar').animate({'width':'55px'});
		$('.sui-body').animate({'left':'55px'});
		$('.sui-user-panel').animate({'padding-left':'55px'});
		$('.sui-container').addClass('sui-sidebar-collapsed');
		$.setCookie("sidebarCollapsed", 1);
	}
	return false;
})

$('.sui-nav-collapse').on('click', function(){
	$('.sui-sidebar>.nav').slideToggle();
})

// if ($.getCookie('sidebarCollapsed') == 1) {
// 	$('.sui-container').addClass('sui-sidebar-collapsed'); 
// }

$.loadUnreadMsg = function(){
	$.getJSON('<?=$this->url('controller=message&action=get_unread_list')?>', function(json){
		$('.my-msg-num').text(json.length);
	});	
}


$.loadUnreadMsg();
var intervalid = setInterval(function() {
	$.loadUnreadMsg();
}, 30000);
</script>
</body>
</html>