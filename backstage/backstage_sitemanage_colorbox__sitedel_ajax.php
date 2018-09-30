<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$sid=isset($_POST['sid'])&&trim($_POST['sid'])?trim($_POST['sid']):'';
$end_time=date("Y-m-d H:i:s");

$connectSql="SELECT * from connect where sid=$sid";
$connectSqlResult=$link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows=$connectSqlResult->fetchall();
// pre($connectSqlRows);exit;
if(!empty($connectSqlRows))
{
  $arr=array('status3'=>'have');
}
elseif(empty($connectSqlRows))
{
  $siteUpdte="UPDATE site SET status=0,end_time='$end_time' WHERE sid=$sid";
  $siteUpdteResult=$link->prepare($siteUpdte);
  $siteUpdteResult->execute();

  $siteSql="SELECT place from site where sid=$sid";
  $siteSqlResult=$link->prepare($siteSql);
  $siteSqlResult->execute();
  $siteSqlRows=$siteSqlResult->fetch();

  $place2=$siteSqlRows['place'];
  $status2=!empty($sid)?'success':'error';

  $arr=array('status2'=>$status2,'place2'=>$place2);
}
echo json_encode($arr);
?>
