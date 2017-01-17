<?php

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js',
    '/resources/js/borrow/borrow.js',
    '/resources/js/datetimepicker.js',
];
$this->params['extraLoadCss'] = [
    '/resources/css/yamy.css',
    '/resources/css/datetimepicker.css',
];
?>

<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>p2p平台列表</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="/borrow/detail-create" class="actionBtn add">新增借款明细</a>借款明细</h3>
        <ul class="tab" style="margin-bottom: 20px;">
            <li><a href="javascript:void(0)" data-id="1" class="selected status">未还清</a></li>
            <li><a href="javascript:void(0)" data-id="0" class="status">已还清</a></li>
        </ul>
        <div style="margin-bottom: 20px;">
            <input type="text" name="" value="" size="20"
                   class="inpMain datepicker start_time" />&nbsp&nbsp&nbsp~&nbsp&nbsp&nbsp
            <input type="text" name="" value="" size="20"
                   class="inpMain datepicker end_time" />
            <a style="margin-left: 20px;" class="btn search">搜索</a>
        </div>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic"></table>
        <div class="spage"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        yamy.borrow.init();
        yamy.borrow.detail.init();
    });
</script>