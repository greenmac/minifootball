<?php
include_once('link.php');
include_once('function.php');
include_once('get_order_list.php');
header("Content-Type:text/html; charset=utf-8");

##抓傳送過來的資料
##上傳前先確定會不會有覆蓋後資料問題的改變
// $m_id=0;
// $mid=0;
$mid=isset($_GET['mid'])&&trim($_GET['mid'])?trim($_GET['mid']):0;
$m_id=!empty($m_id)?$m_id:0;
$start_time=date('Y-m-d H:i:s');
$end_time=date('Y-m-d H:i:s');
foreach($getOrderInfo as $get1)
{
  $product_name=!empty($get1['product_name']) && trim($get1['product_name']) ? trim($get1['product_name']):'';//就是race_name的sku
  $plu=!empty($get1['plu']) && strpos($get1['plu'],',') ? explode(',',$get1['plu']):"";//就是race_name的sku
  if(isset($plu[0])&&$plu[0]=="A") //盃賽顯示
  {
    $pluKey=isset($plu[1]) ? $plu[1]:"";
    $order_num=!empty($get1['order_num']) && trim($get1['order_num'])?trim($get1['order_num']):'';
    $card_num=!empty($get1['card_num']) && trim($get1['card_num'])?trim($get1['card_num']):'';
    $member_name=!empty($get1['member_name']) && trim($get1['member_name'])?trim($get1['member_name']):'';

    $memberSql="SELECT * from member where member_status=1 and card_num='$card_num'";
    $memberSqlResult=$link->prepare($memberSql);
    $memberSqlResult->execute();
    $memberSqlRows=$memberSqlResult->fetchall();
    // pre($memberSqlRows);

    if(!empty($memberSqlRows))
    {
      $memberSqlUpdate="UPDATE member set m_id='$m_id',member_name='$member_name',member_status=1,end_time='$end_time' where card_num='$card_num'";
      $memberSqlUpdateResult=$link->prepare($memberSqlUpdate);
      $memberSqlUpdateResult->execute();
    }
    elseif(empty($memberSqlRows))
    {
      $memberInsert="INSERT INTO member(m_id,card_num,member_name,member_phone,member_email,member_status,start_time)
       VALUES ('$m_id','$card_num','$member_name','member_phone','member_email',1,'$start_time')";
      $memberInsertResult=$link->prepare($memberInsert);
      $memberInsertResult->execute();
    }
    $memberSql2="SELECT * from member where member_status=1 and card_num='$card_num'";
    $memberSql2Result=$link->prepare($memberSql2);
    $memberSql2Result->execute();
    $memberSql2Rows=$memberSql2Result->fetchall();
    $mid=!empty($memberSql2Rows[0]['mid'])?$memberSql2Rows[0]['mid']:0;
    // pre($mid);
    // pre($memberSql2Rows);

    $race_nameSql="select * from race_name where status=1 and sku='$pluKey'";
    $race_nameSqlResult=$link->prepare($race_nameSql);
    $race_nameSqlResult->execute();
    $race_nameSqlRows=$race_nameSqlResult->fetchall();
    // pre($race_nameSqlRows);
    $race_nameSqlRowsR_nid=!empty($race_nameSqlRows[0]['r_nid'])?$race_nameSqlRows[0]['r_nid']:0;

    if(!empty($race_nameSqlRowsR_nid))
    {
      $race_nameAppmakerUpdate="UPDATE race_name set appmaker_name='$product_name' where sku='$pluKey'";
      $race_nameAppmakerUpdateResult=$link->prepare($race_nameAppmakerUpdate);
      $race_nameAppmakerUpdateResult->execute();
    }

    $ticketSql="SELECT * from ticket where number='$order_num'";
    $ticketSqlResult=$link->prepare($ticketSql);
    $ticketSqlResult->execute();
    $ticketSqlRows=$ticketSqlResult->fetchall();
    // pre($ticketSqlRows);

    if(!empty($ticketSqlRows))
    {
      $ticketSqlUpdate="
      UPDATE ticket as ticket,
      (
        select * from race_name
      ) as race_name
      set ticket.r_nid=race_name.r_nid
      where ticket.sku=race_name.sku
      ";
      $ticketSqlUpdateResult=$link->prepare($ticketSqlUpdate);
      $ticketSqlUpdateResult->execute();
    }
    elseif(empty($ticketSqlRows))
    {
      $ticketInsert="INSERT INTO ticket(mid,r_nid,status,number,sku,start_time)
       VALUES ($mid,$race_nameSqlRowsR_nid,1,'$order_num','$pluKey','$start_time')";
      $ticketInsertResult=$link->prepare($ticketInsert);
      $ticketInsertResult->execute();
    }
  }
}

