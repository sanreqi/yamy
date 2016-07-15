<form id="platform-form" action="" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <td align="right">平台名称<em class="yamy-required">*</em></td>
            <td>
                <select name="Account[platform_id]" style="width: 232px;">
                    <?php foreach ($options as $k => $v): ?>
                        <?php if ($k == $model['platform_id']): ?>
                            <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="90" align="right">用户名<em class="yamy-required">*</em></td>
            <td>
                <input type="text" name="Account[username]" value="<?php echo $model['username']; ?>" size="40" class="inpMain" />
                <span class="error-alert"><?php echo isset($errors['name'][0]) ? $errors['name'][0] : '' ?></span>
            </td>
        </tr>
        <tr>
            <td align="right">手机号</td>
            <td>
                <input type="text" name="Account[mobile]" value="<?php echo $model['mobile']; ?>" size="40" class="inpMain" />
            </td>
        </tr>   
        <tr>
            <td align="right">余额</td>
            <td>
                <input type="text" name="Account[balance]" value="<?php echo $model['balance']; ?>" size="40" class="inpMain" />
            </td>
        </tr>   
        <tr>
            <td align="right">最近回款日期</td>
            <td>
                <input type="text" name="Account[returned]" value="<?php echo date('Y-m-d', $model['returned_time']); ?>" size="40" class="inpMain" />
            </td>
        </tr>   
        <tr>
            <td></td>
            <td>  
                <input name="submit" class="btn" type="submit" value="提交" />
            </td>
        </tr>
    </table>
</form>