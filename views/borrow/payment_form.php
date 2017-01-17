<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\BorrowWay;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js',
    '/resources/js/borrow/borrow.js',
    '/resources/js/datetimepicker.js',
];
$this->params['extraLoadCss'] = [
    '/resources/css/yamy.css',
    '/resources/css/datetimepicker.css',
];
?>

<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增账号</strong></div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增还款</h3>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="yamy-error-summary">
                <?php foreach ($errors as $k => $v): ?>
                    <?php echo $v[0] . "<br/>"; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form id="borrow-payment-form" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">平台名称</td>
                    <td><?php echo $detail->way->platform; ?></td>
                </tr>
                <tr>
                    <td align="right">平台账号</td>
                    <td><?php echo $detail->way->account; ?></td>
                </tr>
                <tr>
                    <td align="right">原本剩余金额</td>
                    <td><?php echo $detail->remain; ?></td>
                </tr>
                <tr>
                    <td align="right">还款金额</td>
                    <td>
                        <input type="text" name="BorrowPayment[amount]" value="<?php echo $model['amount']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">还款时间</td>
                    <td>
                        <input type="text" name="BorrowPayment[time]" value="<?php echo !empty($model['time']) ? date('Y-m-d', $model['time']) : '';  ?>" size="40"
                               class="inpMain datepicker" />
                    </td>
                </tr>
                <tr>
                    <td align="right">还完剩余金额</td>
                    <td>
                        <input type="text" name="BorrowPayment[remain]" value="<?php echo $model['remain'] ? $model['remain'] : 0; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>    <tr>
                    <td align="right">产生利息</td>
                    <td>
                        <input type="text" name="BorrowPayment[interest]" value="<?php echo $model['interest']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input name="submit" class="btn" type="submit" value="提交"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    $("document").ready(function() {
        yamy.borrow.init();
    });
</script>


