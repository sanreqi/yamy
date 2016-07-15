<?php
use app\models\Platform;
use app\models\Account;
use app\models\Detail;
use yii\widgets\LinkPager;
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>充值提现</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>账户详情&nbsp;&nbsp;&nbsp;<?php echo $platformName; ?>&nbsp;&nbsp;&nbsp;<?php echo $account['mobile']; ?></h3>
        <h3>充值：<?php echo $recharge; ?>元&nbsp;&nbsp;&nbsp;提现：<?php echo $withdraw; ?>元&nbsp;&nbsp;&nbsp;返现：<?php echo $cashback; ?>元</h3>
        <h3>余额：<?php echo $balance; ?>元&nbsp;&nbsp;&nbsp;收益：<?php echo $profit; ?>元</h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">明细id</th>
                <th align="left">平台名称</th>
                <th align="left">账户名称</th>
                <th align="left">交易类型</th>
                <th align="left">金额</th>
                <th align="left">手续费</th>
                <th align="left">返现</th>
                <th align="left">时间</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo $model['id']; ?></td>
                        <td align="left"><?php echo Platform::getNameById($model['platform_id']); ?></td>
                        <td align="left"><?php echo Account::getMobileById($model['account_id']); ?></td>
                        <td align="left"><?php echo Detail::getTypeByKey($model['type']); ?></td>
                        <td align="left"><?php echo $model['amount'] ?></td>
                        <td align="left"><?php echo $model['charge'] ?></td>
                        <td align="left"><?php echo $model['cashback'] ?></td>
                        <td align="left"><?php echo date('Y-m-d', $model['time']); ?></td>           
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

