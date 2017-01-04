<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\BorrowWay;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
$this->params['extraLoadCss'] = [
    '/resources/css/yamy.css',
];
?>

<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增账号</strong></div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增个人信息</h3>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="yamy-error-summary">
                <?php foreach ($errors as $k => $v): ?>
                    <?php echo $v[0] . "<br/>"; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form id="borrow-way-form" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">途径名称</td>
                    <td>
                        <input type="text" name="BorrowWay[platform]" value="<?php echo $model->platform; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">账号</td>
                    <td>
                        <input type="text" name="BorrowWay[account]" value="<?php echo $model->account; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">还款类型</td>
                    <td>
                        <select style="width: 270px;" name="BorrowWay[type]" value="<?php echo $model->type; ?>">
                            <?php foreach (BorrowWay::getTypeList() as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">预期年化</td>
                    <td>
                        <input type="text" name="BorrowWay[rate]" value="<?php echo $model->rate; ?>" size="40"
                               class="inpMain"/> %
                    </td>
                </tr>
                <tr>
                    <td align="right">应还金额</td>
                    <td>
                        <input type="text" name="BorrowWay[remain]" value="<?php echo $model->remain; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">备注</td>
                    <td>
                        <textarea style="width: 270px; height: 100px;"
                            name="BorrowWay[note]"><?php echo $model->note; ?></textarea>
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



