<?php
##不需使用登入頁了,有另外的登入頁
include_once(dirname(dirname(__FILE__)).'/link.php');
include_once(dirname(dirname(__FILE__)).'/function.php');

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
    <script src="https://code.jquery.com/jquery-3.3.0.js"></script>
		<script src="..\jquery-validation-1.17.0\lib\jquery.js"></script><!--vaildate-->
		<script src="..\jquery-validation-1.17.0\dist\jquery.validate.js"></script><!--vaildate-->
  </head>
  <body>
  <form method="post" id="postform" name="postform" autocomplete="off">
   <div class="" style="border:3px solid;width:470px;height:200px;margin: 0 auto;">

     <div class="" style="border-bottom:1px solid;width:100%;height:30px;margin: 0 auto;">
       <div class="" style="display:inline-block;border-right:1px solid;width:13%;height:100%;text-align:center;line-height:30px;">
          帳號
       </div>
       <div class="" style="display:inline-block;width:75%;height:100%;text-align:center;line-height:30px;">
          <input type="text" id="manager_name" name="manager_name" value="" size="">
       </div>
     </div>

     <div class="" style="border-bottom:1px solid;width:100%;height:30px;margin: 0 auto;">
       <div class="" style="display:inline-block;border-right:1px solid;width:13%;height:100%;text-align:center;line-height:30px;">
          密碼
       </div>
       <div class="" style="display:inline-block;width:75%;height:100%;text-align:center;line-height:30px;">
          <input type="password" id="manager_password" name="manager_password" value="" size="">
       </div>
     </div>

     <div class="" style="width:100%;height:140px;margin: 0 auto;text-align:center;line-height:100%;">
       <?php
          echo '
          <div id="top_wrap">
            <div class="top_box">
              <div class="login_box">

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

              </div>
            </div>
          </div>
          ';
       ?>
     </div>

   </div>
  </form>

  <script>
/*
	$(document).ready(function()
	{
			$("#postform").validate(
			{
					rules:
					{
						manager_name: "required",
						manager_password: "required",
						ct_captcha: "required"
					},
					messages:
					{
						manager_name: "請輸入帳號",
						manager_password: "請輸入密碼",
						ct_captcha: "請輸入驗證碼"
					}
			});
*/
		function on_submit()
		{
      let manager_name=$('#manager_name').val();
      let manager_password=$('#manager_password').val();
			let ct_captcha=$('#ct_captcha').val();

      $.ajax(
      {
        url:'backstage_login_ajax.php',
        type:'post',
        cache:true,
        async:false,
        datatype:'json',
        data:
        {
          "manager_name":manager_name,
          "manager_password":manager_password,
					"ct_captcha":ct_captcha,
					"session_id":<?php echo $session_id;?>
        },
        error:function(data)
        {
            alert("填寫失敗");
        },
        success:function(data)
        {
          // console.log(data);return;
          var dataobj=$.parseJSON($.trim(data));
          if(dataobj.status=="success")
          {
            alert("填寫成功");
            window.location='backstage_index.php';
          }
					if(dataobj.status=="error")
					{
						alert("填寫失敗");
						window.location='backstage_login.php';
					}
        }
      });

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

    }
/*
});
*/
  </script>
 </body>
</html>
