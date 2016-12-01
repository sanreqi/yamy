<?php
use yii\helpers\Url;
use app\models\Detail;

$this->params['extraLoadJS'] = [
    '/resources/mobile/js/account.js',
];
$this->params['extraLoadCss'] = [
    '/resources/mobile/css/mobile.css',
    '/resources/mobile/css/yamy.css'
];
?>

<div class='container_head'>
    <span class='jobdetail'>账户详情</span>
    <span class='back'><a href="javascript:history.go(-1);"><span class='glyphicon glyphicon-chevron-left'></span></a></span>
</div>
<div class="view_container">
    <div class="detail_btn_group">
        <a href="<?php echo Url::toRoute(['/mobile/account/create-detail', 'id'=>$_GET['id'],'type'=>Detail::TYPE_RECHARGE]); ?>"><div class="recharge_btn detail_btn">充值</div></a>
        <a href="<?php echo Url::toRoute(['/mobile/account/create-detail', 'id'=>$_GET['id'],'type'=>Detail::TYPE_WITHDRAW]); ?>"><div class="cashback_btn detail_btn">提现</div></a>
        <div style="clear: both"></div>
    </div>
    <div id="account_view_list">
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">平台名称</li>
            <li class="account_view_list_right"><?php echo $platformName; ?></li>
        </ul>
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">账号</li>
            <li class="account_view_list_right"><?php echo $account['mobile']; ?></li>
        </ul>
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">充值总额</li>
            <li class="account_view_list_right"><?php echo $recharge; ?></li>
        </ul>
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">提现总额</li>
            <li class="account_view_list_right"><?php echo $withdraw; ?></li>
        </ul>
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">返现</li>
            <li class="account_view_list_right"><?php echo $cashback; ?></li>
        </ul>
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">收益</li>
            <li class="account_view_list_right"><?php echo $profit; ?></li>
        </ul>
    </div>
</div>
