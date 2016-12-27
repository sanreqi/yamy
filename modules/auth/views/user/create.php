<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['extraLoadCss'] = [
    '/resources/auth/css/auth.css',
];

?>

<section class="content-header">
    <h1>
        创建用户
        <small>User Create</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<section class="content">
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]) ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'truename') ?>
    <?= $form->field($model, 'pwd')->passwordInput() ?>
    <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>
    <?= $form->field($model, 'roles')->checkboxList($roleList)->label("基于角色"); ?>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</section>