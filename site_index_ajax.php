<?php
include_once('link.php');
include_once('function.php');
$sid=isset($_POST['sid'])&&trim($_POST['sid'])?trim($_POST['sid']):'';
$tid=isset($_POST['tid'])&&trim($_POST['tid'])?trim($_POST['tid']):'';
$gid=isset($_POST['gid'])&&trim($_POST['gid'])?trim($_POST['gid']):'';
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):'';
$c_status=isset($_POST['c_status'])&&trim($_POST['c_status'])?trim($_POST['c_status']):'';

if(!empty($r_nid)){
	$groupUP="update `connect` set `gid`=$gid where `r_nid`=$r_nid and `tid`=$tid and status>0";
	$groupResult=$link->prepare($groupUP);
	$groupResult->execute();
}

$raceSql="SELECT * from race where appear=1 and r_nid=$r_nid and sid=$sid and status=$c_status";
$raceSqlResult=$link->prepare($raceSql);
$raceSqlResult->execute();
$raceSqlRows=$raceSqlResult->fetch();
$rid=$raceSqlRows['rid'];
// pre($raceSql);exit;

$status=!empty($sid)&&!empty($gid)&&!empty($rid)?'success':'error';
$arr=array('status'=>$status,'sid'=>$sid,'gid'=>$gid,'rid'=>$rid);
echo json_encode($arr);
?>
