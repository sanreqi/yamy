<?php
use app\models\Cashback;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
?>

<form id="platform-form" action="" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <td width="90" align="right">明细id</td>
            <td>
               <?php echo $detailId; ?>
            </td>
        </tr>
        <tr>
            <td width="90" align="right">返现金额</td>
            <td>
                <input type="text" name="Cashback[amount]" value="<?php echo $model['amount']; ?>" size="40" class="inpMain" />
            </td>
        </tr>
        <tr>
            <td align="right">返现人</td>
            <td>
                <input type="text" name="Cashback[casher]" value="<?php echo $model['casher'];  ?>" size="40" class="inpMain" />
            </td>
        </tr>
        <tr>
            <td align="right">返现途径</td>
            <td>
                <select name="Cashback[type]" style="width: 232px;">                    
                    <?php foreach (Cashback::getTypeList() as $k => $v): ?>
                        <?php if ($k == $model['type']): ?>
                            <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>         
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">返现状态</td>
            <td>
                <select name="Cashback[status]" style="width: 232px;">                    
                    <?php foreach (Cashback::getStatusList() as $k => $v): ?>
                        <?php if ($k == $model['status']): ?>
                            <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>         
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">操作时间</td>
            <td>
                <input type="text" name="Cashback[time]" value="<?php echo date('Y-m-d', $model['time']); ?>" size="40" class="inpMain" />
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

