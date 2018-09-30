<?php
include_once('link.php');
include_once('function.php');
$mid=isset($_POST['mid']) && trim($_POST['mid']) ? trim($_POST['mid']):0;
$name_player=isset($_POST['name_player']) && trim($_POST['name_player']) ? trim($_POST['name_player']):0;
$birth=isset($_POST['birth']) && trim($_POST['birth']) ? trim($_POST['birth']):0;
// $birth=strtotime($birth);
$id_card=isset($_POST['id_card']) && trim($_POST['id_card']) ? trim($_POST['id_card']):0;
$name_parents=isset($_POST['name_parents']) && trim($_POST['name_parents']) ? trim($_POST['name_parents']):0;
$mobile=isset($_POST['mobile']) && trim($_POST['mobile']) ? trim($_POST['mobile']):0;
$smcid=!empty($_POST['smcid']) && trim($_POST['smcid']) ? trim($_POST['smcid']):0;
$smaid=!empty($_POST['smaid']) && trim($_POST['smaid']) ? trim($_POST['smaid']):0;
$address=!empty($_POST['address']) && trim($_POST['address']) ? trim($_POST['address']):'';
$clothes_back_num=!empty($_POST['clothes_back_num']) && trim($_POST['clothes_back_num']) ? trim($_POST['clothes_back_num']):'';
$clothes_size=!empty($_POST['clothes_size']) && trim($_POST['clothes_size']) ? trim($_POST['clothes_size']):0;
$status=1;
$start_time=date('Y-m-d H:i:s');
$end_time=date('Y-m-d H:i:s');

$PlayerInsert="INSERT INTO player(mid,status,name_player,birth,id_card,name_parents,mobile,smcid,smaid,address,clothes_back_num,clothes_size,start_time,end_time)
  VALUES ('$mid','$status','$name_player','$birth','$id_card','$name_parents','$mobile','$smcid','$smaid','$address','$clothes_back_num','$clothes_size','$start_time','$end_time')";
$PlayerInsert_Result=$link->prepare($PlayerInsert);
$PlayerInsert_Result->execute();

$InsertID=$link->lastinsertid();
$status=$InsertID>0 ? 'success':'error';
$arr=array('status'=>$status);
echo json_encode($arr);
?>
