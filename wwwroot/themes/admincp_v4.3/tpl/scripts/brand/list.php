<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-8
 * Time: 下午5:36
 */
?>
<div class="sui-box">
    <form method="post" action="<?=$this->url('action=batch')?>" data-plugin="chk-group" class="sui-datalist">
        <div class="sui-toolbar">
            <ul class="pagination pagination-sm pull-right">
                <?=$this->paginator($this->brands)?>
            </ul>
            <button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
            <a class="btn btn-default btn-sm" href="<?=$this->url('action=add&category_id='.$this->category_id.'&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 添加</a>
            <a class="btn btn-default btn-sm" href="<?=$this->url('action=edittitle&category_id='.$this->category_id.'&ref='.$this->_request->url)?>">  修改标题</a>
        </div>

        <table width="100%" class="table table-striped">
            <thead>
            <tr>
                <th width="20" class="text-right"><input type="checkbox" role="chk-all" /></th>
                <th>所属标题</th>
                <th class="text-center">图片</th>
                <th>名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!count($this->brands)) { ?>
                <tr align="center">
                    <td colspan="9"><div class="notfound">找不到相关信息</div></td>
                </tr>
            <?php } else { foreach ($this->brands as $row) { ?>
                <tr>
                    <td class="text-center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
                    <td>
                        <span>
                            <?php echo $row['title'];?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="<?=$this->url('action=edit&id='.$row['id'].'&ref='.$this->_request->url)?>">
                            <img src="<?=$this->img($row['thumb'], '160x160')?>" class="img-thumbnail"></a>
                    </td>
                    <td>
                        <span>
                            <?php echo $row['name'];?>
                        </span>
                    </td>

                    <td valign="top">
                        <a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">编辑</a>
                        <a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a><br />
                    </td>
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
