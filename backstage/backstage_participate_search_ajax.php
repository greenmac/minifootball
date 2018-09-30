<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$keyword=isset($_POST['keyword'])&&trim($_POST['keyword'])?trim($_POST['keyword']):'';

$status=isset($keyword) ? 'success':'error';
$arr=array('status'=>$status);
echo json_encode($arr);
?>
