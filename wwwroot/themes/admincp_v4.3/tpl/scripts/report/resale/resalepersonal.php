<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-8-9
 * Time: 下午3:53
 */
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('分销商个人查询');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>
<style type="text/css">
    .sui-page-header .sui-searchbox {
        left: 180px;
    }
</style>
<div class="sui-page-header">
    <h1> <?=$this->head()->getTitle()?></h1>
    <form method="post" class="sui-searchbox form-inline">
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
        <div class="form-group" >
            <label class="control-label">分销商用户名:</label>
            <input type="text" name="username" value="<?=$this->_request->username?>" placeholder="分销商用户名" class="form-control input-sm" style="width: 110px">
        </div>
        <input type="hidden" name="page" value="1" />
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                分销商个人查询
                <ul class="pagination pagination-sm pull-right" style="margin-top: -4px">
                    <?=$this->paginator($this->users)?>
                </ul>

            </div>
            <div class="panel-body">
                <table width="100%" class="table table-striped" data-plugin="chk-group">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>分销角色</th>
                        <th>分销商姓名</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($this->users as $user) {?>
                    <tr>
                        <td><?php echo $user['id']?></td>
                        <td><?php echo M('User')->getUserRole($user)?></td>
                        <td><?php echo $user['username']?></td>
                        <td><a href="<?php echo $this->url('admincp/report/resaledetail/?id='.$user['id'])?>">查看详情</a> </td>
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