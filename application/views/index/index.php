<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="format-detection" content="telephone=no"/>
    <!--<title>jQuery仿新浪微博发布新鲜事页面代码</title>-->
    <title>页面代码</title>

    <link rel="stylesheet" href="<?php echo base_url() ?>style/blog/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>style/blog/css/style.css">

</head>
<body>
<nav class="navbar  navbar-fixed-top" role="navigation" style="background: #e0620d ;padding-top: 3px;height:50px;">
    <div class="container-fluid" style="background: #fff;">
        <div class="navbar-header ">
            <span class="navbar-brand " href="#">WEIBO</span>

            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#my-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="#热门话题#">
                <i class="glyphicon glyphicon-search btn_search"></i>
                <!--  <button type="submit" class="btn btn-default">提交</button> -->
            </div>

        </form>

        <div class="collapse navbar-collapse" id="my-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>&nbsp;
                        &nbsp;<?php echo $username; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url('login/logout?token=' . trim($_GET['token'])) ?>">退出登录</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <hr style="margin: 0;padding: 0;color:#222;width: 100%">
</nav>

<div class="container container_bg">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-6 col-xs-12 my_edit">
            <div class="row" id="edit_form">
                <span class="pull-left" style="margin:15px;">编写新鲜事</span>
                <span class="tips pull-right" style="margin:15px;"></span>
                <form id="send-form" role="form" style="margin-top: 50px;" action="<?php echo site_url('index/send?token=' . trim($_GET['token'])) ?>" method="post">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div contentEditable="true" id="content" class="form-control "></div>
                        </div>
                        <div class="col-sm-12" style="margin-top: 12px;">
                            <span class="emoji">表情</span>

                            <span class="pic">图片	</span>
                            <span>
									<input type="file" name="" class="select_Img" style="display: none">
									<img class="preview" src="">
                            </span>

                            <div class="myEmoji">
                                <ul id="myTab" class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#set" data-toggle="tab">
                                            预设
                                        </a>
                                    </li>
                                    <li><a href="#hot" data-toggle="tab">热门</a></li>

                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div class="tab-pane fade in active" id="set">
                                        <div class="emoji_1"></div>

                                    </div>
                                    <div class="tab-pane fade" id="hot">
                                        <div class="emoji_2"></div>
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" name="content" id="send-content">
                            <!-- <span> <input type="file" id="selectImg" value=""></input> </span> -->
                            <button type="button" id="send" class="btn btn-default pull-right disabled">发布</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row item_msg">
                <style>
                    .follow-btn{
                        float: right;
                        border: 1px #aaa dotted;
                        padding: 3px;
                        background-color: #eee;
                        color: #444;
                        font-size: 12px;
                        cursor: pointer;
                    }
                </style>
                <?php foreach ($my_follow_post as $k => $v){?>
                <div class="col-sm-12 col-xs-12 message">
                    <img src="<?php echo base_url() ?>style/blog/img/icon.png" class="col-sm-2 col-xs-2" style="border-radius: 50%">
                    <div class="col-sm-10 col-xs-10">
                        <div class="col-sm-12 col-xs-12" style="padding: 0;">
                            <span style="font-weight: bold;vertical-align: middle;font-size: 16px;"><?php echo $v['username']?></span>
                            <?php if ($v['is_follow'] == 1 && $v['user_id'] != $user_id){?>
                                <span class="follow-btn" style="background: #629e43;color: #fff;" onclick="follow(this, <?php echo $v['user_id']?>, 1)">取消关注</span>
                            <?php }elseif($v['is_follow'] == 0 && $v['user_id'] != $user_id){?>
                                <span class="follow-btn" onclick="follow(this, <?php echo $v['user_id']?>, 0)">关注ta</span>
                            <?php }?>
                        </div>
                        <br>
                        <small class="date" style="color:#999"><?php echo $v['time']?></small>
                        <div class="msg_content">
                            <?php echo $v['content']?>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>


        <div class="col-sm-3 col-xs-12 part_right">
            <div class="row text-center inform">
                <img src="<?php echo base_url() ?>style/blog/img/icon.png">
                <h4 style="font-weight: bold;"><?php echo $username ?></h4>
                <div class="col-sm-12 my_inform">
                    <div class="col-sm-4 col-xs-4">
                        <div><?php echo $follow_count; ?></div>
                        <div class="sort">关注</div>

                    </div>
                    <div class="col-sm-4 col-xs-4">
                        <div><?php echo $fans_count; ?></div>
                        <div class="sort">粉丝</div>
                    </div>
                    <div class="col-sm-4 col-xs-4">
                        <div><?php echo $post_count?></div>
                        <div class="sort">博客</div>
                    </div>
                </div>
            </div>
            <style>
                .user-head{
                    width: 40px;
                    height: 40px;
                    float: left;
                    border-radius: 50%;
                }
                .item_hot{
                    display: flex;
                    align-items: center;
                }
            </style>
            <div class="row part_hot">
                <div class="col-sm-12">
                    <span class="pull-left" style="padding: 10px;font-size:16px;font-weight: bold;">关注动态</span>
                    <!--                    <span class="pull-right" style="padding: 10px;">换话题</span>-->
                </div>
                <hr style="margin: 0;padding: 0;width: 100%">
                <?php foreach ($user_list as $k => $v){?>
                <div class="col-sm-12 item_hot">
                    <img class="user-head" src="http://localhost/blog/style/blog/img/icon.png">
                    <span class="pull-left" style="margin-left: 10px;font-size: 16px;">
                        <?php echo $v['username']?>
                    </span>
                    <span class="pull-right item_num" style="flex: 1;text-align: right;">
                        <?php if ($v['is_follow'] == 1 && $v['id'] != $user_id){?>
                            <span class="follow-btn" style="background: #629e43;color: #fff;" onclick="follow(this, <?php echo $v['id']?>, 1)">取消关注</span>
                        <?php }elseif($v['is_follow'] == 0 && $v['id'] != $user_id){?>
                            <span class="follow-btn" onclick="follow(this, <?php echo $v['id']?>, 0)">关注ta</span>
                        <?php }?>
                    </span>
                </div>
                <?php }?>
                <hr style="margin: 0;padding: 0;width: 100%">

                <div class="col-sm-12 text-center" style="padding: 10px"><a href="#">查看更多</a></div>

            </div>
            <div class="row part_hot">
                <div class="col-sm-12">
                    <span class="pull-left" style="padding: 10px;font-size:16px;font-weight: bold;">实时热点</span>
                    <!--                    <span class="pull-right" style="padding: 10px;">换话题</span>-->
                </div>
                <hr style="margin: 0;padding: 0;width: 100%">
                <?php foreach ($all_post as $k => $v){?>
                <div class="col-sm-12 item_hot">
                    <span class="pull-left"><?php echo $v['content']?></span>
                    <span class="pull-right item_num" style="flex: 1;text-align: right;"><?php echo $v['view']?></span>
                </div>
                <?php }?>
                <hr style="margin: 0;padding: 0;width: 100%">
                <div class="col-sm-12 text-center" style="padding: 10px"><a href="#">查看更多</a></div>

            </div>

        </div>
    </div>


</div>
<script src="<?php echo base_url() ?>style/blog/js/jquery-3.1.0.js"></script>
<script src="<?php echo base_url() ?>style/blog/js/bootstrap.min.js"></script>
<script type="text/javascript">
    let siteUrl = '<?php echo site_url()?>';
    let token = '<?php echo $_GET['token']?>';

    function follow(obj, user_id, flag)
    {
        $.get(siteUrl + 'index/follow?token='+token+'&uid='+user_id+'&flag='+flag, function (res) {
            if (res.code == 0)
            {
                window.location.reload();
            }
        }, 'json')
    }

    $(function () {

        $("#content").keyup(function () {

            //判断输入的字符串长度
            var content_len = $("#content").text().replace(/\s/g, "").length;

            $(".tips").text("已经输入" + content_len + "个字");


            if (content_len == 0) {
                // alert(content);
                $(".tips").text("");
                $("#send").addClass("disabled");
                return false;
            } else {
                $("#send").removeClass("disabled");
            }
        });

        $(".pic").click(function () {

            $(".select_Img").click();


        });

        //点击按钮发送内容
        $("#send").click(function () {
            var content = $("#content").html();

            //判断选择的是否是图片格式
            var imgPath = $(".imgPath").text();
            var start = imgPath.lastIndexOf(".");
            var postfix = imgPath.substring(start, imgPath.length).toUpperCase();

            $("#send-content").val(content);
            $("#send-form").submit();
            //if (imgPath != "") {
            //    if (postfix != ".PNG" && postfix != ".JPG" && postfix != ".GIF" && postfix != ".JPEG") {
            //        alert("图片格式需为png,gif,jpeg,jpg格式");
            //        return false;
            //    } else {
            //        $(".item_msg").append("<div class='col-sm-12 col-xs-12 message' > <img src='<?php //echo base_url()?>//style/blog/img/icon.png' class='col-sm-2 col-xs-2' style='border-radius: 50%'><div class='col-sm-10 col-xs-10'><span style='font-weight: bold;''>Jack.C</span> <br><small class='date' style='color:#999'>刚刚</small><div class='msg_content'>" + content + "<img class='mypic' onerror='this.src='<?php //echo base_url()?>//style/blog/img/bg_1.jpg' src='file:///" + imgPath + "' ></div></div></div>");
            //    }
            //} else {
            //    $(".item_msg").append("<div class='col-sm-12 col-xs-12 message' > <img src='<?php //echo base_url()?>//style/blog/img/icon.png' class='col-sm-2 col-xs-2' style='border-radius: 50%'><div class='col-sm-10 col-xs-10'><span style='font-weight: bold;''>Jack.C</span> <br><small class='date' style='color:#999'>刚刚</small><div class='msg_content'>" + content + "</div></div></div>");
            //}
        });

        //添加表情包1
        for (var i = 1; i < 60; i++) {
            $(".emoji_1").append("<img src='<?php echo base_url()?>style/blog/img/f" + i + ".png' style='width:35px;height:35px' >");
        }
        //添加表情包2
        for (var i = 1; i < 61; i++) {
            $(".emoji_2").append("<img src='<?php echo base_url()?>style/blog/img/h" + i + ".png' style='width:35px;height:35px' >");
        }


        $(".emoji").click(function () {
            $(".myEmoji").show();
            //点击空白处隐藏弹出层
            $(document).click(function (e) {

                if (!$("#edit_form").is(e.target) && $("#edit_form").has(e.target).length === 0) {

                    $(".myEmoji").hide();
                }
            });
        });

        //将表情添加到输入框
        $(".myEmoji img").each(function () {
            $(this).click(function () {
                var url = $(this)[0].src;

                $('#content').append("<img src='" + url + "' style='width:25px;height:25px' >");

                $("#send").removeClass("disabled");
            })
        });

        //放大或缩小预览图片
        $(".mypic").click(function () {
            var oWidth = $(this).width(); //取得图片的实际宽度
            var oHeight = $(this).height(); //取得图片的实际高度

            if ($(this).height() != 200) {
                $(this).height(200);
            } else {
                $(this).height(oHeight + 200 / oWidth * oHeight);

            }
        })

    })
</script>
</body>
</html>