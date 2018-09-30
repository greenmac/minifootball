<?php
include_once('link.php');
include_once('function.php');
$pid=isset($_POST['pid']) && trim($_POST['pid']) ? trim($_POST['pid']):'';
$name_player=isset($_POST['name_player']) && trim($_POST['name_player']) ? trim($_POST['name_player']):'';
$birth=isset($_POST['birth']) && trim($_POST['birth']) ? trim($_POST['birth']):'';
// $birth=strtotime($birth);
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

#球員更新
$playerUpdate="UPDATE player SET name_player='$name_player',birth='$birth',id_card='$id_card',name_parents='$name_parents',
mobile='$mobile',smcid='$smcid',smaid='$smaid',address='$address',clothes_back_num='$clothes_back_num',clothes_size='$clothes_size',end_time='$end_time'
WHERE pid=$pid";
$playerUpdate_Result=$link->prepare($playerUpdate);
$playerUpdate_Result->execute();

$status=!empty($pid) ? 'success':'error';
$arr=array('status'=>$status);
echo json_encode($arr);
?>
