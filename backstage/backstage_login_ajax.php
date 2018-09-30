<?php
##不需使用登入頁了,有另外的登入頁
include_once(dirname(dirname(__FILE__)).'/link.php');
include_once(dirname(dirname(__FILE__)).'/function.php');

$phpbb_root_path = defined('PHPBB_ROOT_PATH') ? PHPBB_ROOT_PATH : './';

$_SESSION['manager_name']=isset($_POST['manager_name'])&&trim($_POST['manager_name'])?trim($_POST['manager_name']):'';
$_SESSION['manager_password']=isset($_POST['manager_password'])&&trim($_POST['manager_password'])?trim($_POST['manager_password']):'';
$_captcha	=isset($_POST['ct_captcha'])&&trim($_POST['ct_captcha'])?trim($_POST['ct_captcha']):'';
$_SESSION['session_id']=isset($_POST['session_id'])&&trim($_POST['session_id'])?trim($_POST['session_id']):'';
$session_id =0;
if(isset($_SESSION['session_id']) && trim($_SESSION['session_id'])){
	$session_id =$_SESSION['session_id'];
}else{
	$session_id = (time()%10000).''.mt_rand(10000000,99999999);
	$_SESSION['session_id']=$session_id;
}

$manager_name=$_SESSION['manager_name'];
$manager_password=$_SESSION['manager_password'];

if($manager_name&&$manager_password)
{
  $check_err_msg='';
  $check_err_code=0;

  $managerSql="SELECT count(*) as logincount from manager where manager_name='$manager_name' and manager_password='$manager_password'";
  $managerResult=$link->prepare($managerSql);
  $managerResult->execute();
  $managerRows=$managerResult->fetchall();

  if($managerRows['0']['logincount']!=1)
  {
    $check_err_code=1;
    $check_err_msg="帳號或密碼輸入錯誤！";
  }
  else
  {
    $manager_name=$managerRows['0']['logincount'];
    if(!empty($manager_name))
    {
      if($_captcha&&$session_id)
      {
        if($session_id!=$_SESSION['session_id'])
        {
          $check_err_code=2;
          $check_err_msg = '禁止不正常發送動作，即將返回首頁！';
        }
        else
        {
          require_once($phpbb_root_path.'js/securimage/securimage.php');
          $securimage = new Securimage();
          if($securimage->check($_captcha))
          {
            $check_err_msg="驗證成功！";
          }else
          {
            $check_err_code=3;
            $check_err_msg = '驗證碼輸入錯誤，請重新輸入！';
          }
          unset($securimage);
        }
      }
    }
    }
    /*
  if($check_err_code==0)
  {
    echo $check_err_msg;
    //echo '<script>alert("'.$check_err_msg.'");</script>';
  }
  elseif($check_err_code==1)
  {
    echo $check_err_msg;
    //echo '<script>alert("'.$check_err_msg.'");</script>';
  }
*/
  $status=!empty($manager_name)&&$check_err_code==0?'success':'error';
  $arr=array('status'=>$status);
  echo json_encode($arr);
}
?>
