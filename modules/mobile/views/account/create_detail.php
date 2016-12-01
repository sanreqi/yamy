<?php
$this->params['extraLoadJS'] = [
    '/resources/mobile/js/detail.js'
];
$this->params['extraLoadCss'] = [
    '/resources/mobile/css/mobile.css'
];
?>

<div class='container_head'>
    <span class='jobdetail'>新增明细</span>
    <span class='back'><a href="javascript:history.go(-1);"><span class='glyphicon glyphicon-chevron-left'></span></a></span>
    <span class='form_save' account_id="<?php echo $_GET['id']; ?>" type="<?php echo $_GET['type']; ?>">保存</span>
</div>

<div class="form_container">
    <div class="form_list">
        <div class="form_left">平台名称</div>
        <div class="form_right"><span>人人贷</span></div>
    </div>
    <div class="form_list">
        <div class="form_left">账号名称</div>
        <div class="form_right"><span>13817917452</span></div>
    </div>
    <div class="form_list">
        <div class="form_left">操作类型</div>
        <div class="form_right"><span>充值</span></div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>金额</div>
        <div class="form_right">
            <input value="" placeholder="" class="detail_amount" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">手续费</div>
        <div class="form_right">
            <input value="0" placeholder="" class="detail_charge" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>操作时间</div>
        <div class="form_right">
            <input value="<?php echo date('Y-m-d'); ?>" placeholder="" class="detail_time" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>余额</div>
        <div class="form_right">
            <input value="<?php echo 1; ?>" placeholder="" class="detail_balance" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>回款时间</div>
        <div class="form_right">
            <input value="21" placeholder="" class="detail_returned" type="text">
        </div>
    </div>
</div>