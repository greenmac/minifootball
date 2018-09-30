<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$rid=isset($_POST['rid'])&&trim($_POST['rid'])?trim($_POST['rid']):0;
$c_status=isset($_POST['c_status'])&&trim($_POST['c_status'])?trim($_POST['c_status']):0;
$sid=isset($_POST['sid'])&&trim($_POST['sid'])?trim($_POST['sid']):0;
$place=isset($_POST['place'])&&is_array($_POST['place'])?($_POST['place']):'';
$location=isset($_POST['location'])&&is_array($_POST['location'])?($_POST['location']):'';
$address=isset($_POST['address'])&&is_array($_POST['address'])?($_POST['address']):'';
$said=isset($_POST['said'])&&is_array($_POST['said'])? $_POST['said'] :0;
$apply		=isset($_POST['apply']) ? $_POST['apply']:'';
$start_time=date("Y-m-d H:i:s");
$end_time=date("Y-m-d H:i:s");

// pre($_POST);exit;
if(empty($r_nid))
{
  if(!empty($apply))
  {
    foreach($apply as $a=>$a1)
    {
      $sid2=$a1['sid'];
      $said2=$a1['said'];
      $place2=$a1['place'];
      $location2=$a1['location'];
      $address2=$a1['address'];

      $siteUpdate="UPDATE `site` SET `said`='$said2',`place`='$place2',`location`='$location2',`address`='$address2' WHERE sid='$sid2'";
      $siteUpdateResult=$link->prepare($siteUpdate);
      $siteUpdateResult->execute();
    }

    if(!empty($_POST["place"]))
    {
      for($i=0;$i<sizeof($_POST["place"]);$i++)
      {
        $siteInsert="INSERT INTO site(said,place,location,address,status,start_time,end_time)
          VALUES('$said[$i]','$place[$i]','$location[$i]','$address[$i]',3,'$start_time','$end_time')";
        $siteResult=$link->prepare($siteInsert);
        $siteResult->execute();
        // pre($siteInsert);
      }
    }
    $status=!empty($apply) ? 'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
  elseif(empty($apply))
  {
    if(!empty($_POST["place"]))
    {
      for($i=0;$i<sizeof($_POST["place"]);$i++)
      {
        $siteInsert="INSERT INTO site(said,place,location,address,status,start_time,end_time)
          VALUES('$said[$i]','$place[$i]','$location[$i]','$address[$i]',3,'$start_time','$end_time')";
        $siteResult=$link->prepare($siteInsert);
        $siteResult->execute();
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
      $sid2=$a1['sid'];
      $said2=$a1['said'];
      $place2=$a1['place'];
      $location2=$a1['location'];
      $address2=$a1['address'];

      $siteUpdate="UPDATE `site` SET `said`='$said2',`place`='$place2',`location`='$location2',`address`='$address2' WHERE sid='$sid2'";
      $siteUpdateResult=$link->prepare($siteUpdate);
      $siteUpdateResult->execute();
    }

    if(!empty($_POST["place"]))
    {
      for($i=0;$i<sizeof($_POST["place"]);$i++)
      {
        $siteInsert="INSERT INTO site(said,r_nid,place,location,address,status,start_time,end_time)
          VALUES('$said[$i]','$r_nid','$place[$i]','$location[$i]','$address[$i]','$c_status','$start_time','$end_time')";
        $siteResult=$link->prepare($siteInsert);
        $siteResult->execute();

        $lastInsertId=$link->lastinsertid();
        $raceInsert="INSERT INTO `race`(`r_nid`, `sku`, `sid`, `appear`, `status`, `kind`,`start_time`)
         VALUES ('$r_nid','$r_nid','$lastInsertId',1,'$c_status','$c_status','$start_time')";
        $raceInsertSql=$link->prepare($raceInsert);
        $raceInsertSql->execute();
      }
    }
    $status=!empty($apply) ? 'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
  elseif(empty($apply))
  {
    if(!empty($_POST["place"]))
    {
      for($i=0;$i<sizeof($_POST["place"]);$i++)
      {
        $siteInsert="INSERT INTO site(said,r_nid,place,location,address,status,start_time,end_time)
          VALUES('$said[$i]','$r_nid','$place[$i]','$location[$i]','$address[$i]','$c_status','$start_time','$end_time')";
        $siteResult=$link->prepare($siteInsert);
        $siteResult->execute();
        // echo $siteInsert;
        $lastInsertId=$link->lastinsertid();
        $raceInsert="INSERT INTO `race`(`r_nid`, `sku`, `sid`, `appear`, `status`, `kind`,`start_time`)
         VALUES ('$r_nid','$r_nid','$lastInsertId',1,'$c_status','$c_status','$start_time')";
        $raceInsertSql=$link->prepare($raceInsert);
        $raceInsertSql->execute();
      }
    }
    $lastInsertId=$link->lastinsertid();
    $status=$lastInsertId>0?'success':'error';
    $arr=array('status'=>$status);
    echo json_encode($arr);
  }
}
?>
