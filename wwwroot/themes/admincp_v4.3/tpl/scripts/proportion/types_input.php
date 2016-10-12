<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-10
 * Time: 下午4:32
 */
?>
<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '分类';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
    <div class="sui-page-header">
        <h1><?=$this->head()->getTitle()?></h1>
    </div>
    <form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">

        <div class="form-group">
            <label class="control-label col-sm-2"> 名称: </label>
            <div class="col-sm-4"> <textarea name="name" class="form-control" rows="2"><?=$this->data['name']?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2"> 英文名: </label>
            <div class="col-sm-4"> <textarea name="english" class="form-control" rows="2"><?=$this->data['english']?></textarea>
                <p class="help-block">如果不是货币性，可以不用填写</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2"> 价值: </label>
            <div class="col-sm-4"> <textarea name="price" class="form-control" rows="2"><?=$this->data['price']?></textarea>
                <p class="help-block">此处为有价格的设置</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">行业分类:</label>
            <div class="col-sm-4">
                <select name="type" class="form-control">
                    <option value="1" <?php echo $this->data['type']==1 ? 'selected': '' ?>>货币性</option>
                    <option value="2" <?php echo $this->data['type']==2 ? 'selected': '' ?>>分类性</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
                <button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
            </div>
        </div>
    </form>
</div>
