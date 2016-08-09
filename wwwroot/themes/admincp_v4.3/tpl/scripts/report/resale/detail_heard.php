<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午5:31
 */
?>

<div class="panel-body">
    <p>
        <span>分销身份:</span>
        <span><?php echo M('User')->getUserRole($this->user)?></span>
    </p>
    <p>
        <span>分销商姓名:</span>
        <span><?php echo $this->user['username']?></span>
    </p>
    <p>
        <span>所属区域:</span>
        <span>
            <div class="JS_Dmenu">
                <input type="text" name="area_text" value="" readonly/>

                <input type="hidden" name="area_id" value="<?=$this->user['area_id']?>" />
            </div>

        </span>
    </p>
    <!--<p>
        <span>当月收入:</span>
        <span>10000.00</span>
    </p>
    <p>
        <span>上层分销商姓名:</span>
        <span>李四</span>
    </p>
    <p>
        <span>一级分销商:</span>
        <span>1000人</span>
    </p>
    <p>
        <span>二级分销商:</span>
        <span>500人</span>
    </p>-->
</div>
<script type="text/javascript">
    seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
        dmenu.init('.JS_Dmenu', {
            rootId: 1,
            limit: 3,
            script: '/misc.php?act=area',
            htmlTpl: '<select class="form-control" style="width:auto;display: none; margin-right:6px"></select>',
            firstText: '请选择所在地',
            defaultText: '请选择',
            selected: $('input[name=area_id]').val(),
            callback: function(el, data) {
                var location = $('.JS_Dmenu>select>option:selected').text();
                $('input[name=area_id]').val(data.id > 0 ? data.id : 0);
                $('input[name=area_text]').val(location);
            }
        });
    });
</script>
