<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="bg">    
        <div class="recharge-game-money w1190">
            <div class="agents-work">
              <div class="agents-top">
                <div class="agents-work-l">
                  <?php include_once VIEWS.'inc/prople_head.php'; ?>
                </div>
                <div class="agents-work-r">
                  <p class="p1">我的本月收益</p>
                  <p class="p2">4000元</p>
                </div>
              </div>
              <h2 class="bt-tit">创业财富再升级如下,<font class="bt-tit-small">(周星星，目前会员级别为：二星分销商)</font></h2>
              <div class="member">
                  <div class="member-box">
                      <div class="bot-sp">
                          <ul>
                              <li>
                                  <span>升级三星分销商</span>
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <input value="点击升级" type="submit">
                              </li>
                              <li>
                                  <span>升级四星分销商</span>
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <img src="<?php echo static_file('web/img/img-30.png'); ?> " alt="">
                                  <input value="点击升级" type="submit">
                              </li>
                          </ul>
                      </div>
                  </div>
              </div>
              <ul class="agents-ul">
                <li>
                  <span class="spl">本月发展的一级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">本月激活的一级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">历史发展的一级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">历史激活的一级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">本月发展的二级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">本月激活的二级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">历史发展的二级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">历史激活的二级会员总数</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">我的一级会员本月消费积分币</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">我的二级会员本月消费积分币</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">我的商家的一级会员本月消费积分币</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">我的商家的二级会员本月消费积分币</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">我发展的商家本月使用免费积分</span>
                  <span class="spr">1000  个</span>
                </li>
              </ul>
              <p class="sp-p1"><font style="color:#333;">一级会员：</font>我直接邀请注册的会员</p>
              <p class="sp-p2"><font style="color:#333;">二级会员：</font>我的一级会员邀请注册的会员</p>
            </div>
        </div>
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
    $(function(){
      $(".game-give-head").addClass('cur');

      $(".position").html("创业商家员工")
    })
</script>
</html>