<?php
include_once('link.php');
include_once('function.php');
$tid=isset($_POST['tid']) && trim($_POST['tid']) ? trim($_POST['tid']):0;
$pid=isset($_POST['pid']) && trim($_POST['pid']) ? trim($_POST['pid']):0;
$rid=isset($_POST['rid'])&&trim($_POST['rid'])?trim($_POST['rid']):0;
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$partid=isset($_POST['partid'])&&trim($_POST['partid'])?trim($_POST['partid']):0;
$c_status=isset($_POST['c_status']) && trim($_POST['c_status']) ? trim($_POST['c_status']):0;
$end_time=date('Y-m-d H:i:s');
// pre($tid);exit;

if($c_status==1)
{
  $playerSql="SELECT name_player from player where pid=$pid";
  $playerSqlResult=$link->prepare($playerSql);
  $playerSqlResult->execute();
  $playerSqlRows=$playerSqlResult->fetch();

  $participateUpdate="UPDATE participate SET status=0,end_time='$end_time' WHERE partid=$partid";
  $participateUpdateResult=$link->prepare($participateUpdate);
  $participateUpdateResult->execute();

  $status=isset($pid) ? 'success':'error';
  $name_player=$playerSqlRows['name_player'];
  $arr=array('status'=>$status,'name_player'=>$name_player);
  echo json_encode($arr);
}
elseif($c_status==2)
{
  $playerSql="SELECT name_player from player where pid=$pid";
  $playerSqlResult=$link->prepare($playerSql);
  $playerSqlResult->execute();
  $playerSqlRows=$playerSqlResult->fetch();

  $participate_finalsUpdate="UPDATE participate_finals SET status=0,end_time='$end_time' WHERE part_fid=$partid";
  $participate_finalsResult=$link->prepare($participate_finalsUpdate);
  $participate_finalsResult->execute();

  $participatePeople="SELECT
  participate.partid,participate.mid,participate.tid,participate.r_nid,participate.participate_status,participate.pid,
  participate_finals.part_fid,participate_finals.participate_finals_status,participate_finals.pid
  from
  (
   select partid,mid,tid,r_nid,pid,status as participate_status
   FROM participate
   where tid=$tid and status=1
  )participate
  inner join
  (
   SELECT part_fid,mid,tid,r_nid,pid,status as participate_finals_status
   FROM participate_finals
   where tid=$tid and status=1
   group by pid
  )participate_finals
  on participate.pid=participate_finals.pid
  order by participate.pid desc
  ";
  // pre($participatePeople);exit;
  $participatePeopleResult=$link->prepare($participatePeople);
  $participatePeopleResult->execute();
  $participatePeopleRows=$participatePeopleResult->fetchall();
  $participatePeopleRums=$participatePeopleResult->rowcount();

  if($participatePeopleRums<7)
  {
    $participate_finalsUpdate="UPDATE participate_finals SET status=1,end_time='$end_time' WHERE part_fid=$partid";
    $participate_finalsResult=$link->prepare($participate_finalsUpdate);
    $participate_finalsResult->execute();

    $arr=array('noremove'=>'noremove');
    echo json_encode($arr);
  }
  else
  {
    $participate_finalsNewOne="SELECT *
    FROM
    (
      SELECT *
      FROM participate_finals
      WHERE status=1 and tid=$tid
    )participate_finals
    left join
    (
      SELECT *
      FROM participate
      WHERE status=1 and tid=$tid
    )participate
    on participate_finals.pid=participate.pid
    where participate.partid is null";
    $participate_finalsNewOneResult=$link->prepare($participate_finalsNewOne);
    $participate_finalsNewOneResult->execute();
    $participate_finalsNewOneRows=$participate_finalsNewOneResult->fetchall();
    $participate_finalsNewOneRums=$participate_finalsNewOneResult->rowcount();

    ##檢查connect['calculate']數字多少
    $connectCalculate="SELECT * from connect where tid=$tid and status=2";
    $connectCalculateResult=$link->prepare($connectCalculate);
    $connectCalculateResult->execute();
    $connectCalculateRows=$connectCalculateResult->fetchall();
    $connectCalculateRid=$connectCalculateRows[0]['rid'];
    $connectCalculateOld=$connectCalculateRows[0]['calculate'];
    // pre($connectCalculateRows);exit;

    ##檢查對應比賽的可更替球員人數是多少
    $raceSqlCalculate="SELECT * from race where rid=$connectCalculateRid";
    $raceSqlCalculateResult=$link->prepare($raceSqlCalculate);
    $raceSqlCalculateResult->execute();
    $raceSqlCalculateRows=$raceSqlCalculateResult->fetchall();
    $raceSqlCalculateRowsCalculate=$raceSqlCalculateRows[0]['calculate'];

    ##更新更替球員人次
    $connectCalculateUpdate="UPDATE connect set calculate=($raceSqlCalculateRowsCalculate-$participate_finalsNewOneRums) where tid=$tid and status=2";
    // pre($connectCalculateUpdate);exit;
    $connectCalculateUpdateResult=$link->prepare($connectCalculateUpdate);
    $connectCalculateUpdateResult->execute();
    // $connectCalculateUpdateRows=$connectCalculateUpdateResult->fetchall();
    // echo $connectCalculateUpdate;exit;
    $status=isset($pid) ? 'success':'error';
    $name_player=$playerSqlRows['name_player'];
    $arr=array('status'=>$status,'name_player'=>$name_player);
    echo json_encode($arr);
  }
}
?>
