<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):'';
$rid=isset($_POST['rid'])&&trim($_POST['rid'])?trim($_POST['rid']):'';
$c_status=isset($_POST['c_status'])&&trim($_POST['c_status'])?trim($_POST['c_status']):'';
$gid=isset($_POST['gid'])&&trim($_POST['gid'])?trim($_POST['gid']):'';
$age=isset($_POST['age'])&&is_array($_POST['age'])?$_POST['age']:'';
$lowest_birth=isset($_POST['lowest_birth'])&&is_array($_POST['lowest_birth'])?$_POST['lowest_birth']:'';
// $highest_birth=isset($_POST['highest_birth'])&&trim($_POST['highest_birth'])?trim($_POST['highest_birth']):'';
$apply		=isset($_POST['apply']) ? $_POST['apply']:'';
$start_time=date("Y-m-d H:i:s");
$end_time=date("Y-m-d H:i:s");
if(empty($r_nid))
{
  if(!empty($apply))
  {
    foreach($apply as $a=>$a1)
    {
      $gid2=$a1['gid'];
      $age2=$a1['age'];
      $lowest_birth2=$a1['lowest_birth'];

      $groupingUpdate="UPDATE `grouping` SET `age`='$age2',`lowest_birth`='$lowest_birth2' WHERE gid='$gid2'";
      $groupingUpdateResult=$link->prepare($groupingUpdate);
      $groupingUpdateResult->execute();
    }

    if(!empty($_POST["age"]))
    {
      for($i=0;$i<sizeof($_POST["age"]);$i++)
      {
        //$birth=strtotime($lowest_birth[$i]);
        $groupingInsert="INSERT INTO grouping(age,lowest_birth,status,start_time,end_time)
          VALUES('$age[$i]','$lowest_birth[$i]',3,'$start_time','$end_time')";
        $groupingResult=$link->prepare($groupingInsert);
        $groupingResult->execute();
      }
    }

    $status=!empty($apply) ? 'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
  elseif(empty($apply))
  {
    if(!empty($_POST["age"]))
    {
      for($i=0;$i<sizeof($_POST["age"]);$i++)
      {
        //$birth=strtotime($lowest_birth[$i]);
        $groupingInsert="INSERT INTO grouping(age,lowest_birth,status,start_time,end_time)
          VALUES('$age[$i]','$lowest_birth[$i]',3,'$start_time','$end_time')";
        $groupingResult=$link->prepare($groupingInsert);
        $groupingResult->execute();
      }
    }
    $lastInsertId=$link->lastinsertid();
    $status=$lastInsertId>0?'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
}
elseif(!empty($r_nid))
{
  if(!empty($apply))
  {
    foreach($apply as $a=>$a1)
    {
      $gid2=$a1['gid'];
      $age2=$a1['age'];
      $lowest_birth2=$a1['lowest_birth'];

      $groupingUpdate="UPDATE `grouping` SET `age`='$age2',`lowest_birth`='$lowest_birth2' WHERE gid='$gid2'";
      $groupingUpdateResult=$link->prepare($groupingUpdate);
      $groupingUpdateResult->execute();
    }

    if(!empty($_POST["age"]))
    {
      for($i=0;$i<sizeof($_POST["age"]);$i++)
      {
        //$birth=strtotime($lowest_birth[$i]);
        $groupingInsert="INSERT INTO grouping(r_nid,age,lowest_birth,status,start_time,end_time)
          VALUES('$r_nid','$age[$i]','$lowest_birth[$i]',1,'$start_time','$end_time')";
        $groupingResult=$link->prepare($groupingInsert);
        $groupingResult->execute();
      }
    }

    $status=!empty($apply) ? 'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
  elseif(empty($apply))
  {
    if(!empty($_POST["age"]))
    {
      for($i=0;$i<sizeof($_POST["age"]);$i++)
      {
        //$birth=strtotime($lowest_birth[$i]);
        $groupingInsert="INSERT INTO grouping(r_nid,age,lowest_birth,status,start_time,end_time)
          VALUES('$r_nid','$age[$i]','$lowest_birth[$i]',1,'$start_time','$end_time')";
        $groupingResult=$link->prepare($groupingInsert);
        $groupingResult->execute();
      }
    }
    $lastInsertId=$link->lastinsertid();
    $status=$lastInsertId>0?'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
}
?>
