<?php
include_once('link.php');
include_once('function.php');
$mid=isset($_POST['mid'])&&trim($_POST['mid'])?trim($_POST['mid']):0;
$tid=isset($_POST['tid'])&&trim($_POST['tid'])?trim($_POST['tid']):0;
$r_nid=isset($_POST['r_nid']) && trim($_POST['r_nid'])?trim($_POST['r_nid']):0;
$pid=isset($_POST['pid']) && trim($_POST['pid'])?rtrim($_POST['pid'],','):0;
$c_status=isset($_POST['c_status']) && trim($_POST['c_status'])?rtrim($_POST['c_status'],','):0;
$partidpid=isset($_POST['partidpid'])&&trim($_POST['partidpid'])?trim($_POST['partidpid']):0;
$chioce=isset($_POST['chioce'])&&trim($_POST['chioce'])?trim($_POST['chioce']):0;
$status=1;
$start_time=date('Y-m-d H:i:s');
$end_time=date('Y-m-d H:i:s');
$pAry=strpos($pid,',') ? explode(',',$pid) : array($pid);
$oldCount=!empty($pAry) ? count(array_filter($pAry)):0;//新勾選的人數,array_filter過濾
$chioceMessage="";

if($chioce==1)
{

  $dupChk='select  `pid` , COUNT( `pid` ) as pCount from player where pid in ( '.$pid.' ) group by id_card HAVING pCount >1';
  $dupResult=$link->prepare($dupChk);
  $dupResult->execute();
  $dupRows=$dupResult->fetchall();

  ##先搜索自己的球員的身分證
  $playerSqlStartTime="SELECT *
  from player
  where status=1 and pid in ($partidpid);";
  $playerSqlStartTimeResult=$link->prepare($playerSqlStartTime);
  $playerSqlStartTimeResult->execute();
  $playerSqlStartTimeRows=$playerSqlStartTimeResult->fetchall();
  // $playerSqlStartTimeRums=$playerSqlStartTimeResult->rowcount();
  // pre($playerSqlStartTimeRows);exit;

  ##篩選預賽裡面有沒有被選的id_card
  foreach($playerSqlStartTimeRows as $playK1=>$playV1)
  {
	$id_cardChioce=!empty($playV1['id_card'])? $playV1['id_card']:'';

	if($c_status==1)
	{
	  $playerChioce="SELECT *
		from
		(
		  select participate.*
		  from participate
		  inner join
      (
        select *
        from connect
        where status>0
      )connect
      on connect.`tid`=participate.`tid`
		  where connect.status>0 and participate.status=1 and participate.r_nid=$r_nid
		) participate
		inner join
		(
		  select *
		  FROM player
		  where status=1 and id_card=\"$id_cardChioce\"
		) player
		on participate.pid=player.pid
		";
	}
	elseif($c_status==2)
	{
	  $playerChioce="
    SELECT *
		from
		(
		  select participate.*
		  from participate
		  inner join
      (
        select *
        from connect
        where status =$c_status
      )connect
      on connect.`tid`=participate.`tid`
		  where connect.status=$c_status and participate.status=1 and participate.r_nid=$r_nid
		) participate_finals
		inner join
		(
		  select *
		  FROM player
		  where status=1 and id_card=\"$id_cardChioce\"
		)player
		on participate_finals.pid=player.pid
		";
	}
  // pre($playerChioce);exit;
	 $playerSqlStartTimeResult=$link->prepare($playerChioce);
	 $playerSqlStartTimeResult->execute();
	 $playerSqlStartTimeRowsss=$playerSqlStartTimeResult->fetchall();
	 $playerSqlStartTimeRumsss=$playerSqlStartTimeResult->rowcount();
	 if(!empty($playerSqlStartTimeRowsss) || !empty($dupRows))
	 {
	   $chioceMessage='have';
	 }
	 elseif(empty($playerSqlStartTimeRowsss))
	 {
	   $chioceMessage='nohave';
	 }
	 $arr=array('chioceMessage'=>$chioceMessage);
	 echo json_encode($arr);
  }
}
elseif($chioce==2)
{
  // echo "bbb";
  if($c_status==1)
  {
    $participateSql="SELECT
      participate.partid,
      participate.mid,
      participate.tid,
      participate.r_nid,
      participate.pid as participate_pid,
      participate.status as participate_status,
      player.pid,player.name_player
      from player
      left JOIN
      (
        select *
        from participate
        where status=1
      )participate
      on player.pid=participate.pid
      where participate.mid=$mid and tid=$tid
      group by pid
      order by pid";
    $participateSqlResult=$link->prepare($participateSql);
    $participateSqlResult->execute();
    $participateSqlRows=$participateSqlResult->fetchall();
    $participateSqlNums=$participateSqlResult->rowcount();
    $newCount=$oldCount+$participateSqlNums;

    if($newCount<7)//最低幾人
    {
      $factor=1;
    }
    elseif($newCount>10)//最高幾人
    {
      $factor=2;
    }
    else//符合人數後才加入並跳轉頁面
    {
      $factor=3;
      $inserSql=' ';
      $pid000=$pAry[0];
      if(!empty($pid000))
      {
        foreach($pAry as $pid)
        {
          $inserSql.="($mid,$tid,$r_nid,$pid,$status,'$start_time','$end_time'),";
        }
        $inserSql=rtrim($inserSql,',');

        $participateInsert="INSERT INTO participate(mid,tid,r_nid,pid,status,start_time,end_time) VALUES $inserSql";
        $participateInsertResult=$link->prepare($participateInsert);
        $participateInsertResult->execute();
      }
    }
  }
  elseif($c_status==2)
  {
    ##新勾選的球員與預賽球員名單比對後出來的人
    $choiceCountParticipate="SELECT
     partid,mid,tid,r_nid,pid,status as participate_status
     FROM participate
     where mid=$mid and tid=$tid and r_nid=$r_nid and status=1 and pid in ($pid)";
    $choiceCountParticipateResult=$link->prepare($choiceCountParticipate);
    $choiceCountParticipateResult->execute();
    $choiceCountParticipateRows=$choiceCountParticipateResult->fetchall();
    $choiceCountParticipateRums=$choiceCountParticipateResult->rowcount();//新勾選的球員與預賽球員名單比對後出來的人數

    ##預賽出賽人數最少七人限制
    $oldCountParticipate="SELECT
      participate.partid,participate.mid,participate.tid,participate.r_nid,participate.participate_status,participate.pid,
      participate_finals.part_fid,participate_finals.participate_finals_status,participate_finals.pid
      from
      (
       select partid,mid,tid,r_nid,pid,status as participate_status
       FROM participate
       where mid=$mid and tid=$tid and r_nid=$r_nid and status=1
      )participate
      inner join
      (
       SELECT part_fid,mid,tid,r_nid,pid,status as participate_finals_status
       FROM participate_finals
       where mid=$mid and tid=$tid and r_nid=$r_nid and status=1
       group by pid
      )participate_finals
      on participate.pid=participate_finals.pid
      order by participate.pid";
    $oldCountParticipateResult=$link->prepare($oldCountParticipate);
    $oldCountParticipateResult->execute();
    $oldCountParticipateRows=$oldCountParticipateResult->fetchall();
    $oldCountParticipateRums=$oldCountParticipateResult->rowcount();
    $newCountParticipateRums=$oldCountParticipateRums+$choiceCountParticipateRums;

    $participate_finalsSql="SELECT
      participate_finals.part_fid,
      participate_finals.mid,
      participate_finals.tid,
      participate_finals.r_nid,
      participate_finals.pid as participate_finals_pid,
      participate_finals.status as participate_finals_status,
      player.pid,player.name_player
      from player
      left JOIN
      (
        select *
        from participate_finals
        where status=1
      )participate_finals
      on player.pid=participate_finals.pid
      where participate_finals.mid=$mid and tid=$tid
      group by pid
      order by pid";
    $participate_finalsSqlResult=$link->prepare($participate_finalsSql);
    $participate_finalsSqlResult->execute();
    $participate_finalsSqlRows=$participate_finalsSqlResult->fetchall();
    $participate_finalsSqlNums=$participate_finalsSqlResult->rowcount();
    $newCount=$oldCount+$participate_finalsSqlNums;

    if($newCountParticipateRums<7)//預賽出賽人數最少幾人限制
    {
        $factor=4;
    }
    elseif($newCountParticipateRums>=7)
    {
      if($newCount<7)//最低幾人
      {
        $factor=1;
      }
      elseif($newCount>10)//最高幾人
      {
        $factor=2;
      }
      else//符合人數後才加入並跳轉頁面
      {
        $factor=3;
        $inserSql=' ';
        $pid000=$pAry[0];
        if(!empty($pid000))
        {
          foreach($pAry as $pid)
          {
            $inserSql.="($mid,$tid,$r_nid,$pid,$status,'$start_time','$end_time'),";
          }
          $inserSql=rtrim($inserSql,',');

          $participate_finalsInsert="INSERT INTO participate_finals(mid,tid,r_nid,pid,status,start_time,end_time) VALUES $inserSql";
          $participate_finalsInsertResult=$link->prepare($participate_finalsInsert);
          $participate_finalsInsertResult->execute();
        }

        ##決賽新增人數
        $newFinalsCount="SELECT *
        FROM
        (
         SELECT *
         FROM participate_finals
         where mid=$mid and tid=$tid and r_nid=$r_nid and status=1
        )participate_finals
        left join
        (
          select *
          from participate
          where mid=$mid and tid=$tid and r_nid=$r_nid and status=1
        )participate
        on participate_finals.pid=participate.pid
        where participate.partid is null";
        $newFinalsCountResult=$link->prepare($newFinalsCount);
        $newFinalsCountResult->execute();
        $newFinalsCountRows=$newFinalsCountResult->fetchall();
        $newFinalsCountRums=$newFinalsCountResult->rowcount();

        $newCalculate=3-$newFinalsCountRums;//決賽更替人次計算
        $connectCalculate="UPDATE connect set calculate=$newCalculate where tid=$tid and status>0";
        $connectCalculateResult=$link->prepare($connectCalculate);
        $connectCalculateResult->execute();
        //$connectCalculateRows=$connectCalculateResult->fetchall();
      }
    }
  }

  $arr=array('status'=>"success",'factor'=>$factor);
  echo json_encode($arr);

}
/*套版前,可用
$pAry=strpos($pid,',') ? explode(',',$pid) : array($pid);

$playerUpdate='UPDATE player SET status=1 WHERE `pid` in ('.implode(',' , $pAry).')';
$playerUpdateResult=$link->prepare($playerUpdate);
$playerUpdateResult->execute();

echo json_encode($arr);
*/
?>
