<?php
session_start();
$phpbb_root_path = defined('PHPBB_ROOT_PATH') ? PHPBB_ROOT_PATH : './';

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-type: text/html; charset=utf-8');
header('Vary: Accept-Language');
date_default_timezone_set('Asia/Taipei');
mb_internal_encoding("UTF-8");

$session_id =0;
if(isset($_SESSION['session_id']) && trim($_SESSION['session_id'])){
	$session_id =$_SESSION['session_id'];
}else{
	$session_id = (time()%10000).''.mt_rand(10000000,99999999);
	$_SESSION['session_id']=$session_id;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="-1"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>驗證碼sample</title>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<body>
<?php
if($_POST)
{
	$check_err_msg='';
	$_captcha	=isset($_POST['ct_captcha'])&& trim($_POST['ct_captcha'])	? trim($_POST['ct_captcha']): '';
	$session_id =isset($_POST['session_id'])&& trim($_POST['session_id'])	? trim($_POST['session_id']): '';
	if($_captcha&&$session_id)
	{
		if($session_id!=$_SESSION['session_id']){
			$check_err_msg = '禁止不正常發送動作，即將返回首頁！';
		}else{
			require_once($phpbb_root_path.'js/securimage/securimage.php');
			$securimage = new Securimage();
			if($securimage->check($_captcha))
			{
				$check_err_msg="驗證成功！";
			}else{
				$check_resp_code=1;
				$check_err_msg = '驗證碼輸入錯誤，請重新輸入！';
			}
			unset($securimage);
		}
	}

	//echo '<script>alert("'.$check_err_msg.'");</script>';
}
else
{
	$check_resp_code=1;
	$check_err_msg="帳號或密碼輸入錯誤！";
}

if($check_resp_code ==0)
{
	echo '<script>alert("'.$check_err_msg.'"); document.location.href="test01.php";</script>';
}
elseif($check_resp_code ==1)
{
	echo '<script>alert("'.$check_err_msg.'");</script>';
}
?>

<?php
		echo '
		<div id="top_wrap">
			<div class="top_box">
				<div class="login_box">
					<form method="post" id="postform" name="postform" autocomplete="off">
						<table class="login_box_table" border="1" cellspacing="0" cellpadding="0">
							<tr>
								<td class="lb_type2">驗證碼 :</td>
								<td class="ver_code" valign="bottom">
									<div class="ver_box">
										<div align="left">
											<object type="application/x-shockwave-flash" data="js/securimage/securimage_play.swf?audio_file=js/securimage/securimage_play.php&amp;bgColor1=#ffffff&amp;bgColor2=#ffffff&amp;iconColor=#777777&amp;borderWidth=1&amp;borderColor=#000000" height="32" width="32">
												<param name="movie" value="js/securimage/securimage_play.swf?audio_file=js/securimage_play.php&amp;bgColor1=#ffffff&amp;bgColor2=#ffffff&amp;iconColor=#777777&amp;borderWidth=1&amp;borderColor=#000000">
											</object>
											<a onclick="document.getElementById(\'siimage\').src =\'./js/securimage/securimage_show.php?sid=\'+ Math.random();" href="#" title="Refresh Image" tabindex="-1" style="border-style: none;"><img src="js/securimage/images/refresh.png" title="更換驗證碼" border="0" /></a>不分大小寫
											<img id="siimage" style="border:1px solid #000000;" src="js/securimage/securimage_show.php?sid='.md5(uniqid()).'" title="請輸入驗證碼" border="0" />
										</div>
										<div align="left" style="float:left; text-align:left;margin-top:6px; width:100%;">
											<input type="text" id="ct_captcha" name="ct_captcha" class="lb_input2" maxlength="6" value="" style="width:120px;float:left;margin:0px;" />
											<a onclick="javascript:on_submit();return false;" href="#" class="login_btn" style="margin-left:10px;float:left;">送出</a>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<input type="hidden" id="session_id" name="session_id" value="'.$session_id.'"/>
					</form>
				</div>
			</div>
		</div>
		';
?>

<script type="text/javascript">

function on_submit()
{
	var ct_captcha	=$("#ct_captcha");
	if(!ct_captcha.val()){
		ct_captcha.focus();
		alert("尚未輸入驗證碼");
		return;
	}
	$("#postform").submit();
}

</script>
</body>
</html>
