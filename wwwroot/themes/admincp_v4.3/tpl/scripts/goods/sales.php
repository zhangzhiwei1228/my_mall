<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-8
 * Time: 下午5:31
 */
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('销售明细');
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
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="sd" id="sd" value="<?=$this->_request->sd?>" placeholder="月份" class="form-control input-sm" style="width: 110px">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">状态:</label>
            <select name="status" class="form-control">
                <option value="5" <?=$this->_request->status == '5' ? 'selected' : ''?>>全部</option>
                <option value="1" <?=$this->_request->status == '1' ? 'selected' : ''?>>待付款</option>
                <option value="2" <?=$this->_request->status == '2' ? 'selected' : ''?>>待发货</option>
                <option value="3" <?=$this->_request->status == '3' ? 'selected' : ''?>>待签收</option>
                <option value="4" <?=$this->_request->status == '4' ? 'selected' : ''?>>已完成</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">销售统计</div>
            <div class="panel-body">
                <table width="100%" class="table table-striped" data-plugin="chk-group">
                    <thead>
                    <tr>
                        <th>日期</th>
                        <th>销售总量</th>
                        <th>销售总额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($this->order_goods as $val) {?>
                        <tr>
                            <td><?php echo $val['otime']?></td>
                            <td><?php echo $val['o_total_quantity']?></td>
                            <td><?php echo $val['o_total_amount']?></td>
                        </tr>
                    <?php }?>

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
            autoclose: true,startView:3,minView:3,todayHighlight:true
        });
    })
</script>
