<!DOCTYPE html>
<html>
<head>
    <title>登录注册表单切换</title>

    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <script type="text/javascript" src="<?php echo base_url() ?>style/login/js/jquery-1.11.1.min.js"></script>

    <link href="<?php echo base_url() ?>style/login/css/style.css" rel="stylesheet" type="text/css" media="all"/>

</head>
<body>

<div class="main">
    <!--    <h1>登录注册表单切换</h1>-->
    <h1></h1>
    <div class="w3_login">
        <div class="w3_login_module">
            <div class="module form-module">
                <div class="toggle">
                    <i class="fa fa-times fa-pencil"></i>
                    <div class="tooltip">点击切换</div>
                </div>
                <div class="form">
                    <h2>登录账号</h2>
                    <form action="#" method="post" class="login" onsubmit="return false">
                        <input type="text" name="post[username]" placeholder="用户名" required/>
                        <input type="password" name="post[password]" placeholder="密码" required/>
                        <input type="submit" value="登录" onclick="checkLogin()"/>
                    </form>
                </div>
                <div class="form">
                    <h2>创建一个账号</h2>
                    <form action="#" method="post" class="register" onsubmit="return false">
                        <input type="text" name="post[username]" placeholder="用户名" required=""/>
                        <input type="password" name="post[password]" placeholder="密码" required=""/>
                        <input type="password" name="post[rpassword]" placeholder="再次输入密码" required=""/>
                        <input type="submit" value="注册" onclick="checkRegister()"/>
                    </form>
                </div>
                <div class="cta"><a href="#">忘记密码?</a></div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>style/plugins/layer/layer.js"></script>
<script src="<?php echo base_url() ?>style/plugins/cookie/cookie.js"></script>
<script src="<?php echo base_url() ?>style/plugins/message/message.js"></script>
<script type="text/javascript">
    let siteUrl = '<?php echo site_url()?>';

    $('.toggle').click(function () {
        $(this).children('i').toggleClass('fa-pencil');
        $('.form').animate({
            height: "toggle",
            'padding-top': 'toggle',
            'padding-bottom': 'toggle',
            opacity: "toggle"
        }, "slow");
    });

    function checkLogin() {
        let loadT = layer.msg('正在提交数据...', {time: 0, icon: 16, shade: [0.3, '#000']});
        $.ajax({
            url: siteUrl + "login/login",
            type: 'post',
            dataType: 'json',
            data: $('.login').serialize(),
            success(res) {
                if (res.code == 0) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.href = siteUrl + "index?token=" + res.token;
                    });
                } else {
                    layer.msg(res.msg, {icon: 2, shift: 6});
                }
            },
            complete() {
                layer.close(loadT);
            },
            xhrFields: {withCredentials: true}
        });
    }

    function checkRegister() {
        let loadT = layer.msg('正在提交数据...', {time: 0, icon: 16, shade: [0.3, '#000']});
        $.post(siteUrl + "/login/register", $(".register").serialize(), function (res) {
            layer.close(loadT);
            if (res.code == 0) {
                layer.msg(res.msg, {icon: 1});
                window.location.href = siteUrl + "login";
            } else {
                layer.msg(res.msg, {icon: 2, shift: 6});
            }
        }, "json");
    }
</script>
</body>
</html>