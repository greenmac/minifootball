<?php
include_once('link.php');
include_once('function.php');

$PNo=isset($_POST['PNo'])&&trim($_POST['PNo'])?trim($_POST['PNo']):0;
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$raceSqlNote="SELECT rid,r_nid,sid,status,note
  FROM race
  where appear=1 and status=1 and r_nid=$r_nid and sid=$PNo";//"select * from sys_map_area where smcid='". $_POST["CNo"] ."'";
$raceSqlNoteResult=$link->prepare($raceSqlNote);
$raceSqlNoteResult->execute();
$raceSqlNoteRows=$raceSqlNoteResult->fetch();
$raceSqlNoteNums=$raceSqlNoteResult->rowcount();
$raceChk=$raceSqlNoteNums>0?3:0;

echo $raceChk==3?$raceSqlNoteRows['note']:'';
?>
