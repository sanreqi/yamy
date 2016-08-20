<?php
use app\models\Detail;
use app\models\Platform;
use app\models\Account;

$this->params['extraLoadJS'] = [
    '/resources/js/datetimepicker.js',
    '/resources/js/yamy.js',
];
$this->params['extraLoadCss'] = [
    '/resources/css/datetimepicker.css'
];
?>
<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增明细</strong></div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增明细</h3>
        <form id="platform-form" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">平台名称</td>
                    <td>
                        <?php echo Platform::getNameById($account['platform_id']); ?>
                    </td>
                </tr>
                <tr>
                    <td align="right">账号名</td>
                    <td>
                        <?php echo $account['mobile']; ?>
                    </td>
                </tr>

                <tr>
                    <td align="right">交易类型</td>
                    <td>
                        <?php echo Detail::getTypeByKey($type); ?>
                    </td>
                </tr>
                <tr>
                    <td width="90" align="right">金额</td>
                    <td>
                        <input type="text" name="Detail[amount]" value="" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">手续费</td>
                    <td>
                        <input type="text" name="Detail[charge]" value="" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">操作时间</td>
                    <td>
                        <input type="text" name="Detail[time]" value=""
                               size="40" class="inpMain datepicker"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">余额</td>
                    <td>
                        <input type="text" name="Detail[balance]" value="<?php echo $account['balance']; ?>"
                               size="40" class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">最近一笔回款时间</td>
                    <td>
                        <input type="text" name="Detail[returned_time]" value="<?php echo !empty($account['returned_time']) ? date('Y-m-d', $account['returned_time']) : ''; ?>" size="40"
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