##本機測試用
// $mid=1;$m_id=0;
if(!empty($getOrderInfo))
{
  $memberSql="SELECT
  ticket.tid,
  ticket.mid,
  member.member_name,
  connect.tid as connect_tid,
  participate.tid as participate_tid,
  sum(participate.status) as participate_sum_status,
  participate.status,
  connect.rid,
  connect.status as c_status,
  connect.cid,
  connect.gid,
  connect.team_name,
  site.place,
  grouping.age,
  ticket.r_nid,
  race.status as race_status,
  race_name.name,
  race_name.sku,
  ticket.number
  from
  (
    SELECT *
    FROM ticket
    where mid=$mid and status=1
  )ticket
  inner join member
  on ticket.mid=member.mid
  left join
  (
    select
    cid,mid,tid,rid,r_nid,sid,gid,status,calculate,team_name,
    leader_name,leader_mobile,leader_email,coach_name,coach_mobile,coach_email,supervise_name,supervise_mobile,supervise_email,
    start_time,end_time
    from connect
    where status>0 and reveal >0
  )connect
  on ticket.tid=connect.tid
  left join
  (
    select *
    from site
  )site
  on connect.sid=site.sid
  left join
  (
    select *
    from grouping
  )grouping
  on connect.gid=grouping.gid
  left join
  (
    select *
    FROM race
    where appear=1
  )race
  on connect.rid=race.rid
  inner join
  (
    select *
    from race_name
    where status=1
  )race_name
  on ticket.r_nid=race_name.r_nid
  left join participate
  on ticket.tid=participate.tid
  where race.status <>0 or race.status is null
  group by mid,r_nid,tid
  order by tid desc
  ";
  $memberSqlResult=$link->prepare($memberSql);
  $memberSqlResult->execute();
  $memberSqlRows=$memberSqlResult->fetchall(PDO::FETCH_ASSOC);
  // pre($memberSqlRows);

  $nowTime=strtotime(date('Y/m/d H:i:s'));
}

