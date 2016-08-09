<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午5:55
 */

if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('免费积分统计');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>
<style type="text/css">
    .sui-page-header .sui-searchbox {
        left: 140px;
    }
</style>
<div class="sui-page-header">
    <h1> <?=$this->head()->getTitle()?></h1>
    <form method="get" class="sui-searchbox form-inline">
        <div class="input-group">
            <input type="text" name="sd" value="<?=$this->_request->sd?>" placeholder="起始时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
            <span class="input-group-addon">~</span>
            <input type="text" name="ed" value="<?=$this->_request->ed?>" placeholder="结束时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
        </div>
        <div class="form-group" style="margin-left: 5px">
            <label class="control-label" style="padding-top: 8px">区县:</label>
            <div class="JS_Dmenu" style="float: right">
                <input type="hidden" name="area_text" value="<?=$this->_request->area_text?>" />
                <input type="hidden" name="area_id" value="<?=$this->_request->area_id?>" />
            </div>
        </div>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">免费积分统计</div>
            <div class="panel-body">
                <table width="100%" class="table table-striped" data-plugin="chk-group">
                    <thead>
                    <tr>
                        <th>会员充值免费积分</th>
                        <th>商家购买</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $this->member_bonus?></td>
                        <td><?php echo $this->seller_bonus?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    seajs.use('/assets/js/datetime/datetime.sea.js', function(){
        $('#sd').datetimepicker({
            format: 'yyyy-mm',
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            minView: 2,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 0
        });
    })
</script>
<script type="text/javascript">
    seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
        dmenu.init('.JS_Dmenu', {
            rootId: 1,
            limit: 3,
            script: '/misc.php?act=area',
            htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
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
