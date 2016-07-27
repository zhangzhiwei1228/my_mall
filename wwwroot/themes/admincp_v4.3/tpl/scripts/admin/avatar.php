<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('头像设置');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<div id="altContent">
		<h2>插件加载失败，您的浏览器获取没有安装Adobe Flash Player</h2>
		<p><a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash player</a></p>
	</div>
</div>
<script src="/assets/avatar/js/swfobject.js"></script>
<script>
	var flashvars = {
		js_handler:"jsfun",
		swfID:"avatarEdit",
		picSize:"5242880",
		sourceAvatar:"<?=$this->data['avatar'] ? $this->data['avatar'] : '/assets/avatar/avatar.png'?>",
		avatarLabel:"头像预览，请注意清晰度",
		sourceLabel:"保存你的原图吧",
		avatarAPI:"/assets/avatar/upload.php?token=<?=$this->data->getToken()?>",
		avatarSize:"128,128|80,80",
		avatarSizeLabel:"大尺寸|小尺寸"
	};
	var params = {
		menu: "false",
		scale: "noScale",
		allowFullscreen: "true",
		allowScriptAccess: "always",
		bgcolor: "",
		wmode: "transparent" // can cause issues with FP settings & webcam
	};
	var attributes = {
		id:"AvatarUpload"
	};
	swfobject.embedSWF(
		"/assets/avatar/avatarUpload.swf", 
		"altContent", "700", "500", "10.0.0", 
		"/assets/avatar/expressInstall.swf", 
		flashvars, params, attributes);
		
	function jsfun(obj)
	{
		//if(obj.type == "sourcePicSuccess") alert("原图上传成功");
		//if(obj.type == "sourcePicError") alert("原图上传失败");
		if(obj.type == "avatarSuccess") { 
			alert("头像上传成功");
			window.location.href = '<?=$this->url('&')?>';
		}
		if(obj.type == "avatarError") alert("头像上传失败");
		//if(obj.type == "init") alert("flash初始化完成");
		if(obj.type == "cancel") alert("取消编辑头像");
		if(obj.type == "FileSelectCancel") alert("取消选取本机图片");	
		
		console.log(obj);
	}
</script>
