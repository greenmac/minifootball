<?php
include_once('link.php');
include_once('function.php');

$PNo=isset($_POST['PNo'])&&trim($_POST['PNo'])?trim($_POST['PNo']):0;
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$siteSql="SELECT *
  FROM site
  where sid=$PNo";//"select * from sys_map_area where smcid='". $_POST["CNo"] ."'";
$siteSqlResult=$link->prepare($siteSql);
$siteSqlResult->execute();
$siteSqlRows=$siteSqlResult->fetch();
$siteSqlRums=$siteSqlResult->rowcount();
$raceChk=$siteSqlRums>0?4:0;

echo $raceChk==4?$siteSqlRows['address']:'';
?>