?>
 <!DOCTYPE html>
 <html lang="zh-CN">

 <head>
     <title>迷你足球報名系統</title>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1">
     <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
     <meta property="og:image" content="">
     <meta property="og:title" content="">
     <meta property="og:description" content="">
     <meta property="og:url" content="" />
     <!-- Bootstrap -->
     <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
     <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
     <link href="css/bootstrap.css" rel="stylesheet">
     <link href="css/animate.css" rel="stylesheet">
     <link href="css/all.css" rel="stylesheet">
     <link href="font/flaticon.css" rel="stylesheet">
     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
     <!--[if lt IE 9]>
       <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
       <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

     <![endif]-->

     <body class="main_green_bg">
         <!--表頭-->
         <header class="head main_light_blue_bg">
             <div class="title">
                 <h2><div class="ball_icon"><i class="flaticon-ball"></i></div><?php echo isset($memberSqlRows[0]['member_name']) ? $memberSqlRows[0]['member_name'].'的球員':'';?> </h2>
             </div>
             <a onclick="chk_mid(<?php echo $m_id;?>,<?php echo $mid;?>);return false;" href="#" class="player_edit">
                 新增/編輯球員
                 <i class="far fa-edit pull-right"></i>
             </a>
         </header>
         <!--表頭 end-->
         <!--主體 -->
         <section class="main_green_bg main">
             <div class="title">
                 <h2><div class="ball_icon"><i class="flaticon-ball"></i></div>您的盃賽</h2>
             </div>

             <?php
             if(!empty($memberSqlRows)){
               foreach($memberSqlRows as $k=>$v)
               {
                 $mid=!empty($v['mid'])?$v['mid']:0;
                 $r_nid=!empty($v['r_nid'])?$v['r_nid']:0;
                 $connect_tid=!empty($v['connect_tid'])?$v['connect_tid']:0;
                 $tid=!empty($v['tid'])?$v['tid']:0;
                 $c_status=!empty($v['c_status'])?$v['c_status']:1;
                 $participate_tid=!empty($v['participate_tid'])?$v['participate_tid']:0;
                 $gid=!empty($v['gid'])?$v['gid']:0;
                 $rid=!empty($v['rid'])?$v['rid']:0;
                 $race_status=!empty($v['race_status'])?$v['race_status']:0;
                 $beginTime="";
                 $finalTime="";
                 $aTime=array();
                 $bTime=array();
                 // if($c_status==2)
                 // {
                   $raceOpenTime="SELECT
                   begin_date,
                   begin_hour,
                   begin_minutes,
                   final_date,
                   final_hour,
                   final_minutes
                   from race
                   where r_nid=$r_nid and status=$c_status and rid=$rid and appear=1";
                   $raceOpenTimeResult=$link->prepare($raceOpenTime);
                   $raceOpenTimeResult->execute();
                   $raceOpenTimeRows=$raceOpenTimeResult->fetchall();
                   // pre($raceOpenTime);

                   // foreach($raceOpenTimeRows as $roK1 => $raceOpenTimeRows)
                   // {
                     $begin_date=!empty($raceOpenTimeRows[0]['begin_date'])?$raceOpenTimeRows[0]['begin_date']:0;
                     $begin_hour=!empty($raceOpenTimeRows[0]['begin_hour'])?$raceOpenTimeRows[0]['begin_hour']:0;
                     $begin_minutes=!empty($raceOpenTimeRows[0]['begin_minutes'])?$raceOpenTimeRows[0]['begin_minutes']:0;
                     $final_date=!empty($raceOpenTimeRows[0]['final_date'])?$raceOpenTimeRows[0]['final_date']:0;
                     $final_hour=!empty($raceOpenTimeRows[0]['final_hour'])?$raceOpenTimeRows[0]['final_hour']:0;
                     $final_minutes=!empty($raceOpenTimeRows[0]['final_minutes'])?$raceOpenTimeRows[0]['final_minutes']:0;

                     $beginDataAry=!empty($begin_date) ? explode('/',$begin_date):array();
                     $beginYear=!empty($beginDataAry) ? $beginDataAry[0]:0;
                     $beginMonth=!empty($beginDataAry) ? $beginDataAry[1]:0;
                     $beginDay=!empty($beginDataAry) ? $beginDataAry[2]:0;
                     // pre($beginYear);
                     // pre($beginMonth);
                     // pre($beginDay);

                     $finalDataAry=!empty($final_date) ? explode('/',$final_date):array();
                     $finalYear=!empty($finalDataAry) ? $finalDataAry[0]:0;
                     $finalMonth=!empty($finalDataAry) ? $finalDataAry[1]:0;
                     $finalDay=!empty($finalDataAry) ? $finalDataAry[2]:0;
                     // pre($finalYear);
                     // pre($finalMonth);
                     // pre($finalDay);

                     //$beginTime=!empty(strtotime("$begin_date $begin_hour:$begin_minutes"))?strtotime("$begin_date $begin_hour:$begin_minutes"):0;
                     $beginTime=mktime($begin_hour,$begin_minutes,0,$beginMonth,$beginDay,$beginYear);
                     // $finalTime=!empty(strtotime("$final_date $final_hour:$final_minutes"))?strtotime("$final_date $final_hour:$final_minutes"):0;
                     $finalTime=mktime($final_hour,$final_minutes,0,$finalMonth,$finalDay,$finalYear);
                     // pre($beginTime);
                     // pre($finalTime);
                     $dateBeginTime=date('Y/m/d H:i:s',$beginTime);
                     $dateFinalTime=date('Y/m/d H:i:s',$finalTime);
                     // pre($dateBeginTime);
                     // pre($dateFinalTime);
                   // }
                 // }
             ?>
             <!--門票-->
             <div class="tickets">
                 <div class="tickets_top">
                     <div class="tickets_data clearfix">
                         <!-- <div class="race main_blue_bg"> -->
                           <?php
                            echo $c_status==2?'<div class="race main_red_bg">決賽</div>':'<div class="race main_blue_bg">預賽</div>';
                            $c_status=!empty($c_status)?$c_status:1;
                           ?>
                         <!-- </div> -->
                         <div class="pull-right tickets_data_text">
                             盃賽資訊
                             <span class="undo">
                               <?php
                                 echo !empty($connect_tid)?'<font color="#00FF00">已填寫</font>':'<font color="#FF0000">未填寫</font>';
                               ?>
                             </span>
                             ｜
                             球員
                             <span class="undo">
                               <?php
                                 echo !empty($v['participate_sum_status'])?'<font color="#00FF00">已指派</font>':'<font color="#FF0000">未指派</font>';
                               ?>
                             </span>
                         </div>
                     </div>
                     <div class="tickets_title"><?php echo $v['name'];?></div>
                     <div class="tickets_number">訂單編號:<span><?php echo $v['number']?></span></div>
                     <div class="tickets_number">隊伍名稱:<span><?php echo !empty($v['team_name'])?$v['team_name']:'';?></span></div>
                     <div class="tickets_number">比賽場區:<span><?php echo !empty($v['place'])?$v['place']:'';?></span></div>
                     <div class="tickets_number">年齡分組:<span><?php echo !empty($v['age'])?$v['age']:'';?></span></div>
                 </div>
                 <ul class="btn_row  clearfix">
                    <?php
                    // if(!empty($race_status))##race表的status等於0或大於0的判斷(大於0正常跑,等於0會跳出alert'盃賽已結束')
                    // {
                      if($c_status==2)
                      // if(!empty($c_status))
                      {
                        // if($nowTime<$beginTime || $nowTime>$finalTime)
                        if($nowTime<$beginTime)
                        {
                          echo '<li><a onclick="siteClick('.$v['tid'].','.$c_status.','.$race_status.','.$beginTime.','.$finalTime.');"><i class="far fa-file-alt"></i>填寫盃賽資訊</a></li>';
                        }
                        else
                        {
                          echo '<li><a href="site_index.php?m_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&participate_tid='.$participate_tid.'&rid='.$rid.'"><i class="far fa-file-alt"></i>填寫盃賽資訊</a></li>';
                        }
                      }
                      else
                      {
                        echo '<li><a href="site_index.php?m_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&participate_tid='.$participate_tid.'&rid='.$rid.'"><i class="far fa-file-alt"></i>填寫盃賽資訊</a></li>';
                      }
                    // }
                    // elseif(empty($race_status))
                    // {
                    //   echo '<li><a onclick="siteClick('.$v['tid'].','.$c_status.','.$race_status.');"><i class="far fa-file-alt"></i>填寫盃賽資訊</a></li>';
                    // }
                    ?>
                     <?php
                     // if(!empty($race_status))##race表的status等於0或大於0的判斷(大於0正常跑,等於0會跳出alert'盃賽已結束')
                     // {
                       // if($c_status==2)
                       if(!empty($c_status))
                       {
                         // if($nowTime<$beginTime || $nowTime>$finalTime)
                         if($nowTime<$beginTime)
                         {
                           echo '<li><a onclick="appointClick('.$connect_tid.','.$c_status.','.$race_status.','.$beginTime.','.$finalTime.');"><i class="far fa-user"></i>指派球員</a></li>';
                         }
                         else
                         {
                           if(!empty($connect_tid))
                           {
                             $alt=2;
                             echo '<li><a href="appoint_index.php?m_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&participate_tid='.$participate_tid.'&gid='.$gid.'&alt='.$alt.'"><i class="far fa-user"></i>指派球員</a></li>';
                           }
                           else
                           {
                             echo '<li><a onclick="appointClick('.$connect_tid.','.$c_status.','.$race_status.','.$beginTime.','.$finalTime.');"><i class="far fa-user"></i>指派球員</a></li>';
                           }
                         }
                       }
                       else
                       {
                         if(!empty($connect_tid))
                         {
                           echo '<li><a href="appoint_index.php?m_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&participate_tid='.$participate_tid.'&gid='.$gid.'"><i class="far fa-user"></i>指派球員</a></li>';
                         }
                         else
                         {
                           echo '<li><a onclick="appointClick('.$connect_tid.','.$c_status.','.$race_status.','.$beginTime.','.$finalTime.');"><i class="far fa-user"></i>指派球員</a></li>';
                         }
                       }
                     // }
                     // elseif(empty($race_status))
                     // {
                     //   echo '<li><a onclick="appointClick('.$connect_tid.','.$c_status.','.$race_status.');"><i class="far fa-user"></i>指派球員</a></li>';
                     // }
                    ?>
                 </ul>
             </div>
             <?php }} ?>
         </section>
         <!--主體 end -->

     </body>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="js/bootstrap.min.js"></script>

     <script src="js/all.js"></script>
     <script>
        let c_status=<?php echo !empty($c_status)?$c_status:0;?>;
        // let beginTime=<?php //echo !empty($beginTime)?$beginTime:0;?>;
        // let finalTime=<?php //echo !empty($finalTime)?$finalTime:0;?>;
        let nowTime=<?php echo !empty($nowTime)?$nowTime:0;?>;
        let unixtime=<?php echo mktime(0,0,0,0,0,0);?>;

        function siteClick(tid,c_status,race_status,beginTime,finalTime)
        {
          // if(race_status)##race表的status等於0或大於0的判斷(大於0正常跑,等於0會跳出alert'盃賽已結束')
          // {
            if(c_status==2)
            // if(c_status)
            {
              if(nowTime<beginTime)
              {
                alert('未到報名時間');return;
              }
              else if(nowTime>finalTime)
              {
                if(beginTime == unixtime && finalTime==unixtime)
                {
                  alert('尚未開放報名');return;
                }
                else
                {
                  alert('超過報名時間');return;
                }
              }
            }
        //   }
        //   else
        //   {
        //     alert('盃賽已結束');return;
        //   }
        }

        function appointClick(tid,c_status,race_status,beginTime,finalTime)
        {
          // if(race_status)##race表的status等於0或大於0的判斷(大於0正常跑,等於0會跳出alert'盃賽已結束')
          // {
            // if(c_status==2)
            if(c_status)
            {
              if(nowTime<beginTime)
              {
                alert('未到報名時間');return;
              }
              else if(nowTime>finalTime)
              {
                if(beginTime == unixtime && finalTime==unixtime)
                {
                  alert('尚未開放報名');return;
                }
                else
                {
                  alert('超過報名時間');return;
                }
              }
            }
            else if(!tid)
            {
              alert('請先填寫盃賽資訊');
            }
          // }
          // else
          // {
          //   alert('盃賽已結束');return;
          // }
        }

  		function chk_mid(m_id,mid){
  			if(m_id>0&&mid>0){
  				window.location='player_index.php?m_id=<?php echo $m_id;?>&mid=<?php echo $mid;?>';
  			}else{
  				alert("請先購買盃賽票卷");
  				return;
  			}
  		}

      $(document).ready()
      {
        let order_num=<?php echo !empty($order_num)?$order_num:0;?>;
        if(!order_num)
        {
          alert('您尚未購買盃賽票券\n請至購物車完成票券購買\n');
        }
      }
     </script>
 </html>
