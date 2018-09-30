<?php
include_once('link.php');
header("Content-Type:text/html; charset=utf-8");

function pre($pre){
  echo '<pre>';
  print_r($pre);
  echo '</pre>';
}
##用function刪除,相同功能寫一次後呼叫出
// class demo
// {
//   function r_nidDe($table,$r_nid,$link)
//   {
//     $sql="DELETE from $table where r_nid in ($r_nid)";
//     $sqlResult=$link->prepare($sql);
//     $sqlResult->execute();
//     pre($sql);
//   }
// }
// $demo=new demo;
// $demo->r_nidDe('race_name','150,151,166',$link);
// $demo->r_nidDe('race','150,151,166',$link);
// $demo->r_nidDe('site','150,151,166',$link);
// $demo->r_nidDe('grouping','150,151,166',$link);

##陣列重新排序(升序)
// $numbers = array(4, 6, 2, 22, 11);
// sort($numbers);
// echo $numbers[0];
?>
