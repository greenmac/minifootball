<?php
include_once('link.php');
include_once('function.php');
header("Content-Type:text/html; charset=utf-8");

$m_id=isset($_GET['m_id'])&&trim($_GET['m_id'])?trim($_GET['m_id']):0;
$mid=isset($_GET['mid'])&&trim($_GET['mid'])?trim($_GET['mid']):0;
$r_nid=isset($_GET['r_nid'])&&trim($_GET['r_nid'])?trim($_GET['r_nid']):0;
$rid=isset($_GET['rid'])&&trim($_GET['rid'])?trim($_GET['rid']):0;
$tid=isset($_GET['tid'])&&trim($_GET['tid'])?trim($_GET['tid']):0;
$c_status=isset($_GET['c_status'])&&trim($_GET['c_status'])?trim($_GET['c_status']):0;
$connect_tid=!empty($_GET['connect_tid'])&&trim($_GET['connect_tid'])?trim($_GET['connect_tid']):0;
$participate_pid=!empty($_GET['participate_pid'])&&trim($_GET['participate_pid'])?trim($_GET['participate_pid']):0;
$gid=isset($_GET['gid'])&&trim($_GET['tid'])?trim($_GET['gid']):0;
$alt=!empty($_GET['alt'])&&trim($_GET['alt'])?trim($_GET['alt']):0;

if($c_status==1)
{
  ##預賽球員名單
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
    order by pid desc";
  $participateSqlResult=$link->prepare($participateSql);
  $participateSqlResult->execute();
  $participateSqlRows=$participateSqlResult->fetchall();
  $participateSqlNums=$participateSqlResult->rowcount();
  if($participateSqlNums<7)//最低幾人
  {
    $factor=1;
    $factorMessage='"指派球員不滿7人"';
    echo '<script>alert('.$factorMessage.')</script>';
  }
  elseif($participateSqlNums>10)//最高幾人
  {
    $factor=2;
    $factorMessage='"指派球員超過10人"';
    echo '<script>alert('.$factorMessage.')</script>';
  }
  else
  {
    $factor=3;
    $factorMessage='"已指派球員'.$participateSqlNums.'人"';
  }
}
elseif($c_status==2)
{
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
    order by participate.pid desc";
  $oldCountParticipateResult=$link->prepare($oldCountParticipate);
  $oldCountParticipateResult->execute();
  $oldCountParticipateRows=$oldCountParticipateResult->fetchall();
  $oldCountParticipateRums=$oldCountParticipateResult->rowcount();
  // pre($oldCountParticipateRums);

  ##決賽球員名單
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
    order by pid desc";
  $participate_finalsSqlResult=$link->prepare($participate_finalsSql);
  $participate_finalsSqlResult->execute();
  $participate_finalsSqlRows=$participate_finalsSqlResult->fetchall();
  $participate_finalsSqlNums=$participate_finalsSqlResult->rowcount();
  // pre($participate_finalsSqlRows);
  // pre($participate_finalsSqlNums);

  if($oldCountParticipateRums<7)//預賽出賽人數最少幾人限制
  {
    $factor=4;
    $factorMessage='"預賽球員不可低於7人"';//不可低於預賽出賽人數7人
    echo '<script>alert('.$factorMessage.');return;</script>';
  }
  elseif($oldCountParticipateRums>=7)
  {
    if($participate_finalsSqlNums<7)//最低幾人
    {
      $factor=1;
      $factorMessage='"指派球員不滿7人"';
      echo '<script>alert('.$factorMessage.')</script>';
    }
    elseif($participate_finalsSqlNums>10)//最高幾人
    {
      $factor=2;
      $factorMessage='"指派球員超過10人"';
      echo '<script>alert('.$factorMessage.')</script>';
    }
    else
    {
      $factor=3;
      $factorMessage='"已指派球員'.$participate_finalsSqlNums.'人"';
    }
  }
}

##盃賽名稱
$raceSql="SELECT name from race_name where r_nid=$r_nid and status=1";
$raceSqlResult=$link->prepare($raceSql);
$raceSqlResult->execute();
$raceSqlRows=$raceSqlResult->fetch();

