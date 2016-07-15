<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>p2p平台列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="/platform/create" class="actionBtn add">新增平台</a>p2p平台列表</h3>
        <div class="filter">
            <form action="" method="get">
<!--                <select name="cat_id">       
                </select>-->
                <input name="keyword" type="text" class="inpMain" value="" size="20" />
                <input name="submit" class="btnGray" type="submit" value="搜索" />
            </form>
        </div>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">平台id</th>
                <th align="left">平台名称</th>
                <th align="left">平台所在地</th>
                <th width="80" align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo $model['id']; ?></td>
                        <td><?php echo $model['name']; ?></td>
                        <td><?php echo $model['location']; ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/platform/update', 'id' => $model['id']]) ?>">编辑</a> | <a href="<?php echo Url::toRoute(['/platform/delete', 'id' => $model['id']]) ?>">删除</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        
        <div class="spage">
            第<?php echo $pages->page+1; ?>页
            <?php echo LinkPager::widget([
                'pagination' => $pages,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页'
            ]); ?>
        </div>
    </div>
</div>   
    
