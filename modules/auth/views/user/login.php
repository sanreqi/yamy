<!DOCTYPE html>
<html class="bg-black">
<head>
    <meta charset="UTF-8">
    <title>超人小散 | 登录</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="/resources/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/resources/auth/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/resources/auth/css/AdminLTE.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-black">

<div class="form-box" id="login-box">
    <div class="header">超人小散控制台登录</div>
    <form action="/auth/user/login" method="post">
        <div class="body bg-gray">
            <?php $errors = $model->getFirstErrors(); ?>
            <?php if ($errors): ?>
                <?php foreach ($errors as $e): ?>
                    <div class="" style="color: red;"><?php echo $e; ?></div>
                    <?php break; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-group">
                <input type="text" value="<?php echo $model->username; ?>" name="LoginForm[username]" class="form-control" placeholder="用户名"/>
            </div>
            <div class="form-group">
                <input type="password" value="<?php echo $model->password; ?>" name="LoginForm[password]" class="form-control" placeholder="密码"/>
            </div>
            <div class="form-group">
                <input <?php echo $model->rememberMe ? 'checked=checked' : ''; ?> type="checkbox" name="LoginForm[rememberMe]"/> 记住我
            </div>
        </div>
        <div class="footer">
            <button type="submit" class="btn bg-olive btn-block">立即登录</button>
<!--            <p><a href="javascript:void(0)">忘记密码</a></p>-->
<!--            <a href="javascript:void(0)" class="text-center">注册</a>-->
        </div>
    </form>

<!--    <div class="margin text-center">-->
<!--        <span>社区活动</span>-->
<!--        <br/>-->
<!--        <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>-->
<!--        <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>-->
<!--        <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>-->
<!---->
<!--    </div>-->
</div>

</body>
</html>