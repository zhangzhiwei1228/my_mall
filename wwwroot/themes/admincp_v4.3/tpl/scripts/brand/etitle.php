<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-9
 * Time: 上午9:55
 */
if(!defined('APP_KEY')) { exit('Access Denied'); }
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle('修改标题');

?>
<div class="sui-box">
    <div class="sui-page-header">
        <h1 class="pull-left"><?=$this->head()->getTitle()?></h1>
        <ul class="nav nav-pills">

        </ul>
    </div>
    <form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
        <div class="tab-content">
            <div id="base" class="tab-pane fade active in">
                <div class="form-group">
                    <label class="control-label col-sm-2"><span class="required">*</span>标题:</label>
                    <div class="col-sm-7">
                        <input type="text" name="title" id="title" value="<?=$this->data['title']?>" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-7">
                    <input type="hidden" name="category_id" value="<?php echo $this->category_id ? $this->category_id : $this->data['category_id']?>"/>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
                    <button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
                </div>
            </div>
        </div>
    </form>
</div>
