<?php
include_once('link.php');
include_once('function.php');
#區域連動
$sys_map_areaSql="select * from sys_map_area where smcid='". $_POST["CNo"] ."'";
$sys_map_areaSqlResult=$link->prepare($sys_map_areaSql);
$sys_map_areaSqlResult->execute();
$sys_map_areaSqlRows=$sys_map_areaSqlResult->fetchall();
$sys_map_areaSqlRums=$sys_map_areaSqlResult->rowcount();

echo $sys_map_areaSqlRums>0?"<option value=''>請選擇鄉鎮市區</option>":"<option value=''>請選擇鄉鎮市區</option>";
foreach($sys_map_areaSqlRows as $sma)
{
  echo $sys_map_areaSqlRums>0?"<option value='".$sma['smaid']."'>".$sma['area']."</option>":"<option value=''>請選擇鄉鎮市區</option>";
}
?>
