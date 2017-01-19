//引用方法

 // $.MsgBox.Alert("标题", "内容！");   //alert提示框
 // $.MsgBox.Confirm("标题", "内容！",function(){ callback });  //confirm提示框


//弹出 确认/提示 信息框
$.MsgBox = {
    Alert: function (title, msg) {
      GenerateHtml("alert", title, msg);
      btnOk(); //alert只是弹出消息，因此没必要用到回调函数callback
      btnNo();
    },
    Confirm: function (title, msg, callback) {
      GenerateHtml("confirm", title, msg);
      btnOk(callback);
      btnNo();
    }
  }
//确定按钮事件
function btnOk(callback) {
    $("#mb_btn_ok").click(function () {
      $(".mb-box,.overlay").remove();
      if (typeof (callback) == 'function') {
        callback();
      }
    });
  }
 
//取消按钮事件
function btnNo() {
    $("#mb_btn_no,#mb_close").click(function () {
      $(".mb-box,.overlay").remove();
    });
}
//生成Html
var GenerateHtml = function (type, title, msg) {
    var _html = "";
    _html += '<div class="mb-box"><div class="hd"><a href="javascript:;" id="mb_close" class="close fr">x</a>' + title + '</div><div class="bd">';
    _html += '<p class="msg">' + msg + '</p><p class="btn">';

    if (type == "alert") {
    	_html += '<a href="javascript:;"  id="mb_btn_ok" class="btn ok">确认</a>';
    }
    if (type == "confirm") {
    	_html += '<a href="javascript:;"  id="mb_btn_ok" class="btn ok">确认</a>';
    	_html += ' <a href="javascript:;" id="mb_btn_no" class="btn canc">取消</a>';
    }   
    _html += '</p></div></div>';
    _html += '<div class="overlay"></div>';
    
    $("body").append(_html); //_html添加到body
}



/**弹出pop-up**/
// .overlay{
// 	position: fixed;
// 	width: 100%;
// 	height: 100%;
// 	background: #000;
// 	opacity: 0.8;
// 	filter:alpha(opacity=80);
// 	left: 0;
// 	top: 0;
// 	z-index: 9;
// }
// .mb-box{
// 	position: fixed;
// 	left: 50%;
// 	top: 50%;
// 	width: 540px;
// 	height: 283px;
// 	margin: -141px 0 0 -270px;
	
// 	background: #fff;
// 	border-radius: 8px;
// 	overflow: hidden;
// 	z-index: 10;
// }
// .mb-box .hd{
// 	height: 36px;
// 	border-bottom: 1px solid #e0e0e0;
// 	padding: 22px 22px 0 26px;
// 	background: #f3f3f3;
// 	font-size: 18px;
// 	color: #333;
// }
// .mb-box .close{
// 	display: inline-block;
// 	width: 18px;
// 	height: 18px;
//  line-height:18px;
//  border-radius:50%;
//  border: 1px solid #eee;
//	color:#666;
//	font-weight:bold;
//  text-align:center;
// }
// .mb-box .bd{
// 	padding-top: 35px;
// 	text-align: center;
// }
// .mb-box .msg{
// 	font-size: 28px;
// 	line-height: 40px;
// 	color: #555;
// 	font-weight: normal;
// }
// .mb-box .bd .btn{
// 	display: inline-block;
// 	width: 92px;
// 	line-height: 36px;
// 	text-align: center;
// 	color: #fff;
// 	font-size: 14px;
// }
// .mb-box .bd .btn.ok{
// 	background: #d91100;
// }
// .mb-box .bd .btn.canc{
// 	background: #777;
// }
// .mb-box .bd .btn:hover{
// 	opacity: 0.8;
// }