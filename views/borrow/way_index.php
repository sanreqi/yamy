<?php
use app\models\BorrowWay;
use yii\helpers\Url;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
$this->params['extraLoadCss'] = [
    '/resources/css/yamy.css',
];
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>p2p平台列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="/borrow/way-create" class="actionBtn add">新增借款途径</a>借款途径列表</h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">平台名称</th>
                <th align="left">账号名称</th>
                <th align="left">还款方式</th>
                <th align="left">预期年化</th>
                <th align="left">剩余金额</th>
                <th align="left">备注</th>
                <th width="80" align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo $model['platform']; ?></td>
                        <td><?php echo $model['account']; ?></td>
                        <td><?php echo BorrowWay::getTypeByKey($model['type']); ?></td>
                        <td><?php echo $model['rate']; ?></td>
                        <td><?php echo $model['remain']; ?></td>
                        <td><?php echo $model['note']; ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/borrow/way-update', 'id' => $model['id']]) ?>">编辑</a> | <a class="delete_item" href="<?php echo Url::toRoute(['/borrow/way-delete', 'id' => $model['id']]) ?>">删除</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>