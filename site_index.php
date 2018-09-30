<?php
include_once('link.php');
include_once('function.php');

$m_id=isset($_GET['m_id'])&&trim($_GET['m_id'])?trim($_GET['m_id']):0;
$mid=isset($_GET['mid'])&&trim($_GET['mid'])?trim($_GET['mid']):0;
$r_nid=isset($_GET['r_nid'])&&trim($_GET['r_nid'])?trim($_GET['r_nid']):0;
$rid=isset($_GET['rid'])&&trim($_GET['rid'])?trim($_GET['rid']):0;
$tid=isset($_GET['tid'])&&trim($_GET['tid'])?trim($_GET['tid']):0;
$c_status=isset($_GET['c_status'])&&trim($_GET['c_status'])?trim($_GET['c_status']):0;
$connect_tid=!empty($_GET['connect_tid'])&&trim($_GET['connect_tid'])?trim($_GET['connect_tid']):0;
$participate_pid=!empty($_GET['participate_pid'])&&trim($_GET['participate_pid'])?trim($_GET['participate_pid']):0;

if($c_status==1)
{
  $connectSql="SELECT
    connect.cid,
    connect.mid,
    connect.tid as connect_tid,
    participate.status as participate_status,
    connect.rid,
    race.race_date,
    race.note,
    connect.r_nid,
    connect.sid as connect_sid,
    connect.status,
    connect.gid,
    grouping.age,
    connect.sid,
    site.place,
    site.address,
    race_name.name
    FROM
    (
      select *
      from connect
      where status=$c_status and mid=$mid and r_nid=$r_nid and tid=$tid
    )connect
    inner join site
    on connect.sid=site.sid
    inner join
    (
      select *
      from race_name
      where status=1
    )race_name
    on connect.r_nid=race_name.r_nid
    inner join grouping
    on connect.gid=grouping.gid
    inner join
    (
      select *
      from race
      where appear=1 and status=$c_status
    )race
    on connect.rid=race.rid
    left join participate
    on connect.tid=participate.tid
    ORDER BY connect.rid";
}
elseif($c_status==2)
{
  $connectSql="SELECT
    connect.cid,
    connect.mid,
    connect.tid as connect_tid,
    participate_finals.status as participate_finals_status,
    connect.rid,
    race.race_date,
    race.begin_date,
    race.begin_hour,
    race.begin_minutes,
    race.final_date,
    race.final_hour,
    race.final_minutes,
    race.note,
    connect.r_nid,
    connect.sid as connect_sid,
    connect.status,
    connect.sid,
    site.place,
    site.address,
    connect.gid,
    grouping.age,
    race_name.name
    FROM
    (
      select *
      from connect
	    where status=$c_status and mid=$mid and r_nid=$r_nid and tid=$tid
    )connect
    inner join site
    on connect.sid=site.sid
    inner join
    (
      select *
      from race_name
      where status=1
    )race_name
    on connect.r_nid=race_name.r_nid
    inner join grouping
    on connect.gid=grouping.gid
    inner join
    (
      select *
      from race
      where appear=1 and status=$c_status
    )race
    on connect.rid=race.rid
    left join participate_finals
    on connect.tid=participate_finals.tid
    ORDER BY connect.rid";
}
$connectSqlResult=$link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows=$connectSqlResult->fetchall();
$connect_sid=!empty($connectSqlRows[0]['connect_sid'])?$connectSqlRows[0]['connect_sid']:0;
// pre($connectSql);
// exit;

$ticketSql="SELECT * from ticket where tid=$tid";
$ticketSqlResult=$link->prepare($ticketSql);
$ticketSqlResult->execute();
$ticketSqlRows=$ticketSqlResult->fetchall();

