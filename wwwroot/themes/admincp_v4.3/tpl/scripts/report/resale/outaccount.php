<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午7:46
 */
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('平台总出帐');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>
<style type="text/css">
    .sui-page-header .sui-searchbox {
        left: 160px;
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

        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">平台总出帐</div>
            <div class="panel-body">
                <table width="100%" class="table table-striped" data-plugin="chk-group">
                    <thead>
                    <tr>
                        <th>会员积分币充值</th>
                        <th>会员充值帮帮币</th>
                        <th>推广激活</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $this->coins['total']?>元</td>
                        <td><?php echo round($this->credits['total']/3,2)?></td>
                        <td><?php echo $this->money['total']*-1?></td>
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
