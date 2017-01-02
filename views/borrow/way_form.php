<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
$this->params['extraLoadCss'] = [
    '/resources/bootstrap/css/bootstrap.min.css',
    '/resources/css/yamy.css',
];
?>

<div id="dcMain">
    <div id="urHere">利息</div>
    <div class="mainBox">
        <h3>借款途径</h3>
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
//                'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
        ]) ?>


        <?= $form->field($model, 'platform')->label("角色名称"); ?>
        <?= $form->field($model, 'platform')->label("角色名称"); ?>
        <?= $form->field($model, 'platform')->label("角色名称"); ?>
        321

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>




