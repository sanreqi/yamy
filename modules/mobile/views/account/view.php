<?php
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
        <div class="recharge_btn detail_btn">充值</div>
        <div class="cashback_btn detail_btn">提现</div>
        <div style="clear: both"></div>
    </div>
    <div id="account_view_list">
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">余下</li>
            <li class="account_view_list_right">可惜</li>
        </ul>
        <ul class="account_view_list_ul">
            <li class="account_view_list_left">但是</li>
            <li class="account_view_list_right">呵呵呵呵</li>
        </ul>
    </div>
</div>