$raceSqlSubstring="SELECT race.rid,race.r_nid,race.sid,race.status,race.gid,grouping.gid,grouping.age,grouping.lowest_birth
FROM
(
  SELECT race.rid,race.r_nid,race.sid,race.status,SUBSTRING_INDEX(SUBSTRING_INDEX(race.gid, ',', numbers.n), ',', -1) gid
  FROM
  (
   SELECT 1 n
   UNION ALL SELECT 2
   UNION ALL SELECT 3
   UNION ALL SELECT 4
   UNION ALL SELECT 5
   UNION ALL SELECT 6
   UNION ALL SELECT 7
   UNION ALL SELECT 8
   UNION ALL SELECT 9
   UNION ALL SELECT 10
  ) numbers
  INNER JOIN race
  ON (CHAR_LENGTH(race.gid)-CHAR_LENGTH(REPLACE(race.gid, ',', '')))>=numbers.n-1
  where appear=1 and status=$c_status and r_nid=$r_nid and sid=$connect_sid
)race
inner JOIN
(
  select *
  from grouping
)grouping
on race.gid=grouping.gid
order by grouping.gid";
// pre($raceSqlSubstring);exit;

$raceSqlSubstringResult=$link->prepare($raceSqlSubstring);
$raceSqlSubstringResult->execute();
$raceSqlSubstringRows=$raceSqlSubstringResult->fetchall();

$raceSql="SELECT
race.rid,race.r_nid,race.race_date,
race.begin_date,
race.begin_hour,
race.begin_minutes,
race.final_date,
race.final_hour,
race.final_minutes,
race_name.name,race.sid,site.said,site.place
          FROM
          (
            select *
          	from race
          	where appear=1 and status=$c_status and r_nid=$r_nid
          )race
          inner JOIN site
          on race.sid=site.sid
          inner join race_name
          on race.r_nid=race_name.r_nid";
$raceSqlResult=$link->prepare($raceSql);
$raceSqlResult->execute();
$raceSqlRows=$raceSqlResult->fetchall();
// pre($raceSqlRows);exit;

