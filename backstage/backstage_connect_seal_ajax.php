<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$cid=isset($_POST['cid']) && trim($_POST['cid']) ? trim($_POST['cid']):'';
$connect_tid=isset($_POST['connect_tid']) && trim($_POST['connect_tid']) ? trim($_POST['connect_tid']):'';
$c_status=isset($_POST['c_status']) && trim($_POST['c_status']) ? trim($_POST['c_status']):'';
$end_time=date('Y-m-d H:i:s');

$connectSql="SELECT team_name from connect where cid=$cid and tid=$connect_tid";
$connectSqlResult=$link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows=$connectSqlResult->fetchall();
$team_name=$connectSqlRows[0]['team_name'];

##封存隊伍時,會同時把connect(status),ticket(status,reveal)三個欄位狀態轉為0,要叫回來需要三個狀態都改為1
$connectUpdate="UPDATE connect SET reveal=0,status=0,end_time='$end_time' WHERE cid=$cid and tid=$connect_tid";
$connectUpdateResult=$link->prepare($connectUpdate);
$connectUpdateResult->execute();

$ticketUpdate="UPDATE ticket SET status=0,end_time='$end_time' WHERE tid=$connect_tid";
$ticketUpdateResult=$link->prepare($ticketUpdate);
$ticketUpdateResult->execute();

$status=!empty($connect_tid)?'success':'error';
$arr=array('status'=>$status,'team_name'=>$team_name);
echo json_encode($arr);
?>
