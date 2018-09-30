<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$gid=isset($_POST['gid'])&&trim($_POST['gid'])?trim($_POST['gid']):'';
$end_time=date("Y-m-d H:i:s");

$connectSql="SELECT * from connect where gid=$gid";
$connectSqlResult=$link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows=$connectSqlResult->fetchall();
if(!empty($connectSqlRows))
{
  $arr=array('status3'=>'have');
}
elseif(empty($connectSqlRows))
{
  $groupingUpdte="UPDATE grouping SET status=0,end_time='$end_time' WHERE gid=$gid";
  $groupingUpdteResult=$link->prepare($groupingUpdte);
  $groupingUpdteResult->execute();

  $groupingSql="SELECT age from grouping where gid=$gid";
  $groupingSqlResult=$link->prepare($groupingSql);
  $groupingSqlResult->execute();
  $groupingSqlRows=$groupingSqlResult->fetch();

  $age2=$groupingSqlRows['age'];
  $status2=!empty($gid)?'success':'error';

  $arr=array('status2'=>$status2,'age2'=>$age2);
}
echo json_encode($arr);
?>
