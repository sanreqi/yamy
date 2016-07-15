<?php

use app\models\Detail;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
?>

<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>新增明细</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>新增平台</h3>
        <form id="platform-form" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
                <tr>
                    <td width="90" align="right">金额</td>
                    <td>
                        <input type="text" name="Detail[amount]" value="<?php echo $model['amount']; ?>" size="40" class="inpMain" />
                    </td>
                </tr>
                <tr>
                    <td align="right">说明</td>
                    <td>
                        <input type="text" name="Detail[description]" value="<?php echo $model['description']; ?>" size="40" class="inpMain" />
                    </td>
                </tr>     
                <tr>
                    <td align="right">操作时间</td>
                    <td>
                        <input type="text" name="Detail[time]" value="<?php echo $model['time']; ?>" size="40" class="inpMain" />
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
    </div>
</div>