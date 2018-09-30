<?php
include_once(dirname(dirname(__FILE__)).'/link.php');
include_once(dirname(dirname(__FILE__)).'/function.php');

$card_num=isset($_POST['card_num']) && trim($_POST['card_num']) ? trim($_POST['card_num']):'';
$mid=isset($_POST['mid']) && trim($_POST['mid']) ? trim($_POST['mid']):'';
$pid=isset($_POST['pid']) && trim($_POST['pid']) ? trim($_POST['pid']):'';
$tid=isset($_POST['tid']) && trim($_POST['tid']) ? trim($_POST['tid']):'';
$r_nid=isset($_POST['r_nid']) && trim($_POST['r_nid']) ? trim($_POST['r_nid']):'';
$name_player=isset($_POST['name_player']) && trim($_POST['name_player']) ? trim($_POST['name_player']):'';
$birth=isset($_POST['birth']) && trim($_POST['birth']) ? trim($_POST['birth']):'';
$id_card=isset($_POST['id_card']) && trim($_POST['id_card']) ? trim($_POST['id_card']):'';
$name_parents=isset($_POST['name_parents']) && trim($_POST['name_parents']) ? trim($_POST['name_parents']):'';
$mobile=isset($_POST['mobile']) && trim($_POST['mobile']) ? trim($_POST['mobile']):'';
$smcid=isset($_POST['smcid']) && trim($_POST['smcid']) ? trim($_POST['smcid']):0;
$smaid=isset($_POST['smaid']) && trim($_POST['smaid']) ? trim($_POST['smaid']):0;
$address=isset($_POST['address']) && trim($_POST['address']) ? trim($_POST['address']):'';
$clothes_back_num=isset($_POST['clothes_back_num']) && trim($_POST['clothes_back_num']) ? trim($_POST['clothes_back_num']):'';
$clothes_size=isset($_POST['clothes_size']) && trim($_POST['clothes_size']) ? trim($_POST['clothes_size']):0;
$start_time=date('Y-m-d H:i:s');
$end_time=date('Y-m-d H:i:s');

if($pid>0)
{
  ##球員更新
  $playerUpdate="UPDATE player SET name_player='$name_player',birth='$birth',id_card='$id_card',name_parents='$name_parents',
  mobile='$mobile',smcid='$smcid',smaid='$smaid',address='$address',clothes_back_num='$clothes_back_num',clothes_size='$clothes_size',end_time='$end_time'
  WHERE pid=$pid";
  $playerUpdate_Result=$link->prepare($playerUpdate);
  $playerUpdate_Result->execute();

  $status=$pid>0 ? 'success':'error';
  $arr=array('status'=>$status);
  echo json_encode($arr);
}
elseif($pid==0)
{
  $memberSql="SELECT * from member where card_num='".$card_num."'";
  $memberSqlRsult=$link->prepare($memberSql);
  $memberSqlRsult->execute();
  $memberSqlRows=$memberSqlRsult->fetchall();
  $midInsert=$memberSqlRows[0]['mid'];

  $PlayerInsert="INSERT INTO player(mid,status,name_player,birth,id_card,name_parents,mobile,smcid,smaid,address,clothes_back_num,clothes_size,start_time,end_time)
    VALUES ($midInsert,1,'$name_player','$birth','$id_card','$name_parents','$mobile','$smcid','$smaid','$address','$clothes_back_num','$clothes_size','$start_time','$end_time')";
  $PlayerInsert_Result=$link->prepare($PlayerInsert);
  $PlayerInsert_Result->execute();
  $InsertID=$link->lastinsertid();

  $status=$InsertID>0 ? 'success':'error';
  $arr=array('status'=>$status);
  echo json_encode($arr);
}

?>
