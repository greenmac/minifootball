<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$sid=isset($_POST['sid'])&&trim($_POST['sid'])?trim($_POST['sid']):'';
$place=isset($_POST['place'])&&trim($_POST['place'])?trim($_POST['place']):'';
$location=isset($_POST['location'])&&trim($_POST['location'])?trim($_POST['location']):'';
$address=isset($_POST['address'])&&trim($_POST['address'])?trim($_POST['address']):'';
$said=isset($_POST['said'])&&trim($_POST['said'])?trim($_POST['said']):'';
// print_r($_POST);
$status=1;
$start_time=date("Y-m-d H:i:s");
$end_time=date("Y-m-d H:i:s");

$siteUpdte="UPDATE site SET status=0,end_time='$end_time' WHERE sid=$sid";
$siteUpdteResult=$link->prepare($siteUpdte);
$siteUpdteResult->execute();

$siteSql="SELECT place from site where sid=$sid";
$siteSqlResult=$link->prepare($siteSql);
$siteSqlResult->execute();
$siteSqlRows=$siteSqlResult->fetch();

$place2=$siteSqlRows['place'];
$status2=!empty($sid)?'success':'error';

$siteInsert="INSERT INTO site(said,place,location,address,status,start_time,end_time)
VALUES('$said','$place','$location','$address','$status','$start_time','$end_time')";
$siteResult=$link->prepare($siteInsert);
$siteResult->execute();

$lastInsertId=$link->lastinsertid();
$status=$lastInsertId>0?'success':'error';
$arr=array('status'=>$status,'status2'=>$status2,'place2'=>$place2);
echo json_encode($arr);
?>
