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
    <div id="urHere">p2p平台<b>></b><strong>p2p平台列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="/borrow/way-create" class="actionBtn add">新增借款途径</a>借款途径列表</h3>
        <ul class="tab">
            <li><a href="/borrow/detail-index?status=1" class="selected">未还清</a></li>
            <li><a href="/borrow/detail-index?status=0">已还清</a></li>
        </ul>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">平台名称</th>
                <th align="left">账号名称</th>
                <th align="left">剩余金额</th>
                <th align="left">借款时间</th>
                <th align="left">应还款时间</th>
                <th align="left">备注</th>
                <th width="80" align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo 1; ?></td>
                        <td><?php echo 2; ?></td>
                        <td><?php echo 3; ?></td>
                        <td><?php echo 4; ?></td>
                        <td><?php echo 5; ?></td>
                        <td><?php echo 6; ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/borrow/way-update', 'id' => $model['id']]) ?>">编辑</a> | <a class="delete_item" href="<?php echo Url::toRoute(['/borrow/way-delete', 'id' => $model['id']]) ?>">删除</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>

