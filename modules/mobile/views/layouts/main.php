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
    <meta content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width"
          name="viewport">
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
            <script src="<?= $jsFile . '?v=' . \Yii::$app->params['version']; ?>" type="text/javascript"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- END GLOBAL MANDATORY STYLES -->

    <?php if (!empty($this->params['extraLoadCss'])): ?>
        <?php foreach ($this->params['extraLoadCss'] as $cssFile): ?>
            <link href="<?= $cssFile . '?v=' . \Yii::$app->params['version']; ?>" rel="stylesheet" type="text/css"/>
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
<ul id="bottom_menu">
    <li class="bottom_menu_unit" style="border-right: 1px #fff solid;">平台
        <ul class="sub_menu" style="display: none">
            <a><li class="sub_menu_li">平台列表</li></a>
            <a href="/mobile/platform/create"><li class="sub_menu_li">创建平台</li></a>
        </ul>
    </li>
    <li class="bottom_menu_unit" style="border-right: 1px #fff solid;">账号
        <ul class="sub_menu" style="display: none">
            <a href="/mobile/account/index"><li class="sub_menu_li">账号列表</li></a>
            <a href="/mobile/account/create"><li class="sub_menu_li">创建账号</li></a>
        </ul>
    </li>
    <li class="bottom_menu_unit">汇总
        <ul class="sub_menu" style="display: none">
            <li class="sub_menu_li">说说的</li>
            <li class="sub_menu_li">看看吧</li>
        </ul>
    </li>
</ul>

<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    jQuery(document).ready(function () {
        $(".bottom_menu_unit").click(function () {
            var $this = $(this);
            var $t = $this.find("ul");
            if ($t.is(":visible")) {
                $t.css("display", "none");
            } else {
                $(".sub_menu").hide();
                $.each($this.find("ul li"), function (i, v) {
                    var top = -50 * (i + 1);
                    $(this).css("top", top);
                });
                $this.find("ul").css("display", "block");
            }
        });
    });

</script>
<!-- END JAVASCRIPTS -->
<?php $this->endBody() ?>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
