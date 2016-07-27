视频文件链接: <input type="text" id="fileUrl" value="http://112.90.246.23/plvod01.videocc.net/sl8da4jjbx/2/sl8da4jjbx692db9d494a9986ab8c7a2.mp4" size="100">

writetoken: <input type="text" id="writetoken" value="Y07Q4yopIVXN83n-MPoIlirBKmrMPJu0" size="40">

<input type="button" id="button" value="点击抓取">

 标题 <input id="title" size="70">

 首图 <input id="first_image" size="70"><img src="" id="first_image_img" style="display:none">

 播放链接 <input id="swf_link" size="70">
<script>
$("#button").click(function(){
  $("#button").val("正在抓取，请稍后");
  $.get("http://v.polyv.net/uc/services/rest", {
    method:"uploadUrlFile",
    fileName:"remotefile title",
    writetoken:$("#writetoken").val(),
    fileUrl:$("#fileUrl").val()
      },function(video){
      $("#button").val("抓取完成");
          $("#title").val(video.data[0].title);
          $("#first_image").val(video.data[0].first_image);
          $("#first_image_img").attr("src",video.data[0].first_image);
          $("#first_image_img").css({display:"block"});
          $("#swf_link").val(video.data[0].swf_link);
      
  });
});
</script>