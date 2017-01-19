jQuery.fn.LoadImage=function(scaling,obj){
	var n = obj.length;
	return this.each(function(){
		var $t = $(this);
		var src = $(this).attr("data-img");
		var img = new Image();
		img.src = src;
		//处理ff下会自动读取缓存图片
		if(img.complete || img.width){
			n -- ;
			if(n == 0){
				banner();
			}
		    return;
		}
		$(img).load(function(){
			$t.attr("style","background:url("+src+") no-repeat center");
			n -- ;
			if(n == 0){
				banner();
			}
		});
	});
}
function banner(){
	//初始化banner样式
	$(".banner").css("background","none");
	var listN = $(".banner > ul > li").length;
	$(".banner > ul > li").eq(0).fadeIn(1000);

	for(i=0;i<listN;i++){
		$(".banner .btn").append('<span class="span'+i+' fl"></span>');
	}
	$(".banner .btn").css("margin-left",-$(".btn").width()/2);
	$(".banner .btn span").eq(0).addClass("on");

	//执行效果
	var sw = 1;
	$(".banner .btn span").mouseover(function(){
		sw = $(".banner .btn span").index(this);
		myShow(sw);
	});
	function myShow(i){
		$(".banner .btn span").eq(i).addClass("on").siblings("span").removeClass("on");
		$(".banner > ul > li").eq(i).stop(true,true).fadeIn(1000).siblings().stop(true,true).fadeOut(1000);
	}
	//滑入停止动画，滑出开始动画
	$(".banner").hover(function(){
		if(myTime){
		   clearInterval(myTime);
		}
	},function(){
		clearInterval(myTime);
		myTime = setInterval(function(){
		  myShow(sw);
		  sw++;
		  if(sw==listN){sw=0;}
		} , 5000);
	});
	//自动开始
	var myTime = setInterval(function(){
	   myShow(sw);
	   sw++;
	   if(sw==listN){sw=0;}
	} , 5000);
}

$(function(){
	$(".banner > ul > li").LoadImage(true,$(".banner > ul > li"));
})