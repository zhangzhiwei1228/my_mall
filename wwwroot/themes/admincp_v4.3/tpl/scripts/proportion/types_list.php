<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-10
 * Time: 下午4:51
 */
?>
<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '比例设置';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
    <div class="sui-page-header">
        <h1> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
        <form method="get" action="<?=$this->url('action=typesearch')?>" class="sui-searchbox form-inline">
            <input type="hidden" name="search" value="1" />
            <input type="hidden" name="page" value="1" />
            <div class="form-group">
                <input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入关键词查询" />
                <?php if ($this->_request->q) { ?>
                    <a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
        <div class="sui-toolbar">
            <ul class="pagination pagination-sm pull-right">
                <?=$this->paginator($this->datalist)?>
            </ul>
            <button type="submit" name="act" value="typesdelete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');">
                <i class="fa fa-trash-o"></i> 删除</button>
            <a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>">
                <i class="fa fa-plus-circle"></i> 添加比例
            </a>
            <a class="btn btn-default btn-sm" href="<?=$this->url('action=addcoltypes&ref='.$this->_request->url)?>">
                <i class="fa fa-plus-circle"></i> 添加分类
            </a>

            <a class="btn btn-default btn-sm" href="<?=$this->url('action=list&ref='.$this->_request->url)?>">
                比例列表
            </a>
        </div>
        <table width="100%" class="table table-striped" data-plugin="chk-group">
            <thead>
            <tr>
                <th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
                <th>名称</th>
                <th class="hidden-xs">英文名</th>
                <th  class="hidden-xs">所属分类</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!count($this->datalist)) { ?>
                <tr align="center">
                    <td colspan="6"><div class="notfound">找不到相关信息</div></td>
                </tr>
            <?php } else { foreach ($this->datalist as $row) { ?>
                <tr>
                    <td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
                    <td><a href="<?=$this->url('action=typesedit&id=' . $row['id'].'&ref='.$this->_request->url)?>">
                            <?=$this->highlight($row['name'], $this->_request->q)?><?php echo $row['price']>0 ? '('.$row['price']*100 .'%)' : '' ?>
                        </a>
                    </td>
                    <td class="hidden-xs"><?=$this->highlight($row['english'], $this->_request->q)?></td>

                    <td class="hidden-xs"><?=$row['type'] <2 ? '货币性':($row['type'] < 3 ? '分类性' :'收益性'); ?></td>
                    <td><a href="<?=$this->url('action=typesedit&id=' . $row['id'].'&ref='.$this->_request->url)?>">编辑</a> <a href="<?=$this->url('action=typesdelete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
                </tr>
            <?php } } ?>
            </tbody>
        </table>
        <div class="sui-toolbar">
            <script type="text/javascript">
                var toolbar = $('.sui-toolbar').clone();
                document.write(toolbar.html());
            </script>
        </div>
    </form>
</div>
