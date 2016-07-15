<?php
use app\models\Detail;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
?>

<form id="platform-form" action="" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
        <tr>
            <td align="right">平台名称</td>
            <td>
                <select name="Detail[platform_id]" style="width: 232px;" class="select-platform">
                    <option value="0">请选择平台</option>
                    <?php if (!empty($platOptions)): ?>    
                        <?php foreach ($platOptions as $k => $v): ?>
                            <?php if ($k == $model['platform_id']): ?>
                                <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>        
                </select>
            </td>
        </tr> 
        <tr>
            <td align="right">账号名</td>
            <td>
                <select name="Detail[account_id]" style="width: 232px;" class="select-account">    
                    <?php if (!empty($accountOptions)): ?>    
                        <?php foreach ($accountOptions as $k => $v): ?>
                            <?php if ($k == $model['platform_id']): ?>
                                <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td align="right">交易类型</td>
            <td>
                <select name="Detail[type]" style="width: 232px;">                    
                    <?php foreach (Detail::getTypeList() as $k => $v): ?>
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
            <td width="90" align="right">金额</td>
            <td>
                <input type="text" name="Detail[amount]" value="<?php echo $model['amount']; ?>" size="40" class="inpMain" />
            </td>
        </tr>
        <tr>
            <td align="right">手续费</td>
            <td>
                <input type="text" name="Detail[charge]" value="<?php echo $model['charge']; ?>" size="40" class="inpMain" />
            </td>
        </tr>     
        <tr>
            <td align="right">操作时间</td>
            <td>
                <input type="text" name="Detail[time]" value="<?php echo date('Y-m-d', $model['time']); ?>" size="40" class="inpMain" />
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

