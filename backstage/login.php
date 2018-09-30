<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Linky360</title>

        <meta name="description" content="AppUI is a Web App Bootstrap Admin Template created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="css/themes.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

    </head>
    <body>
        <!-- Login Container -->
        <div id="login-container">
            <!-- Login Header -->
            <h1 class="h2 text-light text-center push-top-bottom animation-slideDown">
                <i class="fa fa-cube"></i> <strong>Linky360</strong>
            </h1>
            <!-- END Login Header -->

            <!-- Login Block -->
            <div class="block animation-fadeInQuickInv">
                <!-- Login Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="page_ready_reminder.html" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Forgot your password?"><i class="fa fa-exclamation-circle"></i></a>
                        <a href="page_ready_register.html" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Create new account"><i class="fa fa-plus"></i></a>
                    </div>
                    <h2>LINKY-UI-登入</h2>
                </div>
                <!-- END Login Title -->

                <!-- Login Form -->
				<?php
                echo '
				<form id="form-login" method="post" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="text" id="manager_name" name="manager_name" class="form-control" placeholder="帳號" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="password" id="manager_password" name="manager_password" class="form-control" placeholder="密碼" value="">
                        </div>
                    </div>
					<div align="left">
						<object type="application/x-shockwave-flash" data="js/securimage/securimage_play.swf?audio_file=js/securimage/securimage_play.php&amp;bgColor1=#ffffff&amp;bgColor2=#ffffff&amp;iconColor=#777777&amp;borderWidth=1&amp;borderColor=#000000" height="32" width="32">
							<param name="movie" value="js/securimage/securimage_play.swf?audio_file=js/securimage_play.php&amp;bgColor1=#ffffff&amp;bgColor2=#ffffff&amp;iconColor=#777777&amp;borderWidth=1&amp;borderColor=#000000">
						</object>
						<a onclick="document.getElementById(\'siimage\').src =\'./js/securimage/securimage_show.php?sid=\'+ Math.random();" href="#" title="Refresh Image" tabindex="-1" style="border-style: none;"><img src="js/securimage/images/refresh.png" title="更換驗證碼" border="0" /></a>不分大小寫
						<img id="siimage" style="border:1px solid #000000;" src="js/securimage/securimage_show.php?sid='.md5(uniqid()).'" title="請輸入驗證碼" border="0" />
					</div>
					</br>
                    <div class="form-group form-actions">
                        <div class="col-xs-8">
							<input type="text" id="ct_captcha" name="ct_captcha" class="form-control" placeholder="請輸入驗證碼" value="">
                            
                        </div>
                        <div class="col-xs-4 text-right">
                            <input type="button" id="onLogin" class="btn btn-effect-ripple btn-sm btn-primary" onclick="onLoginClick()" value="登入">
                        </div>
                    </div>
                </form>';

                ?>
                <!-- END Login Form -->
            </div>
            <!-- END Login Block -->
            
            <!-- END Footer -->
        </div>
        <!-- END Login Container -->

        <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file (Remove 'http:' if you have SSL) -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/app.js"></script>

        <script>
            function onLoginClick()
            {
                let manager_name=$('#manager_name').val();
                let manager_password=$('#manager_password').val();
                let ct_captcha=$('#ct_captcha').val();

                const manager_name2	=$("#manager_name");
                if(!manager_name2.val())
                {
                    manager_name2.focus();
                    alert("尚未輸入帳號");
                    return;
                }

                const manager_password2	=$("#manager_password");
                if(!manager_password2.val())
                {
                    manager_password2.focus();
                    alert("尚未輸入密碼");
                    return;
                }

                const ct_captcha2	=$("#ct_captcha");
                if(!ct_captcha2.val())
                {
                    ct_captcha2.focus();
                    alert("尚未輸入驗證碼");
                    return;
                }

                $.ajax(
                {
                    url:'login_ajax.php',
                    type:"post",
                    cache: true,
                    async:false,
                    datatype:"json",
                    data:
                    {
                        "manager_name":manager_name,
                        "manager_password":manager_password,
                        "ct_captcha":ct_captcha,
                    },
                    error:function(data)
                    {
                        alert("編輯失敗");
                    },
                    success:function(data)
                    {
                        // console.log(data);return;
                        const dataobj=$.parseJSON($.trim(data));
                        if(dataobj.status=="success")
                        {
                            switch(dataobj.check_err_code)
                            {
                                case 1:
                                    alert('驗證成功！');
                                    window.location="backstage_index.php";
                                    break;
                                case 2:
                                    alert('帳號或密碼輸入錯誤！');
                                    return;
                                    break;
                                case 3:
                                    alert('驗證碼輸入錯誤，請重新輸入！');
                                    return;
                                    break;
                                case 4:
                                    alert('請輸入 帳號 或 密碼 或 驗證碼！');
                                    return;
                                    break;
                                default:
                                    return;
                            }                          
                        }
                    }
                });

            }
        </script>	
    </body>
</html>