<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-5
 * Time: 下午4:23
 */
?>
<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('会员统计报表');
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
                <input type="text" name="sd" value="<?=$this->_request->sd?>" placeholder="起始时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
                <span class="input-group-addon">~</span>
                <input type="text" name="ed" value="<?=$this->_request->ed?>" placeholder="结束时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
            </div>
        </div>

        <div class="form-group">
            <label class=" control-label">邀请人手机号:</label>
            <input type="text" class="form-control" name="mobile" placeholder="邀请人手机号" maxlength="11">
        </div>

        <div class="form-group">
            <label class="control-label">区县:</label>
            <div class="JS_Dmenu" style="float: right">
                <input type="hidden" name="area_text" value="<?=$this->data['area_text']?>" />
                <input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
            </div>
        </div>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">会员数据统计</div>
            <div class="panel-body">
                <table width="100%" class="table table-striped" data-plugin="chk-group">
                    <thead>
                    <tr>
                        <th>会员总数</th>
                        <th>激活数</th>
                        <th>订单总数</th>
                        <th>购买率</th>
                        <th>会员购物总额</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $this->udata['u_total']?></td>
                            <td><?php echo $this->udata['u_vip']?></td>
                            <td><?php echo $this->order['o_total']?></td>
                            <td><?php echo round($this->order['o_total_quantity']/$this->goods['g_total'],5)*100?>%</td>
                            <td><?php echo empty($this->order['o_total_amount']) ? 0 : $this->order['o_total_amount']?>元</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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