##票卷號碼
$ticketSql="SELECT number from ticket where tid=$tid";
$ticketSqlResult=$link->prepare($ticketSql);
$ticketSqlResult->execute();
$ticketSqlRows=$ticketSqlResult->fetch();

##決賽新增人數限制
$connectCalculate="SELECT * from connect where tid=$tid and status>0";
$connectCalculateResult=$link->prepare($connectCalculate);
$connectCalculateResult->execute();
$connectCalculateRows=$connectCalculateResult->fetchall();
$connectCalculateRid=$connectCalculateRows[0]['rid'];
$connectCalculateOld=$connectCalculateRows[0]['calculate'];

##決賽可新增人數
$raceSqlCalculate="SELECT * from race where appear=1 and rid=$connectCalculateRid";
$raceSqlCalculateResult=$link->prepare($raceSqlCalculate);
$raceSqlCalculateResult->execute();
$raceSqlCalculateRows=$raceSqlCalculateResult->fetchall();
$raceSqlCalculateRowsCalculate=$raceSqlCalculateRows[0]['calculate'];

# 查詢報名場次是否超過報名時間
$raceOverTimeSql=
"SELECT *
from
(
  select rid,sid,final_date,final_hour,final_minutes
  from race
  where rid=$rid and status=$c_status and appear=1
)race
";
$raceOverTimeSqlRe=$link->prepare($raceOverTimeSql);
$raceOverTimeSqlRe->execute();
$raceOverTimeSqlRw=$raceOverTimeSqlRe->fetchall(PDO::FETCH_ASSOC);
$rOTSR_final_date=!empty($raceOverTimeSqlRw[0]['final_date'])?$raceOverTimeSqlRw[0]['final_date']:0;
$rOTSR_final_hour=!empty($raceOverTimeSqlRw[0]['final_hour'])?$raceOverTimeSqlRw[0]['final_hour']:0;
$rOTSR_final_minutes=!empty($raceOverTimeSqlRw[0]['final_minutes'])?$raceOverTimeSqlRw[0]['final_minutes']:0;

$nowTime=strtotime(date('Y/m/d H:i:s'));
$rOTSR_final_time=strtotime("$rOTSR_final_date $rOTSR_final_hour:$rOTSR_final_minutes:0");

