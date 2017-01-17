<?php
use app\models\BorrowWay;
use yii\helpers\Url;
use app\models\BorrowPayment;

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
        <h3><a href="/borrow/payment-create?id=<?php echo $detail['id']; ?>" class="actionBtn add">新增还款</a>还款列表&nbsp&nbsp&nbsp&nbsp<?php echo $detail->way->platform.'-'.$detail->way->account; ?>
        <h3>产生利息：<?php echo BorrowPayment::getInterestByDetailId($detail->id); ?></h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th align="left">还款金额</th>
                <th align="left">还款时间</th>
                <th align="left">当前剩余金额</th>
                <th align="left">本次产生利息</th>
                <th width="80" align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td><?php echo $model['amount'];  ?></td>
                        <td><?php echo date('Y-m-d', $model['time']); ?></td>
                        <td><?php echo $model['remain'];  ?></td>
                        <td><?php echo $model['interest']; ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/borrow/payment-update', 'id' => $model['id'], 'detail_id' => $detail->id]) ?>">编辑</a> | <a class="delete_item" href="<?php echo Url::toRoute(['/borrow/payment-delete', 'id' => $model['id']]) ?>">删除</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>