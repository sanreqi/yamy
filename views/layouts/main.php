<?php

use yii\helpers\Html;
?>

<?php $selected = Yii::$app->params['selected']; ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="UTF-8" />
        <title><?= Html::encode($this->title) ?>超人小散</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="123" name="description" />
        <meta content="www.123.com" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link rel="stylesheet" href="/resources/css/public.css" />
        <link rel="stylesheet" href="/resources/css/yamy.css" />
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
                <link href="<?= $cssFile . '?v=' . \Yii::$app->params['version']; ?>" rel="stylesheet" type="text/css" />
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="favicon.ico"/>
        <?php $this->head() ?>
    </head>
    <!-- END HEAD -->
    <body>
        <?php $this->beginBody() ?>
        <div id="dcWrap">
            <div id="dcHead">
                <div id="head">
<!--                    <div class="logo"><a href="index.html"><img src="images/dclogo.gif" alt="logo"></a></div>-->
                    <div class="nav">
                        <ul>
                            <li><a href="/main/index">首页</a></li>
                            <li><a href="/platform/index">p2p平台</a></li>
                            <li><a href="/account/index">p2p账号</a></li>
                            <li><a href="/detail/index">充值提现</a></li>
                            <li><a href="/cashback/index">返现</a></li>
                            <li><a href="/bank/index">个人信息</a></li>
                            <li class="M"><a href="JavaScript:void(0);" class="">借款</a>
                                <div class="drop mTopad">
                                    <a href="/borrow/way-index">借款途径</a>
                                    <a href="/borrow/detail-index">借款列表</a>
                                </div>
                            </li>
                            <li><a href="/instruction/index">说明</a></li>
                        </ul>
                        <ul class="navRight">
                            <li class="M noLeft"><a href="JavaScript:void(0);">您好，<?php echo Yii::$app->user->identity->username; ?></a>
                                <div class="drop mUser">
                                    <a href="manager.php?rec=edit&id=1">编辑我的个人资料</a>
                                    <a href="manager.php?rec=cloud_account">设置云账户</a>
                                </div>
                            </li>
                            <li class="noRight"><a href="/auth/user/logout">退出</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="dcLeft">
                <div id="menu">
                    <ul class="top">
                        <li><a href="index.html"><i class="home"></i><em>管理首页</em></a></li>
                    </ul>
                    <ul>
                        <li><a href="system.html"><i class="system"></i><em>系统设置</em></a></li>
                        <li><a href="nav.html"><i class="nav"></i><em>自定义导航栏</em></a></li>
                        <li><a href="show.html"><i class="show"></i><em>首页幻灯广告</em></a></li>
                        <li><a href="page.html"><i class="page"></i><em>单页面管理</em></a></li>
                    </ul>
                    <ul>
                        <li class="<?php echo $selected=='category' ? 'cur' : ''; ?>"><a href="/category/index"><i class="productCat"></i><em>商品分类</em></a></li>
                        <li class="<?php echo $selected=='create' ? 'cur' : ''; ?>"><a href="/category/create"><i class="product"></i><em>商品列表</em></a></li>
                    </ul>
                    <ul>
                        <li><a href="article_category.html"><i class="articleCat"></i><em>文章分类</em></a></li>
                        <li><a href="article.html"><i class="article"></i><em>文章列表</em></a></li>
                    </ul>
                    <ul class="bot">
                        <li><a href="backup.html"><i class="backup"></i><em>数据备份</em></a></li>
                        <li><a href="mobile.html"><i class="mobile"></i><em>手机版</em></a></li>
                        <li><a href="theme.html"><i class="theme"></i><em>设置模板</em></a></li>
                        <li><a href="manager.html"><i class="manager"></i><em>网站管理员</em></a></li>
                        <li><a href="manager.php?rec=manager_log"><i class="managerLog"></i><em>操作记录</em></a></li>
                    </ul>
                </div>
            </div>
            
            <?=$content?>
            <?php // echo Yii::$app->params['selected']; exit; ?>
            <div class="clear"></div>
            <div id="dcFooter">
                <div id="footer">
                    <div class="line"></div>
                    <ul>
                        版权所有 © 2013-2015 666srq，并保留所有权利。
                    </ul>
                </div>
            </div><!-- dcFooter 结束 -->
            <div class="clear"></div> 
        </div>

        <!-- END PAGE LEVEL SCRIPTS -->
        <script type="text/javascript">
            jQuery(document).ready(function() {

            });
        </script>
        <!-- END JAVASCRIPTS -->
        <?php $this->endBody() ?>
    </body>
    <!-- END BODY -->
</html>
<?php $this->endPage() ?>
