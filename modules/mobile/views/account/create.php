<?php

$this->params['extraLoadCss'] = [
    '/resources/mobile/css/mobile.css'
];
?>

<div class='container_head'>
    <span class='jobdetail'>创建账户</span>
    <span class='back'><a href="javascript:history.go(-1);"><span class='glyphicon glyphicon-chevron-left'></span></a></span>
    <span class='form_save'>保存</span>
</div>

<div class="form_container">
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>平台名称</div>
        <div class="form_right">
            <select class="form_selected">
                <?php foreach ($platformOptions as $k => $v): ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>账号信息</div>
        <div class="form_right">
            <select class="form_selected">
                <?php foreach ($bankAccountOptions as $k => $v): ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>余额</div>
        <div class="form_right">
            <input value="" placeholder="" class="" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">最近回款日期</div>
        <div class="form_right">
            <input value="" placeholder="" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">充值金额</div>
        <div class="form_right">
            <input value="" placeholder="" class="" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">充值时间</div>
        <div class="form_right">
            <input value="" placeholder="" class="" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">返现人</div>
        <div class="form_right">
            <input value="" placeholder="" class="" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">返现金额</div>
        <div class="form_right">
            <input value="" placeholder="" class="" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">返现时间</div>
        <div class="form_right">
            <input value="" placeholder="" class="" type="text">
        </div>
    </div>
</div>