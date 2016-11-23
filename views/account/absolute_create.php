<?php
use app\models\Cashback;

$this->params['extraLoadJS'] = [
    '/resources/js/datetimepicker.js',
    '/resources/js/account/account.js',
    '/resources/select2/js/select2.min.js',
];
$this->params['extraLoadCss'] = [
    '/resources/css/yamy.css',
    '/resources/css/datetimepicker.css',
    '/resources/select2/css/select2.min.css'
];
?>

<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增账号</strong></div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增账号(新版)</h3>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="yamy-error-summary">
                <?php foreach ($errors as $k => $v): ?>
                    <?php echo $v[0] . "<br/>"; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="platform-form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $form->platformId; ?>" class="platformid_hidden" name="Account[platform_id]"/>
            <input type="hidden" value="<?php echo $form->registeredSimId; ?>" class="simid_hidden" name="Account[sim_id]"/>
            <input type="hidden" value="<?php echo $form->bankAccountIds ?>" class="bankcardids_hidden"
                   name="Account[bankaccount_ids]"/>
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">平台名称</td>
                    <td>
                        <select name="Account[platform_id]" class="platform_select2" style="width: 270px;">
                            <option value="">----请选择----</option>
                            <?php foreach ($platformOptions as $k => $v): ?>
                                <?php if ($form->platformId == $k): ?>
                                    <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">个人信息</td>
                    <td>
                        <select name="Account[bankcardIds]" style="width: 270px;" class="bankaccount_select2"
                                multiple="multiple">
                            <?php foreach ($bankAccountOptions as $k => $v): ?>
                                <?php if (in_array($k, $bankIdsArray)): ?>
                                    <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">余额</td>
                    <td>
                        <input type="text" name="Account[balance]" value="<?php echo $form->balance ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">天数</td>
                    <td>
                        <input type="text" name="Account[days]" value="<?php echo $form->days; ?>" size="40"
                               class="inpMain invest_days"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">最近回款日期</td>
                    <td>
                        <input type="text" name="Account[returned]" value="<?php echo $form->returnedTime; ?>" size="40"
                               class="inpMain datepicker latest_returned"/>
                    </td>
                </tr>
                <tr>
                    <td align="right"><input name="Account[isrecharge]" class="recharge_chx" type="checkbox"
                                             value="1" <?php echo $form->isRecharge ? 'checked="checked"' : ''; ?>/></td>
                    <td>
                        是否充值
                    </td>
                </tr>

                <tr class="recharge_tr" style="<?php echo $form->isRecharge ? '' : 'display:none'; ?>">
                    <td align="right">充值金额</td>
                    <td>
                        <input type="text" name="Account[recharge_amount]" value="<?php echo $form->rechargeAmount; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="recharge_tr" style="<?php echo $form->isRecharge ? '' : 'display:none'; ?>">
                    <td align="right">充值时间</td>
                    <td>
                        <input type="text" name="Account[recharge_time]" value="<?php echo $form->rechargeTime; ?>" size="40"
                               class="inpMain recharge_date datepicker" />
                    </td>
                </tr>
                <tr>
                    <td align="right"><input name="Account[iscashback]" class="cashback_chx" type="checkbox"
                                             value="1" <?php echo $form->isCashback ? 'checked="checked"' : ''; ?> /></td>
                    <td>
                        是否返现
                    </td>
                </tr>
                <tr class="cashback_tr" style="<?php echo $form->isCashback ? '' : 'display:none'; ?>">
                    <td align="right">返现人</td>
                    <td>
                        <input type="text" name="Account[cashback_casher]" value="<?php echo $form->casher; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="cashback_tr" style="<?php echo $form->isCashback ? '' : 'display:none'; ?>">
                    <td align="right">返现金额</td>
                    <td>
                        <input type="text" name="Account[cashback_amount]" value="<?php echo $form->cashbackAmount; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="cashback_tr" style="<?php echo $form->isCashback ? '' : 'display:none'; ?>">
                    <td align="right">返现途径</td>
                    <td>
                        <select name="Account[cashback_type]" style="width: 232px;">
                            <?php foreach (Cashback::getTypeList() as $k => $v): ?>
                                <?php if ($form->cashbackType == $k): ?>
                                    <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="cashback_tr" style="<?php echo $form->isCashback ? '' : 'display:none'; ?>">
                    <td align="right">返现状态</td>
                    <td>
                        <select name="Account[cashback_status]" style="width: 232px;">
                            <?php foreach (Cashback::getStatusList() as $k => $v): ?>
                                <?php if ($form->cashbackStatus == $k): ?>
                                    <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="cashback_tr" style="<?php echo $form->isCashback ? '' : 'display:none'; ?>">
                    <td align="right">返现时间</td>
                    <td>
                        <input type="text" name="Account[cashback_time]" value="<?php echo $form->cashbackTime ?>" size="40"
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
    $(document).ready(function () {
        yamy.account.create.init();
    });
</script>
