<?php
use yii\helpers\Url;
use app\models\Platform;
use app\models\Account;
use app\models\Detail;
use app\models\Cashback;
use yii\widgets\LinkPager;
$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>返现</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="" class="actionBtn add">新增返现</a>返现</h3>
        <h3>返现总额：<?php echo 1; ?>元</h3>
    </div>
</div>
