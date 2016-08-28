<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>p2p平台列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="/bank/create" class="actionBtn add">新增银行卡</a>个人信息列表</h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">平台id</th>
                <th align="left">用户名</th>
                <th align="left">姓名</th>
                <th align="left">卡号</th>
                <th align="left">银行</th>
                <th align="left">预留号码</th>
                <th align="left">余额</th>
                <th width="80" align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo $model['id']; ?></td>
                        <td><?php echo $model['username']; ?></td>
                        <td><?php echo $model['truename']; ?></td>
                        <td><?php echo $model['card']; ?></td>
                        <td><?php echo $model['bank']; ?></td>
                        <td><?php echo $model['reserved_phone']; ?></td>
                        <td><?php echo $model['balance']; ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/bank/update', 'id' => $model['id']]) ?>">编辑</a> | <a href="<?php echo Url::toRoute(['/bank/delete', 'id' => $model['id']]) ?>">删除</a></td>
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