if($c_status==1)
{
  $participateSql="SELECT * from participate where mid=$mid and tid=$tid and r_nid=$r_nid and status>0";
}
elseif($c_status==2)
{
  $participateSql="SELECT * from participate_finals where mid=$mid and tid=$tid and r_nid=$r_nid and status>0";
}
$participateSqlResult=$link->prepare($participateSql);
$participateSqlResult->execute();
$participateSqlRows=$participateSqlResult->fetchall();
$participateSqlRums=$participateSqlResult->rowcount();
// pre($participateSqlRows);
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
    <title></title>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
    <style>
     .error{color:red;}
    </style>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/all.css" rel="stylesheet">
    <link href="font/flaticon.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dist/css/bootstrap-datepicker3.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <body>
        <!--表頭-->
        <form class="" method="post" id="addform" name="addform" action="">
        <header class="head_title">
            <div class="title">
                <h2>盃賽資訊</h2>
            </div>

                <div class="tryout_row">
                    <!-- <div class="race main_blue_bg"> -->
                      <?php
                        switch($c_status)
                        {
                          case 1:
                            echo '<div class="race main_blue_bg">預賽</div>';
                            break;
                          case 2:
                            echo '<div class="race main_red_bg">決賽</div>';
                            break;
                          default:
                            echo 'error';
                        }
                      ?>
                    <!-- </div> -->
                    <ul class="tryout_text">
                        <li><div class="tickets_data_text">
                            球員
                            <span class="undo">
                              <?php
                                echo !empty($participateSqlRums)?'<font color="#00FF00">已指派</font>':'<font color="#FF0000">未指派</font>';
                              ?>
                            </span>
                        </div>
                       </li>
                        <li><div class="tickets_number">票券號碼<span><?php echo $ticketSqlRows[0]['number']?></span></div></li>
                    </ul>

                    <div class="tickets_title">
                      <?php
                        //$connect_tid是聯絡資訊的tid,有值代表有填寫盃賽,沒有值的話,用r_nid去搜尋盃賽名稱
                        echo !empty($connect_tid)&&trim($connect_tid)&&isset($connectSqlRows[0]['name'])?$connectSqlRows[0]['name']:$raceSqlRows[0]['name'];
                      ?>
                    </div>
                </div>
        </header>
        <!--表頭 end-->
        <!--主體 -->
        <section class="main ">
         <div class="tryout_form">
            <div class="form-group">

              <label for="">場區*</label>
             <?php
             $nowTime=strtotime(date('Y/m/d H:i:s'));
             if(!empty($connect_tid)&&trim($connect_tid))
             {
               # 查詢報名場次是否超過報名時間
               $raceOverTimeSql=
               "SELECT *
               from
               (
                 select rid,sid,final_date,final_hour,final_minutes
                 from race
                 where rid=$rid and status=$c_status and appear=1
               )race
               inner join
               (
                 select *
                 from site
                 where status=$c_status
               )site
               on race.sid=site.sid
               ";
               $raceOverTimeSqlRe=$link->prepare($raceOverTimeSql);
               $raceOverTimeSqlRe->execute();
               $raceOverTimeSqlRw=$raceOverTimeSqlRe->fetchall(PDO::FETCH_ASSOC);
               $raceOverTimeSid=!empty($raceOverTimeSqlRw[0]['sid'])?$raceOverTimeSqlRw[0]['sid']:0;
               $raceOverTimePlace=!empty($raceOverTimeSqlRw[0]['place'])?$raceOverTimeSqlRw[0]['place']:'';
               $rOTSR_final_date=!empty($raceOverTimeSqlRw[0]['final_date'])?$raceOverTimeSqlRw[0]['final_date']:0;
               $rOTSR_final_hour=!empty($raceOverTimeSqlRw[0]['final_hour'])?$raceOverTimeSqlRw[0]['final_hour']:0;
               $rOTSR_final_minutes=!empty($raceOverTimeSqlRw[0]['final_minutes'])?$raceOverTimeSqlRw[0]['final_minutes']:0;
               $rOTSR_final_time=strtotime("$rOTSR_final_date $rOTSR_final_hour:$rOTSR_final_minutes:0");
               if($c_status==1)
               {
                  if($nowTime<=$rOTSR_final_time)
                  {
                    echo '<select class="form-control_select" id="place" name="place">';
                    echo '<option value="0">請選擇</option>';
                    $be=array();
                    $fi=array();
                    foreach($raceSqlRows as $ra1)
                    {
                      $begin_date=!empty($ra1['begin_date'])?$ra1['begin_date']:0;
                      $begin_hour=!empty($ra1['begin_hour'])?$ra1['begin_hour']:0;
                      $begin_minutes=!empty($ra1['begin_minutes'])?$ra1['begin_minutes']:0;
                      $final_date=!empty($ra1['final_date'])?$ra1['final_date']:0;
                      $final_hour=!empty($ra1['final_hour'])?$ra1['final_hour']:0;
                      $final_minutes=!empty($ra1['final_minutes'])?$ra1['final_minutes']:0;

                      $beginDataAry=!empty($begin_date) ? explode('/',$begin_date):array();
                      $beginYear=!empty($beginDataAry) ? $beginDataAry[0]:0;
                      $beginMonth=!empty($beginDataAry) ? $beginDataAry[1]:0;
                      $beginDay=!empty($beginDataAry) ? $beginDataAry[2]:0;

                      $finalDataAry=!empty($final_date) ? explode('/',$final_date):array();
                      $finalYear=!empty($finalDataAry) ? $finalDataAry[0]:0;
                      $finalMonth=!empty($finalDataAry) ? $finalDataAry[1]:0;
                      $finalDay=!empty($finalDataAry) ? $finalDataAry[2]:0;

                      //$beginTime=!empty(strtotime("$begin_date $begin_hour:$begin_minutes"))?strtotime("$begin_date $begin_hour:$begin_minutes"):0;
                      $beginTime=mktime($begin_hour,$begin_minutes,0,$beginMonth,$beginDay,$beginYear);
                      // $finalTime=!empty(strtotime("$final_date $final_hour:$final_minutes"))?strtotime("$final_date $final_hour:$final_minutes"):0;
                      $finalTime=mktime($final_hour,$final_minutes,0,$finalMonth,$finalDay,$finalYear);
                      // pre($beginTime);
                      // pre($finalTime);

                      $be[]=$beginTime;
                      $fi[]=$finalTime;

                      $openTime=$nowTime>=$beginTime && $nowTime<=$finalTime;
                      if(!empty($openTime))
                      {
                        $chk=$connectSqlRows[0]['sid']==$ra1['sid'] ? 'selected':'';
                        echo '<option value="'.$ra1['sid'].'"'.$chk.'>'.$ra1['place'].'</option>';
                      }
                    }
                    $beMin=min($be);
                    $fiMax=Max($fi);

                    $nowTimeLessBeMin=$nowTime<$beMin;
                    $nowTimeMoreFiMax=$nowTime>$fiMax;


                    echo $nowTime<$beMin?'<option value="0">尚未開放報名</option>':'error';
                    echo $nowTime>$fiMax?'<option value="0">超過報名時間</option>':'error';
                    echo '</select>';
                  }
                  elseif($nowTime>$rOTSR_final_time)
                  {
                      echo '<select class="form-control_select" id="place" name="place" disabled>';
                      echo '<option value="'.$raceOverTimeSid.'">'.$raceOverTimePlace.'</option>';
                      echo '</select>';
                  }
               }
               elseif($c_status==2)
               {
                 ##決賽場次以預賽晉級的場地為主,不會更動
                 echo '<select class="form-control_select" id="place" name="place" disabled>';
                 echo '<option value="'.$connectSqlRows[0]['sid'].'">'.$connectSqlRows[0]['place'].'</option>';
                 echo '</select>';
               }
             }
             else
             {
               echo '<select class="form-control_select" id="place" name="place">';
               echo '<option value="0">請選擇</option>';
               $be=array();
               $fi=array();
               foreach($raceSqlRows as $ra2)
               {
                 $begin_date=!empty($ra2['begin_date'])?$ra2['begin_date']:0;
                 $begin_hour=!empty($ra2['begin_hour'])?$ra2['begin_hour']:0;
                 $begin_minutes=!empty($ra2['begin_minutes'])?$ra2['begin_minutes']:0;
                 $final_date=!empty($ra2['final_date'])?$ra2['final_date']:0;
                 $final_hour=!empty($ra2['final_hour'])?$ra2['final_hour']:0;
                 $final_minutes=!empty($ra2['final_minutes'])?$ra2['final_minutes']:0;

                 $beginDataAry=!empty($begin_date) ? explode('/',$begin_date):array();
                 $beginYear=!empty($beginDataAry) ? $beginDataAry[0]:0;
                 $beginMonth=!empty($beginDataAry) ? $beginDataAry[1]:0;
                 $beginDay=!empty($beginDataAry) ? $beginDataAry[2]:0;

                 $finalDataAry=!empty($final_date) ? explode('/',$final_date):array();
                 $finalYear=!empty($finalDataAry) ? $finalDataAry[0]:0;
                 $finalMonth=!empty($finalDataAry) ? $finalDataAry[1]:0;
                 $finalDay=!empty($finalDataAry) ? $finalDataAry[2]:0;

                 //$beginTime=!empty(strtotime("$begin_date $begin_hour:$begin_minutes"))?strtotime("$begin_date $begin_hour:$begin_minutes"):0;
                 $beginTime=mktime($begin_hour,$begin_minutes,0,$beginMonth,$beginDay,$beginYear);
                 // $finalTime=!empty(strtotime("$final_date $final_hour:$final_minutes"))?strtotime("$final_date $final_hour:$final_minutes"):0;
                 $finalTime=mktime($final_hour,$final_minutes,0,$finalMonth,$finalDay,$finalYear);
                  // pre($beginTime);
                  // pre($finalTime);

                 $be[]=$beginTime;
                 $fi[]=$finalTime;

                 if($nowTime>=$beginTime && $nowTime<=$finalTime)
                 {
                   echo '<option value="'.$ra2['sid'].'">'.$ra2['place'].'</option>';
                 }
               }

               $beMin=min($be);
               $fiMax=Max($fi);

               $nowTimeLessBeMin=$nowTime<$beMin;
               $nowTimeMoreFiMax=$nowTime>$fiMax;

               echo $nowTime<$beMin?'<option value="0">尚未開放報名</option>':'error';
               echo $nowTime>$fiMax?'<option value="0">超過報名時間</option>':'error';
               echo '</select>';
             };
             ?>
            </div>

            <div class="form-group">
              <label for="">年齡分組*</label>
              <?php
              if(!empty($connect_tid)&&trim($connect_tid))
              {
                $raceOverTimeSql2=
                "SELECT *
                from
                (
                  select rid,gid,final_date,final_hour,final_minutes
                  from race
                  where rid=$rid and status=$c_status and appear=1
                )race
                ";
                $raceOverTimeSql2Re=$link->prepare($raceOverTimeSql2);
                $raceOverTimeSql2Re->execute();
                $raceOverTimeSql2Rw=$raceOverTimeSql2Re->fetchall(PDO::FETCH_ASSOC);
                $rOTSR_final_date2=!empty($raceOverTimeSql2Rw[0]['final_date'])?$raceOverTimeSql2Rw[0]['final_date']:0;
                $rOTSR_final_hour2=!empty($raceOverTimeSql2Rw[0]['final_hour'])?$raceOverTimeSql2Rw[0]['final_hour']:0;
                $rOTSR_final_minutes2=!empty($raceOverTimeSql2Rw[0]['final_minutes'])?$raceOverTimeSql2Rw[0]['final_minutes']:0;
                $rOTSR_final_time2=strtotime("$rOTSR_final_date2 $rOTSR_final_hour2:$rOTSR_final_minutes2:0");
                // pre($rOTSR_final_time2);
                if($c_status==1)
                {
                  $disabled=$nowTime>$rOTSR_final_time2?'disabled':'';
                  echo '<select class="form-control_select" id="age" name="age" '.$disabled.'>';
                  echo '<option value="">請選擇</option>';
                  foreach($raceSqlSubstringRows as $rass1)
                  {
                    $chk=$connectSqlRows[0]['gid']==$rass1['gid']?'selected':'';
                    echo '<option value="'.$rass1['gid'].'"'.$chk.'>'.$rass1['age'].$rass1['lowest_birth'].'後出生</option>';
                  }
                  echo '</select>';
                }
                elseif($c_status==2)
                {
                  ##決賽場次以預賽晉級的場地為主,不會更動
                  echo '<select class="form-control_select" id="age" name="age" disabled>';
                  echo '<option value="'.$connectSqlRows[0]['gid'].'">'.$connectSqlRows[0]['age'].$rass1['lowest_birth'].'後出生</option>';
                  echo '</select>';
                }
              }
              else
              {
                echo '<select class="form-control_select" id="age" name="age">';
                echo '<option value="">請選擇</option>';
                echo '</select>';
              }
              ?>
            </div>

         </div>

        </section>
        <section>
         <div class="tryout_footer">
           <div class="tryout_footer_title"><h3>比賽地址</h3></div>
           <p id="address" name="address">
             <?php
               echo !empty($connect_tid)&&trim($connect_tid)&&isset($connectSqlRows[0]['address'])?nl2br($connectSqlRows[0]['address']):'';
             ?>
           </p>

         <div class="tryout_footer_title"><h3>比賽日期</h3></div>
         <p id="race_date" name="race_date">
           <?php
             echo !empty($connect_tid)&&trim($connect_tid)&&isset($connectSqlRows[0]['race_date'])?nl2br($connectSqlRows[0]['race_date']):'';
           ?>
         </p>

         <div class="tryout_footer_title"><h3>注意事項</h3></div>
         <p id="note" name="note">
           <?php
             echo !empty($connect_tid)&&trim($connect_tid)&&isset($connectSqlRows[0]['note'])?nl2br($connectSqlRows[0]['note']):'';
           ?>
         </p>

         </div>

        </section>
        <input type="button" class="green_btn" id="sendout" name="" value="下一步 (1/2)" onclick="AddSend();">
        </form>
        <!--主體 end-->
    </body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
      <!--上面開了validate會報錯-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="dist/locales/bootstrap-datepicker.zh-CN.min.js" charset="UTF-8"></script>
    <script>
      $(document).ready(function()
      {
        $('#place').change(function()
        {
          var PNo= $('#place').val();
          $.ajax(
          {
            type: "POST",
            url:'site_index_grouping_ajax.php',
            cache: false,
            data:
            {
              'PNo':PNo,
              'r_nid':<?php echo $r_nid;?>,
              'c_status':<?php echo $c_status;?>,
            },
            error: function()
            {
              alert('Ajax request 發生錯誤1');
            },
            success: function(data)
            {
              // console.log(data);return;
              $('#age').html(data);
            }
          });

          $.ajax(
          {
            type: "POST",
            url: 'site_index_address_ajax.php',
            cache: false,
            data:
            {
              'PNo':PNo,
              'r_nid':<?php echo $r_nid;?>
            },
            error: function()
            {
              alert('Ajax request 發生錯誤2');
            },
            success: function(data)
            {
              $('#address').html(data);
            }
          });

          $.ajax(
          {
            type: "POST",
            url: 'site_index_race_date_ajax.php',
            cache: false,
            data:
            {
              'PNo':PNo,
              'r_nid':<?php echo $r_nid;?>
            },
            error: function()
            {
              alert('Ajax request 發生錯誤3');
            },
            success: function(data)
            {
              $('#race_date').html(data);
            }
          });

          $.ajax(
          {
            type: "POST",
            url: 'site_index_note_ajax.php',
            cache: false,
            data:
            {
              'PNo':PNo,
              'r_nid':<?php echo $r_nid;?>
            },
            error: function()
            {
              alert('Ajax request 發生錯誤4');
            },
            success: function(data)
            {
              $('#note').html(data);
            }
          });

        });
      });

      function AddSend()
      {
        // 在键盘按下并释放及提交后验证提交表单
        var validate=$("#addform").validate(
        {
          rules:
          {
            place: "required",
            age: "required"
          },
          messages:
          {
            place: "請輸入場區",
            age: "請輸入年齡分組",
          }
        });
        var chkRsult=validate.form();
        if (chkRsult==true)
        {
          var place=$('#place').val();
          var age=$('#age').val();

          $.ajax(
          {
            url:'site_index_ajax.php',
            type:"post",
            cache: true,
            async:false,
            datatype:"json",
            data:
            {
              "sid":place,
              "gid":age,
			        "tid":<?php echo $tid;?>,
              "r_nid":<?php echo $r_nid;?>,
              "c_status":<?php echo $c_status;?>,
            },
            error:function(data)
            {
              alert("填寫失敗");
            },
            success:function(data)
            {
              // console.log(data);return;
              var dataobj = $.parseJSON($.trim(data));
              if(dataobj.status == "success")
              {
                window.location='site_connect.php?m_id=<?php echo $m_id;?>&mid=<?php echo $mid;?>&r_nid=<?php echo $r_nid;?>&tid=<?php echo $tid;?>&c_status=<?php echo $c_status;?>&connect_tid=<?php echo $connect_tid;?>&sid='+dataobj.sid+'&gid='+dataobj.gid+'&rid='+dataobj.rid;
              }
            }
          })
        }
      }
    </script>
</html>
