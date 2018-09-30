<?php
include_once('link.php');
include_once('function.php');

$PNo=isset($_POST['PNo'])&&trim($_POST['PNo'])?trim($_POST['PNo']):0;
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;

$raceSqlRaceDate="SELECT rid,r_nid,sid,status,race_date
  FROM race
  where appear=1 and status=1 and r_nid=$r_nid and sid=$PNo";//"select * from sys_map_area where smcid='". $_POST["CNo"] ."'";
$raceSqlRaceDateResult=$link->prepare($raceSqlRaceDate);
$raceSqlRaceDateResult->execute();
$raceSqlRaceDateRows=$raceSqlRaceDateResult->fetch();
$raceSqlRaceDateNums=$raceSqlRaceDateResult->rowcount();
$raceChk=$raceSqlRaceDateNums>0?2:0;

echo $raceChk==2?$raceSqlRaceDateRows['race_date']:'';
?>