$disabled=$nowTime>$rOTSR_final_time?'disabled':'';
$disabled2=$nowTime>$rOTSR_final_time?1:0;
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <title>迷你足球報名系統</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1" >
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
    <link rel="stylesheet" type="text/css" href="dist/css/bootstrap-datepicker3.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <body>
        <!--表頭-->
        <header class="head_title">
            <div class="title">
                <h2>盃賽資訊</h2>
            </div>
            <div class="tryout_row">
                <!-- <div class="race main_red_bg"> -->
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
                    <li>
                        <div class="tickets_data_text">
                            球員
                            <span class="done">
                              <?php
                                switch($c_status)
                                {
                                  case 1:
                                    echo !empty($participateSqlRows[0]['participate_status'])?'<font color="#00FF00">已指派</font>':'<font color="#FF0000">未指派</font>';
                                    break;
                                  case 2:
                                    echo !empty($participate_finalsSqlRows[0]['participate_finals_status'])?'<font color="#00FF00">已指派</font>':'<font color="#FF0000">未指派</font>';
                                    break;
                                  default:
                                    echo 'error';
                                }
                              ?>
                            </span>
                        </div>
                    </li>
                    <li>
                        <div class="tickets_number">票券號碼<span><?php echo $ticketSqlRows['number']?></span></div>
                    </li>
                </ul>
                <div class="tickets_title"><?php echo $raceSqlRows['name']?></div>
            </div>
            <?php
              if(empty($disabled2))
              {
                echo '<a class="add_player" href="appoint_list.php?m_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&connect_tid='.$connect_tid.'&gid='.$gid.'">';
                  // <!-- 指派球員 <i class="fas fa-plus-circle pull-right plus"></i></a> -->
              }
              elseif(!empty($disabled2))
              {
                echo '<a class="add_player" onclick="overTime();">';
              }
            ?>

            <!-- <a class="add_player" href="appoint_list.php?<?php //echo 'm_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&connect_tid='.$connect_tid.'&gid='.$gid;?>"> -->
              指派球員 <i class="fas fa-plus-circle pull-right plus"></i></a>
            <div class="player_list_title">
                <h3>球員列表</h3>
                <small>
                    指派球員上限 <span>10人</span>  下限<span> 7人</span>
                </small>
            </div>

            <div class="player_list_title">
                <small>
                  <?php
                    switch($c_status)
                    {
                      case 1:
                      $peopleCount=10-$participateSqlNums<0?0:10-$participateSqlNums;
                        echo '已指派球員 <span>'.$participateSqlNums.'人</span>
                        可再指派球員 <span>'.$peopleCount.'人</span>';
                        break;
                      case 2:
                        $peopleCount=10-$participate_finalsSqlNums<0?0:10-$participate_finalsSqlNums;
                        echo '已指派球員 <span>'.$participate_finalsSqlNums.'人</span>
                        可再指派球員 <span>'.$peopleCount.'人</span>';
                        break;
                      default:
                        break;
                    }
                  ?>
                </small>
            </div>

            <div class="player_list_title">
                <small>
                  <?php
                    switch($c_status)
                    {
                      case 1:
                        echo '';
                        break;
                      case 2:
                        echo '決賽球員可更替 <span>'.$connectCalculateOld.'人</span>';
                        break;
                      default:
                        echo 'error';
                    }
                  ?>
                </small>
            </div>

        </header>
        <!--表頭 end-->
        <!--主體 -->
        <section class="main">
            <!-- <div class="leary_text">您尚未指派球員！</div>-->
            <ul class="player_edit_btn">
                <!-- <li><a data-toggle="modal" data-target=".bs-example-modal-lg">葉瑞君<i class="fas fa-times-circle pull-right circle"></i></a></li> -->
                <?php
                  switch($c_status)
                  {
                    case 1:
                      foreach($participateSqlRows as $p)
                      {
                        $pid=$p['pid'];
                        $partid=$p['partid'];
                        if(empty($disabled2))
                        {
                          echo '<li><a>'.$p['name_player'].'<i class="fas fa-times-circle pull-right circle"  id="'.$p['participate_pid'].'" onclick="cancel('.$pid.','.$partid.')"></i></a></li>';
                        }
                        elseif(!empty($disabled2))
                        {
                          echo '<li><a>'.$p['name_player'].'<i class="fas fa-times-circle pull-right circle"  id="'.$p['participate_pid'].'" onclick="overTime();"></i></a></li>';
                        }
                      };
                      break;
                    case 2:
                      foreach($participate_finalsSqlRows as $p_f)
                      {
                        $pid=$p_f['pid'];
                        $part_fid=$p_f['part_fid'];
                        if(empty($disabled2))
                        {
                          echo '<li><a>'.$p_f['name_player'].'<i class="fas fa-times-circle pull-right circle"  id="'.$p_f['participate_finals_pid'].'" onclick="cancel('.$pid.','.$part_fid.')"></i></a></li>';
                        }
                        elseif(!empty($disabled2))
                        {
                          echo '<li><a>'.$p_f['name_player'].'<i class="fas fa-times-circle pull-right circle"  id="'.$p_f['participate_finals_pid'].'" onclick="overTime();"></i></a></li>';
                        }
                      };
                      break;
                    default:
                      echo 'error';
                  }
                ?>
            </ul>
        </section>
        <?php
        switch($c_status)
        {
          case 1:
            echo '<input class="green_btn" id="addsend" type="button" id="sendout" name="" value="確認">';
            break;
          case 2:
            echo '<input class="green_btn" id="addsend" type="button" id="sendout" name="" value="確認">';
            echo '<a  class="" data-toggle="modal" data-target=".bs-example-modal-lg"  id="race_rule" ></a>';
            break;
        }
        ?>
        <!-- <input class="green_btn" data-toggle="modal" data-target=".bs-example-modal-lg" type="button" id="sendout" name="" value="確認"> -->
        <!--主體 end-->
    </body>
     <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal_msg">
                    <div class="modal_title">
                        規則說明
                    </div>
                    <ul>
                        <li>1.更換球員次數以增加「新的」 球員為準
                        </li>
                        <li>2.新增的球員不得是本次活動 任一球隊已登錄過的球員。
                        </li>
                        <li>3.更替後隊員數必須最少7位、 最多10位。
                        </li>
                        <li>4.更替次數請參閱"決賽球員 更替限制"。
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" class="ok_btn">我知道了</a>
                </div>
            </div>

        </div>

    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/all.js"></script>
    <script type="text/javascript" src="dist/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="dist/locales/bootstrap-datepicker.zh-CN.min.js" charset="UTF-8"></script>
    <script>
      let c_status=<?php echo $c_status;?>;
      let alt=<?php echo $alt;?>;
      $(document).ready()
      {
        if(c_status==2)
        {
          if(alt)
          {
            $('#race_rule').trigger('click');
          }
        }
      }

      function cancel(pid,partid)
      {
        if(c_status==1)
        {
          const participateSqlNums=<?php echo !empty($participateSqlNums)?$participateSqlNums:0;?>;
          if(participateSqlNums<=7)
          {
            alert('不可低於7人');
            return false;
          }
          else
          {
            const link='appoint_index_ajax.php';
            $.ajax(
            {
              url:link,
              type:'post',
              cache:true,
              async:false,
              datatype:'json',
              data:
              {
                "tid":<?php echo $tid;?>,
                "pid":pid,
                "c_status":<?php echo $c_status;?>,
                "r_nid":<?php echo $r_nid;?>,
                "partid":partid,
              },
              error:function(data)
              {
                alert("失敗");
              },
              success:function(data)
              {
                // console.log(data);return;
                const dataobj=$.parseJSON($.trim(data));
                if(dataobj.status=="success")
                {
                  alert("已移除球員　"+dataobj.name_player);
                  window.location='appoint_index.php?<?php echo 'm_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&participate_pid='.$participate_pid.'&gid='.$gid;?>';
                }
              }
            })
          }
        }
        else if(c_status==2)
        {
          let oldCountParticipateRums=<?php echo !empty($oldCountParticipateRums)?$oldCountParticipateRums:0;?>;
          let participate_finalsSqlNums=<?php echo !empty($participate_finalsSqlNums)?$participate_finalsSqlNums:0;?>;
          if(oldCountParticipateRums<7)
          {
            alert('預賽球員不可低於7人');return;//不可低於預賽出賽人數7人
          }
          else
          {
            if(participate_finalsSqlNums<=7)
            {
              alert('不可低於7人');
              return false;
            }
            else
            {
              const link='appoint_index_ajax.php';
              $.ajax(
              {
                url:link,
                type:'post',
                cache:true,
                async:false,
                datatype:'json',
                data:
                {
                  "tid":<?php echo $tid;?>,
                  "pid":pid,
                  "c_status":<?php echo $c_status;?>,
                  "r_nid":<?php echo $r_nid;?>,
                  "partid":partid,
                },
                error:function(data)
                {
                  alert("失敗");
                },
                success:function(data)
                {
                  // console.log(data);return;
                  const dataobj=$.parseJSON($.trim(data));
                  if(dataobj.noremove=="noremove")
                  {
                    alert('預賽球員不可低於7人');return;//不可低於預賽出賽人數7人
                  }
                  if(dataobj.status=="success")
                  {
                    alert("已移除球員　"+dataobj.name_player);
                    window.location='appoint_index.php?<?php echo 'm_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&participate_pid='.$participate_pid.'&gid='.$gid;?>';
                  }
                }
              })
            }
          }
        }
      }

      $(function(){
        var c_status=<?php echo $c_status;?>;
        var factor=<?php echo $factor;?>;
        var factorMessage=<?php echo $factorMessage;?>;
        $('#addsend').click(function()
        {
          switch(factor)
          {
            case 1:
              alert(factorMessage);
              return;
              break;
            case 2:
              alert(factorMessage);
              return;
              break;
            case 3:
              window.location='index.php?<?php echo 'm_id='.$m_id.'&mid='.$mid;?>';
              break;
            case 4:
              alert(factorMessage);
              return;
              break;
            default:
              return;
          }
        })
      })

      function overTime()
      {
        alert('超過報名時間');
      }
    </script>
</html>
