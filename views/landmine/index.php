<?php

$this->params['extraLoadJS'] = [

];
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>充值提现</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>地雷金额：<?php echo \app\models\Landmine::getTotal(); ?></h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th align="left">平台名称</th>
                <th align="left">账户名称</th>
                <th align="left">地雷金额</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left"><?php echo $model['platform']; ?></td>
                        <td align="left"><?php echo $model['account'];  ?></td>
                        <td align="left"><?php echo $model['amount']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>

<script>
</script>
