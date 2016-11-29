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
    <span class='jobdetail'>账户列表</span>
    <span class='back'><a href="javascript:history.go(-1);"><span class='glyphicon glyphicon-chevron-left'></span></a></span>
</div>
<div class="list_container">
    <div class="list_search">
        <input class="search_text" placeholder="请输入平台名称" />
        <div class="search_btn">搜索</div>
    </div>
    <ul class="list_display">
    </ul>
</div>
