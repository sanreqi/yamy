
<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增账号</strong></div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增个人信息</h3>
        <form id="bank-account-form" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td align="right">用户名</td>
                    <td>
                        <input type="text" name="BankAccount[username]" value="<?php echo $model['username']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">真实姓名</td>
                    <td>
                        <input type="text" name="BankAccount[truename]" value="<?php echo $model['truename']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">卡号后四位</td>
                    <td>
                        <input type="text" name="BankAccount[card]" value="<?php echo $model['card']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">银行</td>
                    <td>
                        <input type="text" name="BankAccount[bank]" value="<?php echo $model['bank']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="recharge_tr">
                    <td align="right">预留号码</td>
                    <td>
                        <input type="text" name="BankAccount[reserved_phone]" value="<?php echo $model['reserved_phone']; ?>" size="40"
                               class="inpMain"/>
                    </td>
                </tr>
                <tr class="recharge_tr">
                    <td align="right">余额</td>
                    <td>
                        <input type="text" name="BankAccount[balance]" value="<?php echo $model['balance']; ?>" size="40"
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
