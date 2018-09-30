<?php
include_once('link.php');
include_once('function.php');
$connect_tid=isset($_POST['connect_tid']) && trim($_POST['connect_tid']) ? trim($_POST['connect_tid']):0;
// $c_status=isset($_POST['c_status']) && trim($_POST['c_status']) ? trim($_POST['c_status']):'';
$cid=isset($_POST['cid']) && trim($_POST['cid']) ? trim($_POST['cid']):0;
$mid=isset($_POST['mid']) && trim($_POST['mid']) ? trim($_POST['mid']):0;
$tid=isset($_POST['tid']) && trim($_POST['tid']) ? trim($_POST['tid']):0;
$rid=isset($_POST['rid']) && trim($_POST['rid']) ? trim($_POST['rid']):0;
$r_nid=isset($_POST['r_nid']) && trim($_POST['r_nid']) ? trim($_POST['r_nid']):0;
$sid=isset($_POST['sid']) && trim($_POST['sid']) ? trim($_POST['sid']):0;
$gid=isset($_POST['gid']) && trim($_POST['gid']) ? trim($_POST['gid']):0;
$status=isset($_POST['status']) && trim($_POST['status']) ? trim($_POST['status']):0;
$team_name=isset($_POST['team_name']) && trim($_POST['team_name']) ? trim($_POST['team_name']):'';
$leader_name=isset($_POST['leader_name']) && trim($_POST['leader_name']) ? trim($_POST['leader_name']):'';
$leader_mobile=isset($_POST['leader_mobile']) && trim($_POST['leader_mobile']) ? trim($_POST['leader_mobile']):'';
$leader_email=isset($_POST['leader_email']) && trim($_POST['leader_email']) ? trim($_POST['leader_email']):'';
$coach_name=isset($_POST['coach_name']) && trim($_POST['coach_name']) ? trim($_POST['coach_name']):'';
$coach_mobile=isset($_POST['coach_mobile']) && trim($_POST['coach_mobile']) ? trim($_POST['coach_mobile']):'';
$coach_email=isset($_POST['coach_email']) && trim($_POST['coach_email']) ? trim($_POST['coach_email']):'';
$supervise_name=isset($_POST['supervise_name']) && trim($_POST['supervise_name']) ? trim($_POST['supervise_name']):'';
$supervise_mobile=isset($_POST['supervise_mobile']) && trim($_POST['supervise_mobile']) ? trim($_POST['supervise_mobile']):'';
$supervise_email=isset($_POST['supervise_email']) && trim($_POST['supervise_email']) ? trim($_POST['supervise_email']):'';
$start_time=date('Y-m-d H:i:s');
$end_time=date('Y-m-d H:i:s');

if(empty($connect_tid))
{
  $ticketInConnect="SELECT * from connect where tid=$tid";
  $ticketInConnectResult=$link->prepare($ticketInConnect);
  $ticketInConnectResult->execute();
  $ticketInConnectRows=$ticketInConnectResult->fetchall();

  if(empty($ticketInConnectRows))
  {
    $connectInsert="INSERT INTO connect(mid,tid,rid,r_nid,sid,gid,status,team_name,leader_name,leader_mobile,leader_email,
      coach_name,coach_mobile,coach_email,supervise_name,supervise_mobile,supervise_email,start_time,end_time)
      VALUES ('$mid','$tid','$rid','$r_nid','$sid','$gid','$status','$team_name','$leader_name','$leader_mobile','$leader_email',
      '$coach_name','$coach_mobile','$coach_email','$supervise_name','$supervise_mobile','$supervise_email',
      '$start_time','$end_time')";
    $connectInsert_Result=$link->prepare($connectInsert);
    $connectInsert_Result->execute();

    $InsertID=$link->lastInsertId();
    $status=$InsertID>0 ? 'success':'error';
    $arr=array('status'=>$status);
  }
  elseif(!empty($ticketInConnectRows))
  {
    $arr=array('status'=>'jump');
  }
  echo json_encode($arr);
}
elseif(!empty($connect_tid))
{
  if($status==1)
  {
    $connectUpdate="UPDATE connect SET rid='$rid',r_nid='$r_nid',sid='$sid',gid='$gid',status='$status',team_name='$team_name',
      leader_name='$leader_name',leader_mobile='$leader_mobile',leader_email='$leader_email',
      coach_name='$coach_name',coach_mobile='$coach_mobile',coach_email='$coach_email',
      supervise_name='$supervise_name',supervise_mobile='$supervise_mobile',supervise_email='$supervise_email',
      end_time='$end_time' WHERE cid=$cid and status=1";
  }
  elseif($status==2)
  {
    $connectUpdate="UPDATE connect SET rid='$rid',r_nid='$r_nid',sid='$sid',gid='$gid',status='$status',
      leader_name='$leader_name',leader_mobile='$leader_mobile',leader_email='$leader_email',
      coach_name='$coach_name',coach_mobile='$coach_mobile',coach_email='$coach_email',
      supervise_name='$supervise_name',supervise_mobile='$supervise_mobile',supervise_email='$supervise_email',
      end_time='$end_time' WHERE cid=$cid and status=2";
  }
  $connectUpdateResult=$link->prepare($connectUpdate);
  $connectUpdateResult->execute();

  $status=$cid>0?'success':'error';
  $arr=array('status'=>$status);
  echo json_encode($arr);
}
?>
