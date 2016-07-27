$(function(){
	//Imgshow-Scroll
	var $imgshowUl = $(".ipro-box ul");
	var $imgshowLi = $(".ipro-box li");
	var $prev = $(".ipro-box .prev");
	var $next = $(".ipro-box .next");
	$imgshowLi.eq(1).stop().animate({
		left: 24,
		top: 0,
		width: 199,
		height: 127
	},500).addClass("on");
	var $imgshowLiOn = $(".ipro-box li.on");
	$imgshowLiOn.prev().stop().animate({left:0},500);
	$imgshowLiOn.next().stop().animate({left:117},500);
	$(".ipro-box .tips").stop().fadeIn(600).html($imgshowLiOn.find("img").attr("data-title"));
	$prev.hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	});
	$prev.click(function(){
		var $imgshowLiOn = $(".ipro-box li.on");
		$(".ipro-box .tips").stop().animate({opacity: 0}, 500, function(){
			$(".ipro-box .tips").stop().animate({opacity: 1}, 500).html($imgshowLiOn.prev().find("img").attr("data-title"));
		})
		$imgshowLiOn.prev().stop().animate({left:-117},500,function(){
			$imgshowLiOn.prev().appendTo($imgshowUl).css("left",117);
		});
		$imgshowLiOn.stop().animate({
			left: 0,
			top: 21,
			width: 133,
			height: 85
		},500).removeClass("on");
		$imgshowLiOn.next().stop().animate({
			left: 24,
			top: 0,
			width: 199,
			height: 127
		},500).addClass("on");
		var $liOn = $(".ipro-box li.on");
		$liOn.next().stop().animate({left:117},500);
	});
	$next.hover(function(){
		$(this).addClass("on");
	},function(){
		$(this).removeClass("on");
	});
	$next.click(function(){
		var $imgshowLiOn = $(".ipro-box li.on");
		$imgshowUl.find('li:last').prependTo($imgshowUl).css("left",-133).stop().animate({left:0},500);		
		$(".ipro-box .tips").stop().animate({opacity: 0}, 500, function(){
			$(".ipro-box .tips").stop().animate({opacity: 1}, 500).html($imgshowLiOn.prev().find("img").attr("data-title"));
		})
		$imgshowLiOn.next().addClass("z30").stop().animate({left:117},500,function(){
			$imgshowLiOn.next().removeClass("z30")
		});
		$imgshowLiOn.stop().animate({
			left: 117,
			top: 21,
			width: 133,
			height: 85
		},500).removeClass("on");
		$imgshowLiOn.prev().stop().animate({
			left: 24,
			top: 0,
			width: 199,
			height: 127
		},500).addClass("on");
	});
});