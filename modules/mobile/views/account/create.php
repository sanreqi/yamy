<?php
$this->params['extraLoadJS'] = [
    '/resources/mobile/js/account.js',
];
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
            <select class="form_selected platform">
                <?php foreach ($platformOptions as $k => $v): ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>账号信息</div>
        <div class="form_right">
            <select class="form_selected bank_account">
                <?php foreach ($bankAccountOptions as $k => $v): ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form_list">
        <div class="form_left"><span class="required">*</span>余额</div>
        <div class="form_right">
            <input value="" placeholder="" class="balance" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">回款日期</div>
        <div class="form_right">
            <input value="<?php echo date('Y-m-d', strtotime(date('Y-m-d'))+86400*31); ?>" placeholder="" class="returned_date" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">充值金额</div>
        <div class="form_right">
            <input value="" placeholder="" class="recharge_amount" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">充值时间</div>
        <div class="form_right">
            <input value="<?php echo date('Y-m-d'); ?>" placeholder="" class="recharge_date" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">返现人</div>
        <div class="form_right">
            <input value="比蓝更蓝" placeholder="" class="cashback_name" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">返现金额</div>
        <div class="form_right">
            <input value="" placeholder="" class="cashback_amount" type="text">
        </div>
    </div>
    <div class="form_list">
        <div class="form_left">返现时间</div>
        <div class="form_right">
            <input value="<?php echo date('Y-m-d'); ?>" placeholder="" class="cashback_date" type="text">
        </div>
    </div>
</div>