<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午12:34
 */
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('分销人数统计报表');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>
<style type="text/css">
    .sui-page-header .sui-searchbox {
        left: 200px;
    }
</style>
<div class="sui-page-header">
    <h1> <?=$this->head()->getTitle()?></h1>
    <form method="get" class="sui-searchbox form-inline">
        <div class="form-group" >
            <div class="input-group">
                <input type="text" name="sd" value="<?=$this->_request->sd?>" placeholder="起始时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
                <span class="input-group-addon">~</span>
                <input type="text" name="ed" value="<?=$this->_request->ed?>" placeholder="结束时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
            </div>
        </div>
        <div class="form-group" style="margin-left: 5px">
            <label class="control-label">分销角色:</label>
            <select name="role" class="form-control">
                <option value="0" <?=$this->_request->role == '0' ? 'selected' : ''?>>全部</option>
                <option value="resale-1" <?=$this->_request->role == 'resale-1' ? 'selected' : ''?>>一星分销商</option>
                <option value="resale-2" <?=$this->_request->role == 'resale-2' ? 'selected' : ''?>>二星分销商</option>
                <option value="resale-3" <?=$this->_request->role == 'resale-3' ? 'selected' : ''?>>三星分销商</option>
                <option value="resale-4" <?=$this->_request->role == 'resale-4' ? 'selected' : ''?>>四星分销商管理员</option>
                <option value="staff-resale" <?=$this->_request->role == 'staff-resale' ? 'selected' : ''?>>四星分销员工</option>
                <option value="agent-0" <?=$this->_request->role == 'agent-0' ? 'selected' : ''?>>代理商管理员</option>
                <option value="staff-agent" <?=$this->_request->role == 'staff-agent' ? 'selected' : ''?>>代理商员工</option>
                <option value="seller-0" <?=$this->_request->role == 'seller-0' ? 'selected' : ''?>>商家管理员</option>
                <option value="staff-seller" <?=$this->_request->role == 'staff-seller' ? 'selected' : ''?>>商家员工</option>
            </select>
        </div>
        <div class="form-group" style="margin-left: 5px">
            <label class="control-label" style="padding-top: 8px">区县:</label>
            <div class="JS_Dmenu" style="float: right">
                <input type="hidden" name="area_text" value="<?=$this->_request->area_text?>" />
                <input type="hidden" name="area_id" value="<?=$this->_request->area_id?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">当月收入范围:</label>
            <div class="input-group">
                <input type="number" name="sincome" value="<?=$this->_request->sincome?>"  class="form-control input-sm" style="width: 110px">
                <span class="input-group-addon">~</span>
                <input type="number" name="eincome" value="<?=$this->_request->eincome?>"class="form-control input-sm" style="width: 110px">
            </div>
        </div>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">分销人数统计报表</div>
            <div class="panel-body">
                <label>总人数：<?php echo $this->users['total']?>个</label>
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