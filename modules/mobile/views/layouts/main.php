<?php

use yii\helpers\Html;

?>

<?php $selected = Yii::$app->params['selected']; ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8"/>
    <title><?= Html::encode($this->title) ?>超人小散</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="123" name="description"/>
    <meta content="www.123.com" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<!--    <link rel="stylesheet" href="/resources/css/public.css"/>-->
<!--    <link rel="stylesheet" href="/resources/css/yamy.css"/>-->
    <script src="/resources/js/jquery.min.js" type="text/javascript"></script>
    <!--<script src="/resources/js/jquery.tab.js" type="text/javascript"></script>-->
    <!--<script src="/resources/js/jquery.autoTextarea.js" type="text/javascript"></script>-->
    <script src="/resources/js/global.js" type="text/javascript"></script>
    <!--根据每个页面额外的需求加载js-->
    <?php if (!empty($this->params['extraLoadJS'])): ?>
        <?php foreach ($this->params['extraLoadJS'] as $jsFile): ?>
            <script src="<?= $jsFile ?>" type="text/javascript"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- END GLOBAL MANDATORY STYLES -->

    <?php if (!empty($this->params['extraLoadCss'])): ?>
        <?php foreach ($this->params['extraLoadCss'] as $cssFile): ?>
            <link href="<?= $cssFile ?>" rel="stylesheet" type="text/css"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
    <?php $this->head() ?>
</head>
<!-- END HEAD -->
<body>
<?php $this->beginBody() ?>

<?php echo $content; ?>

<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
</script>
<!-- END JAVASCRIPTS -->
<?php $this->endBody() ?>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
