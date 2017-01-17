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
        <h3>借款列表</h3>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="yamy-error-summary">
                <?php foreach ($errors as $k => $v): ?>
                    <?php echo $v[0] . "<br/>"; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form id="borrow-detail-form" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">借款途径</td>
                    <td>
                        <?php if (!empty($wayOptions)): ?>
                            <select style="width: 270px;" name="BorrowDetail[way_id]" ?>">
                                <?php foreach ($wayOptions as $k => $v): ?>
                                    <?php if ($k == $model->way_id): ?>
                                        <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td align="right">借款总额</td>
                    <td>
                        <input type="text" name="BorrowDetail[amount]" value="<?php echo $model->amount; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>

                <tr>
                    <td align="right">剩余金额</td>
                    <td>
                        <input type="text" name="BorrowDetail[remain]" value="<?php echo $model->remain; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">借款时间</td>
                    <td>
                        <input type="text" name="BorrowDetail[borrow_time]" value="<?php echo !empty($model->borrow_time) ? date("Y-m-d", $model->borrow_time) : ''; ?>" size="40"
                               class="inpMain datepicker" />
                    </td>
                </tr>
                <tr>
                    <td align="right">应还时间</td>
                    <td>
                        <input type="text" name="BorrowDetail[payment_time]" value="<?php echo !empty($model->payment_time) ? date("Y-m-d", $model->payment_time) : ''; ?>" size="40"
                               class="inpMain datepicker"/>
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


