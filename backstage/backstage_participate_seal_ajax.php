<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$said=isset($_POST['said'])&&trim($_POST['said'])?trim($_POST['said']):0;
$connect_tid=isset($_POST['connect_tid'])&&trim($_POST['connect_tid'])?trim($_POST['connect_tid']):0;
$c_status=isset($_POST['c_status'])&&trim($_POST['c_status'])?trim($_POST['c_status']):0;
$partid=isset($_POST['partid'])&&trim($_POST['partid'])?trim($_POST['partid']):0;
$part_fid=isset($_POST['part_fid'])&&trim($_POST['part_fid'])?trim($_POST['part_fid']):0;
$pid=isset($_POST['pid'])&&trim($_POST['pid'])?trim($_POST['pid']):0;
$end_time=date("Y-m-d H:i:s");

$playerSql="SELECT name_player from player where pid=$pid";
$playerSqlResult=$link->prepare($playerSql);
$playerSqlResult->execute();
$playerSqlRows=$playerSqlResult->fetch();
$name_player=$playerSqlRows['name_player'];

if(empty($c_status))
{
  $playerUpdate="UPDATE player set status=0,end_time='$end_time' where pid=$pid";
  $playerUpdateResult=$link->prepare($playerUpdate);
  $playerUpdateResult->execute();

  $participateUpdate="UPDATE participate set status=0,end_time='$end_time' where pid=$pid";
  $participateUpdateResult=$link->prepare($participateUpdate);
  $participateUpdateResult->execute();

  $participate_finalsUpdate="UPDATE participate_finals set status=0,end_time='$end_time' where pid=$pid";
  $participate_finalsUpdateResult=$link->prepare($participate_finalsUpdate);
  $participate_finalsUpdateResult->execute();
}
elseif(!empty($c_status))
{
  if($c_status==1)
  {
    $participateUpdate="UPDATE participate set status=0,end_time='$end_time' where partid=$partid";
    $participateUpdateResult=$link->prepare($participateUpdate);
    $participateUpdateResult->execute();
  }
  elseif($c_status==2)
  {
    $participate_finalsUpdate="UPDATE participate_finals set status=0,end_time='$end_time' where part_fid=$part_fid";
    $participate_finalsUpdateResult=$link->prepare($participate_finalsUpdate);
    $participate_finalsUpdateResult->execute();
  }
}

$status=!empty($pid) ? 'success':'error';
$arr=array('status'=>$status,'name_player'=>$name_player);
echo json_encode($arr);
?>
