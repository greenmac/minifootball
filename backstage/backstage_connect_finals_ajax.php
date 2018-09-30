<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$r_nid=isset($_POST['r_nid']) && trim($_POST['r_nid']) ? trim($_POST['r_nid']):'';
$sid=isset($_POST['sid']) && trim($_POST['sid']) ? trim($_POST['sid']):'';
$cid=isset($_POST['cid']) && trim($_POST['cid']) ? trim($_POST['cid']):'';
$connect_tid=isset($_POST['connect_tid']) && trim($_POST['connect_tid']) ? trim($_POST['connect_tid']):'';
$c_status=isset($_POST['c_status']) && trim($_POST['c_status']) ? trim($_POST['c_status']):'';
$end_time=date('Y-m-d H:i:s');

$connectSql="SELECT team_name from connect where cid=$cid and tid=$connect_tid";
$connectSqlResult=$link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows=$connectSqlResult->fetchall();
$team_name=$connectSqlRows[0]['team_name'];
$start_time=date('Y-m-d H:i:s');
//$end_time=date('Y-m-d H:i:s');

$raceSql="SELECT * from race where appear=1 and r_nid=$r_nid and status=2";
$raceSqlResult=$link->prepare($raceSql);
$raceSqlResult->execute();
$raceSqlRows=$raceSqlResult->fetchall();
$rid=!empty($raceSqlRows[0]['rid'])?$raceSqlRows[0]['rid']:0;
$sid=!empty($raceSqlRows[0]['sid'])?$raceSqlRows[0]['sid']:0;
$calculate=!empty($raceSqlRows[0]['calculate'])?$raceSqlRows[0]['calculate']:0;

if($c_status==1)
{
  ##先把原本的連絡資訊轉為reveal=0
  $connectUpdate="UPDATE connect SET reveal=0,end_time='$end_time' WHERE status=1 and tid=$connect_tid";
  $connectUpdateResult=$link->prepare($connectUpdate);
  $connectUpdateResult->execute();

  $connectFinals="SELECT * from connect where tid=$connect_tid and kind=2";
  $connectFinalsResult=$link->prepare($connectFinals);
  $connectFinalsResult->execute();
  $connectFinalsRows=$connectFinalsResult->fetchall();
  if(empty($connectFinalsRows))
  {
    ##如果要保持預賽隊伍要留資料
    $connect_finalsInsert="
    INSERT INTO connect(mid,tid,rid,r_nid,sid,gid,status,kind,calculate,team_name,leader_name,leader_mobile,leader_email,coach_name,coach_mobile,coach_email,supervise_name,supervise_mobile,supervise_email,start_time)
    SELECT mid,tid,'$rid',r_nid,'$sid',gid,2,2,'$calculate',team_name,leader_name,leader_mobile,leader_email,coach_name,coach_mobile,coach_email,supervise_name,supervise_mobile,supervise_email,'$start_time' from connect
    where cid=$cid
    ";
    $connect_finalsInsertResult=$link->prepare($connect_finalsInsert);
    $connect_finalsInsertResult->execute();
    $c_statudMessage="finals";//轉為決賽
  }
  elseif(!empty($connectFinalsRows))
  {
    $connectUpdate3="UPDATE connect set rid=$rid,status=2,calculate=$calculate where tid=$connect_tid and kind=2";
    $connectUpdate3Result=$link->prepare($connectUpdate3);
    $connectUpdate3Result->execute();
    $c_statudMessage="finals";//轉為決賽
  }

  ##原本的設定,直接將隊伍轉為status=2(決賽場地設定為之前同一場地比賽)
  // $connectUpdate="UPDATE connect SET rid=$rid,status=2,calculate=$calculate,end_time='$end_time' WHERE cid=$cid and tid=$connect_tid";
  // $connectUpdateResult=$link->prepare($connectUpdate);
  // $connectUpdateResult->execute();
  // $c_statudMessage="finals";//轉為決賽

  $participateSql="SELECT * from participate where tid=$connect_tid and status=1";
  $participateSqlResult=$link->prepare($participateSql);
  $participateSqlResult->execute();
  $participateSqlRows=$participateSqlResult->fetchall();

  foreach($participateSqlRows as $par1)
  {
    $mid1=$par1['mid'];
    $tid1=$par1['tid'];
    $r_nid1=$par1['r_nid'];
    $pid1=$par1['pid'];

    $participate_finalsInsert="INSERT INTO participate_finals(mid,tid,r_nid,pid,status,start_time)
      VALUES ($mid1,$tid1,$r_nid1,$pid1,1,'$start_time')";
    $participate_finalsInsertResult=$link->prepare($participate_finalsInsert);
    $participate_finalsInsertResult->execute();
  }
}
elseif($c_status==2)
{
  // echo "bbb";exit;
  $connectUpdate="UPDATE connect SET reveal=1,end_time='$end_time' WHERE status=1 and tid=$connect_tid";
  $connectUpdateResult=$link->prepare($connectUpdate);
  $connectUpdateResult->execute();

  $connectUpdate2="UPDATE connect SET rid=$rid,status=0,end_time='$end_time' WHERE status=2 and tid=$connect_tid";
  $connectUpdate2Result=$link->prepare($connectUpdate2);
  $connectUpdate2Result->execute();

  $c_statudMessage="prelims";//轉為預賽


  ##原本的設定,直接將隊伍轉為status=2(決賽場地設定為之前同一場地比賽),搜索用
  // $raceSql="SELECT * from race where r_nid=$r_nid and tid=$connect_tid and status=1";
  // $raceSqlResult=$link->prepare($raceSql);
  // $raceSqlResult->execute();
  // $raceSqlRows=$raceSqlResult->fetchall();
  // $rid=$raceSqlRows[0]['rid'];
  // $calculate=$raceSqlRows[0]['calculate'];

  // $connectSqlSelect="SELECT * from connect where cid=$cid and tid=$connect_tid";
  // $connectSqlSelectResult=$link->prepare($connectSqlSelect);
  // $connectSqlSelectResult->execute();
  // $connectSqlSelectRows=$connectSqlSelectResult->fetchall();


  $participate_finalsSql="SELECT * from participate_finals where tid=$connect_tid and status=1";
  $participate_finalsSqlResult=$link->prepare($participate_finalsSql);
  $participate_finalsSqlResult->execute();
  $participate_finalsSqlRows=$participate_finalsSqlResult->fetchall();

  foreach($participate_finalsSqlRows as $par2)
  {
    $mid2=$par2['mid'];
    $tid2=$par2['tid'];

    $participate_finalsUpdate="UPDATE participate_finals set status=0 where mid=$mid2 and tid=$tid2";
    $participate_finalsUpdateResult=$link->prepare($participate_finalsUpdate);
    $participate_finalsUpdateResult->execute();
  }
}
// exit;
$status=!empty($connect_tid)?'success':'error';
$arr=array('status'=>$status,'team_name'=>$team_name,'c_statudMessage'=>$c_statudMessage);
echo json_encode($arr);
?>
