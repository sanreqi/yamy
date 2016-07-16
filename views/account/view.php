<?php
use app\models\Platform;
use app\models\Account;
use app\models\Detail;
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>充值提现</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>账户详情&nbsp;&nbsp;&nbsp;<?php echo $platformName; ?>&nbsp;&nbsp;&nbsp;<?php echo $account['mobile']; ?></h3>
        <h3>充值：<?php echo $recharge; ?>元&nbsp;&nbsp;&nbsp;提现：<?php echo $withdraw; ?>元&nbsp;&nbsp;&nbsp;返现：<?php echo $cashback; ?>元
            <a href="<?php echo Url::toRoute(['/account/update','id'=>$id]); ?>" class="actionBtn" style="background-color: #0065b0; margin-left: 10px;">编辑账号</a>
        </h3>
        <h3>余额：<?php echo $balance; ?>元&nbsp;&nbsp;&nbsp;收益：<?php echo $profit; ?>元
            <a href="<?php echo Url::toRoute(['/detail/create','account_id'=>$id,'type'=>Detail::TYPE_WITHDRAW]); ?>" class="actionBtn" style="background-color: #0065b0; margin-left: 10px;">提现</a>
            <a href="<?php echo Url::toRoute(['/detail/create','account_id'=>$id,'type'=>Detail::TYPE_RECHARGE]); ?>" class="actionBtn">充值</a>
        </h3>
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
                <th align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left">
                            <?php if ($model['type'] == Detail::TYPE_RECHARGE): ?>
                                <a href="<?php echo Url::toRoute(['/cashback/create', 'detail_id' => $model['id']]); ?>"><?php echo $model['id']; ?></a>
                            <?php else: ?>
                                <?php echo $model['id']; ?>
                            <?php endif; ?>
                        </td>
<!--                        <td align="left">--><?php //echo $model['id']; ?><!--</td>-->
                        <td align="left"><?php echo Platform::getNameById($model['platform_id']); ?></td>
                        <td align="left"><?php echo Account::getMobileById($model['account_id']); ?></td>
                        <td align="left"><?php echo Detail::getTypeByKey($model['type']); ?></td>
                        <td align="left"><?php echo $model['amount'] ?></td>
                        <td align="left"><?php echo $model['charge'] ?></td>
                        <td align="left"><?php echo $model['cashback'] ?></td>
                        <td align="left"><?php echo date('Y-m-d', $model['time']); ?></td>
                        <td align="center"><a target="_blank" href="<?php echo Url::toRoute(['/detail/update','id' => $model['id']]) ?>">编辑</a> | <a href="<?php echo Url::toRoute(['/detail/delete','id' => $model['id']]) ?>">删除</a></td>
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

