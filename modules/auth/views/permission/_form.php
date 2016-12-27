<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['extraLoadCss'] = [
    '/resources/auth/css/auth.css',
];
?>

<section class="content-header">
    <h1>
        创建权限
        <small>Permission Create</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<section class="content">
    <ul class="nav nav-tabs pr-tab" role="tablist">
        <li role="presentation"><a href="/auth/role">角色</a></li>
        <li role="presentation" class="active"><a href="/auth/permission">权限</a></li>
    </ul>
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]) ?>

    <?= $form->field($model, 'name')->label("权限名称") ?>
    <?= $form->field($model, 'description')->textarea()->label("权限说明"); ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</section>