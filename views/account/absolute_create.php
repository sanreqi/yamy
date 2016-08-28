<?php
use app\models\Account;
use app\models\Cashback;

$this->params['extraLoadJS'] = [
    '/resources/js/datetimepicker.js',
    '/resources/js/yamy.js',
    '/resources/select2/js/select2.min.js',
];
$this->params['extraLoadCss'] = [
    '/resources/css/datetimepicker.css',
    '/resources/select2/css/select2.min.css',
];
?>

<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增账号</strong></div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增账号(新版)</h3>
        <form id="platform-form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" value="" class="info_data" name="Account[info_data]" />
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">平台名称</td>
                    <td>
                        <select name="Account[platform_id]" class="account_select2" style="width: 232px;">
                            <?php foreach ($platformOptions as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">个人信息</td>
                    <td>
                        <select name="Account[info]" style="width: 270px;" class="info_select2" multiple="multiple">
                            <?php foreach ($bankAccountOptions as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">余额</td>
                    <td>
                        <input type="text" name="Account[balance]" value="" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">最近回款日期</td>
                    <td>
                        <input type="text" name="Account[returned]" value="" size="40"
                               class="inpMain datepicker"/>
                    </td>
                </tr>
                <tr>
                    <td align="right"><input name="Account[isrecharge]" class="recharge_chx" type="checkbox" value="1" checked="checked"/></td>
                    <td>
                        是否充值
                    </td>
                </tr>
                <tr class="recharge_tr">
                    <td align="right">充值金额</td>
                    <td>
                        <input type="text" name="Account[recharge_amount]" value="" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="recharge_tr">
                    <td align="right">充值时间</td>
                    <td>
                        <input type="text" name="Account[recharge_time]" value="" size="40"
                               class="inpMain datepicker"/>
                    </td>
                </tr>
                <tr>
                    <td align="right"><input name="Account[iscachback]" class="cashback_chx" type="checkbox" value="1" checked="checked"/></td>
                    <td>
                        是否返现
                    </td>
                </tr>
                <tr class="cashback_tr">
                    <td align="right">返现人</td>
                    <td>
                        <input type="text" name="Account[cashback_casher]" value="" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="cashback_tr">
                    <td align="right">返现金额</td>
                    <td>
                        <input type="text" name="Account[cashback_amount]" value="" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="cashback_tr">
                    <td align="right">返现途径</td>
                    <td>
                        <select name="Account[cashback_type]" style="width: 232px;">
                            <?php foreach (Cashback::getTypeList() as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="cashback_tr">
                    <td align="right">返现状态</td>
                    <td>
                        <select name="Account[cashback_status]" style="width: 232px;">
                            <?php foreach (Cashback::getStatusList() as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="cashback_tr">
                    <td align="right">返现时间</td>
                    <td>
                        <input type="text" name="Account[cashback_time]" value="" size="40"
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
