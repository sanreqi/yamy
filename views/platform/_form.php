<form id="account-form" action="" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <td width="90" align="right">平台名称<em class="yamy-required">*</em></td>
            <td>
                <input type="text" name="Platform[name]" value="<?php echo $model['name']; ?>" size="40" class="inpMain" />
                <span class="error-alert"><?php echo isset($errors['name'][0]) ? $errors['name'][0] : '' ?></span>
            </td>
        </tr>
        <tr>
            <td align="right">所在地</td>
            <td>
                <input type="text" name="Platform[location]" value="<?php echo $model['location']; ?>" size="40" class="inpMain" />
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