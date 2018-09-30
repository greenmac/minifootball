<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$r_nid=isset($_POST['r_nid']) && trim($_POST['r_nid']) ? trim($_POST['r_nid']):'';
$c_status=isset($_POST['c_status']) && trim($_POST['c_status']) ? trim($_POST['c_status']):'';
$end_time=date('Y-m-d H:i:s');

$race_nameSql="SELECT name from race_name where r_nid=$r_nid";
$race_nameSqlResult=$link->prepare($race_nameSql);
$race_nameSqlResult->execute();
$race_nameSqlRows=$race_nameSqlResult->fetchall();
$name=$race_nameSqlRows[0]['name'];

$race_nameSqlUpdate="UPDATE race SET status=0,end_time='$end_time' WHERE r_nid=$r_nid and status=$c_status";
$race_nameSqlUpdateResult=$link->prepare($race_nameSqlUpdate);
$race_nameSqlUpdateResult->execute();

$status=!empty($r_nid)?'success':'error';
$arr=array('status'=>$status,'name'=>$name);
echo json_encode($arr);
?>
