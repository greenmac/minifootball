<?php
include_once(dirname(dirname(__file__)).'/link.php');
include_once(dirname(dirname(__file__)).'/function.php');

$race_name	=isset($_POST['race_name']) && trim($_POST['race_name']) ? trim($_POST['race_name']):'';
$sku		=isset($_POST['sku']) && trim($_POST['sku']) ? trim($_POST['sku']):'';
$apply		=isset($_POST['apply']) ? $_POST['apply']:'';
$apply2=isset($_POST['apply2']) ? $_POST['apply2']:'';
$apply3=isset($_POST['apply3']) ? $_POST['apply3']:'';
$site_check	=isset($_POST['site_check']) ? $_POST['site_check']:"";
$c_status=isset($_POST['c_status'])&&trim($_POST['c_status'])?trim($_POST['c_status']):0;
$r_nid=isset($_POST['r_nid'])&&trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$act=isset($_POST['act']) && trim($_POST['act']) ? trim($_POST['act']):"";
$start_time=time();
$arr=array();

if(!empty($act)&&$act=="check_finals")
{
	$siteSql="SELECT count(`cid`) as sCount from `connect` where `r_nid`=$r_nid and `status`=2";
	$siteSqlResult=$link->prepare($siteSql);
	$siteSqlResult->execute();
	$siteSqlRows=$siteSqlResult->fetchall();

	$sCount=isset($siteSqlRows[0]["sCount"]) ? $siteSqlRows[0]["sCount"]:0;
	$arr=array('sCount'=>$sCount);
	echo json_encode($arr);
}
elseif(!empty($act)&&$act=="check_SiteAge")
{
	if(!empty($r_nid))
	{
		$siteCheck="SELECT * from site where r_nid=$r_nid";
		$siteCheckResult=$link->prepare($siteCheck);
		$siteCheckResult->execute();
		$siteCheckRows=$siteCheckResult->fetchall();
	}
	elseif(empty($r_nid))
	{
		$siteSql='SELECT * from (select count(`sid`) as sCount from `site` where `status`=3 ) s, (select count(`gid`) as gCount from `grouping` where status=3 ) g ';
		$siteSqlResult=$link->prepare($siteSql);
		$siteSqlResult->execute();
		$siteSqlRows=$siteSqlResult->fetchall();
		$sCount=isset($siteSqlRows[0]["sCount"]) ? $siteSqlRows[0]["sCount"]:0;
		$gCount=isset($siteSqlRows[0]["gCount"]) ? $siteSqlRows[0]["gCount"]:0;
	}

	$status=!empty($siteCheckRows) ? 'success' : ($sCount==0) ? 's':($gCount==0 ? 'g':"success");
	if(!empty($r_nid)){$status="success";}
	$arr=array('status'=>$status);
	echo json_encode($arr);
}
else
{
	if(empty($c_status))
	{
		$raceNameInsert="INSERT INTO `race_name`( `name`, `sku`, `status`, `start_time`) VALUES ('$race_name','$sku',1, FROM_UNIXTIME(".time()."))";
		$raceNameInsert_Result=$link->prepare($raceNameInsert);
		$raceNameInsert_Result->execute();
		$InsertID=$link->lastInsertId();

		if($InsertID>0&&!empty($apply))
		{
			$sqlCloump='';
			foreach($apply as $k=>$v)
			{
				$sid=$v['sid'];
				$start_time=time();
				$gid=isset($v["gid"]["check"]) ? implode(',',$v["gid"]["check"]):0;
				// $gid=isset($v["gid"]["check"]) ?$v["gid"]["check"]:'';
				$race_date =$v["race_date"];
				$begin_date=$v["begin_date"];
				$begin_hour=$v["begin_hour"];
				$begin_minutes=$v["begin_minutes"];
				// $begin_date=strtotime($begin_date $begin_hour:$begin_minutes);
				// echo $begin_date;exit;
				$final_date=$v["final_date"];
				$final_hour=$v["final_hour"];
				$final_minutes=$v["final_minutes"];
				$note=$v["note"];
				$appear=in_array($sid,$site_check) ? 1:0;

				$sqlCloump.='("'.$InsertID.'","'.$InsertID.'",'.$sid.','.$appear.',1,1,"'.$race_date.'","'.$gid.'","'.$begin_date.'","'.$begin_hour.'","'.$begin_minutes.'","'.$final_date.'","'.$final_hour.'","'.$final_minutes.'","'.$note.'",FROM_UNIXTIME('.$start_time.')),';
			}
			$raceListInsert='INSERT INTO `race`( `r_nid`, `sku`, `sid`, `appear`, `status`, `kind`, `race_date`, `gid`, `begin_date`, `begin_hour`, `begin_minutes`, `final_date`, `final_hour`, `final_minutes`, `note`, `start_time`) values '.rtrim($sqlCloump,',');
			$raceListInsert_Result=$link->prepare($raceListInsert);
			$raceListInsert_Result->execute();
			$raceListID=$link->lastInsertId();

			$status=$raceListID>0 ? 'success':'error';

			$sqlCloump2='';
			foreach($apply as $k2=>$v2)
			{
				$sid2=$v2["sid"];
				// $gid2=isset($v2["gid"]["check"]) ? implode(',',$v2["gid"]["check"]):'';
				$calculate2 =isset($v2["calculate"]) ? $v2["calculate"]:0;
				$race_date2 =$v2["race_date"];
				// $begin_date2=$v2["begin_date"];
				// $begin_hour2=$v2["begin_hour"];
				// $begin_minutes2=$v2["begin_minutes"];
				// $final_date2=$v2["final_date"];
				// $final_hour2=$v2["final_hour"];
				// $final_minutes2=$v2["final_minutes"];
				// $note2=$v2["note"];
				$start_time2=time();
				$appear=in_array($sid2,$site_check) ? 1:0;
				// $sqlCloump2.='("'.$InsertID.'","'.$InsertID.'",'.$sid2.','.$appear.',2,2,"'.$calculate2.'","'.$race_date2.'","'.$gid2.'","'.$note2.'",FROM_UNIXTIME('.$start_time2.')),';
				$sqlCloump2.='("'.$InsertID.'","'.$InsertID.'",0,2,2,"'.$calculate2.'","'.$race_date2.'",FROM_UNIXTIME('.$start_time2.')),';
			}
			// $raceListInsert2='INSERT INTO `race`( `r_nid`, `sku`, `sid`, `appear`, `status`, `kind`, `calculate`, `race_date`, `gid`, `note`, `start_time`) values '.rtrim($sqlCloump2,',');
			$raceListInsert2='INSERT INTO `race`( `r_nid`, `sku`, `appear`, `status`, `kind`, `calculate`, `race_date`, `start_time`) values '.rtrim($sqlCloump2,',');
			$raceListInsert_Result2=$link->prepare($raceListInsert2);
			$raceListInsert_Result2->execute();
			$raceListID2=$link->lastInsertId();

			if(!empty($apply2))
			{
				foreach($apply2 as $x=>$x1)
				{
					$apply2Sid=$x1['sid'];

					$siteUpdate='';
					$siteUpdate="UPDATE `site` SET `status`=1,`r_nid`='$InsertID' WHERE sid=$apply2Sid";
					$siteUpdateReault=$link->prepare($siteUpdate);
					$siteUpdateReault->execute();
				}
			}

			if(!empty($apply3))
			{
				foreach($apply3 as $y=>$y1)
				{
					$apply3Gid=$y1['gid'];

					$groupingUpdate='';
					$groupingUpdate="UPDATE `grouping` SET `status`=1,`r_nid`='$InsertID' WHERE gid=$apply3Gid";
					$groupingUpdateResult=$link->prepare($groupingUpdate);
					$groupingUpdateResult->execute();
				}
			}
			$status2=$raceListID2>0 ? 'success':'error';
			$arr=array('status'=>$status,'status2'=>$status2);
		}
		echo json_encode($arr);
	}
	elseif(!empty($c_status))//有$c_status編輯
	{
		$raceNameUpdate="UPDATE race_name SET name='$race_name',sku='$sku' WHERE r_nid=$r_nid";
		$raceNameUpdateResult=$link->prepare($raceNameUpdate);
		$raceNameUpdateResult->execute();

		//$sqlCloump='';
		foreach($apply as $k3=>$v3)
		{
			$rid=isset($v3["rid"]) ? $v3["rid"]:0;
			$sid=isset($v3["sid"]) ? $v3["sid"]:0;
			$gid=isset($v3["gid"]["check"]) ? implode(',',$v3["gid"]["check"]):0;
			$race_date =isset($v3["race_date"]) ? $v3["race_date"]:0;
			$begin_date=isset($v3["begin_date"]) ? $v3["begin_date"]:"";
			$begin_hour=isset($v3["begin_hour"]) ? $v3["begin_hour"]:0;
			$begin_minutes=isset($v3["begin_minutes"]) ? $v3["begin_minutes"]:0;
			// $begin_date=strtotime($begin_date $begin_hour:$begin_minutes);
			$final_date=isset($v3["final_date"]) ? $v3["final_date"]:0;
			$final_hour=isset($v3["final_hour"]) ? $v3["final_hour"]:0;
			$final_minutes=isset($v3["final_minutes"]) ? $v3["final_minutes"]:0;
			$note=isset($v3["note"]) ? $v3["note"]:0;
			$start_time=time();
			$appear=in_array($sid,$site_check) ? 1:0;
			$calculate=isset($v3["calculate"]) ? $v3["calculate"]:0;
			//$sqlCloump.='("'.$InsertID.'","'.$InsertID.'",'.$sid.','.$appear.',1,1,"'.$race_date.'","'.$gid.'","'.$begin_date.'","'.$begin_hour.'","'.$begin_minutes.'","'.$final_date.'","'.$final_hour.'","'.$final_minutes.'","'.$note.'",FROM_UNIXTIME('.$start_time.')),';

			$raceEditUpdate="UPDATE race SET sid='$sid',appear='$appear',calculate='$calculate',race_date='$race_date',gid='$gid',
			begin_date='$begin_date',begin_hour='$begin_hour',begin_minutes='$begin_minutes',final_date='$final_date',final_hour='$final_hour',final_minutes='$final_minutes',
			note='$note' WHERE rid='$rid'";
			$raceEditUpdateResult=$link->prepare($raceEditUpdate);
			$raceEditUpdateResult->execute();

			$groupingUpdate='UPDATE `grouping` SET `status`=1,`r_nid`='.$r_nid.' WHERE gid in ('.$gid.')';
			$groupingUpdateResult=$link->prepare($groupingUpdate);
			$groupingUpdateResult->execute();
		}
		$status=!empty($c_status) ? 'success':'error';
		$status2=!empty($r_nid) ? 'success':'error';
		$arr=array('status'=>$status,'status2'=>$status2);
		echo json_encode($arr);
	}
}
?>
