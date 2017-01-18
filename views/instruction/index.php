<?php
use app\models\BorrowPayment;
use app\models\Landmine;
$this->params['extraLoadJS'] = [
    '/resources/js/account/account.js'
];
?>
<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>充值提现</strong></div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>
           <span class="display_sum" style="display: none;">
               <?php echo '总利润：'.$profit.'&nbsp&nbsp&nbsp&nbsp总利息：'.
                   BorrowPayment::getInterestByUid(Yii::$app->user->id).'&nbsp&nbsp&nbsp&nbsp总地雷：'.
                   Landmine::getTotal(); ?>元
           </span>
            <a href="javascript:void(0)" class="view_sum">查看</a>
        </h3>
        <div>1.总利润等于 总资产-（充值总金额-提现总金额）+返现总金额</div>
        <div>2.利润都是2016年6月之后获得，2015年7月-2016年5月利润约6000左右，不记录在内</div>
        <div>3.新建返现必须从充值提现页面的充值记录中过去，点击充值这条记录的id</div>
        <div>4.第一次羊毛PPMONEY,2016年6月13日</div>
    </div>
</div>

<script>
    $(document).ready(function() {
        yamy.account.init();
    });
</script>