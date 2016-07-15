<?php
use yii\helpers\Url;
use app\models\Platform;
use app\models\Account;
use app\models\Detail;
use app\models\Cashback;
use yii\widgets\LinkPager;
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>返现</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="" class="actionBtn add">新增返现</a>返现</h3>
        <h3>返现总额：<?php echo $cashback; ?>元</h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">返现id</th>
                <th align="left">明细id</th>
                <th align="left">平台名称</th>
                <th align="left">返现金额</th>
                <th align="left">返现人(qq昵称居多)</th>
                <th align="left">途径</th>
                <th align="left">状态</th>
                <th align="left">操作时间</th>
                <th align="center">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo $model['id']; ?></td>
                        <td align="left"><?php echo $model['detail_id']; ?></td>
                        <td align="left"><?php echo $model['platform']; ?></td>
                        <td align="left"><?php echo $model['amount']; ?></td>
                        <td align="left"><?php echo $model['casher']; ?></td>
                        <td align="left"><?php echo Cashback::getTypeByKey($model['type']); ?></td>
                        <td align="left"><?php echo Cashback::getStatusByKey($model['status']); ?></td>
                        <td align="left"><?php echo date('Y-m-d', $model['time']); ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/cashback/update','id' => $model['id']]) ?>">编辑</a> | <a href="<?php echo Url::toRoute(['/cashback/delete','id' => $model['id']]) ?>">删除</a></td>
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
