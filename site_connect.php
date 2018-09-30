<?php
include_once('link.php');
include_once('function.php');

$m_id=isset($_GET['m_id'])&&trim($_GET['m_id'])?trim($_GET['m_id']):0;
$mid=isset($_GET['mid'])&&trim($_GET['mid'])?trim($_GET['mid']):'';
$tid=isset($_GET['tid'])&&trim($_GET['tid'])?trim($_GET['tid']):'';
$connect_tid=!empty($_GET['connect_tid'])&&trim($_GET['connect_tid'])?trim($_GET['connect_tid']):0;
$rid=isset($_GET['rid'])&&trim($_GET['rid'])?trim($_GET['rid']):'';
$r_nid=isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']):'';
$sid=isset($_GET['sid'])&&trim($_GET['sid'])?trim($_GET['sid']):'';
$gid=isset($_GET['gid'])&&trim($_GET['gid'])?trim($_GET['gid']):'';
$c_status=isset($_GET['c_status'])&&trim($_GET['c_status'])?trim($_GET['c_status']):'';

$connectSql="SELECT * from connect where mid=$mid and tid=$tid and status>0";
$connectSqlResult=$link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows=$connectSqlResult->fetchall();
$cid=isset($connectSqlRows[0]['cid'])&&trim($connectSqlRows[0]['cid'])?trim($connectSqlRows[0]['cid']):0;

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
        <header class="head_title">
            <div class="title">
                <h2>隊伍及球隊聯絡資訊</h2>
            </div>
        </header>
        <!--表頭 end-->
        <!--主體 -->
        <form class="" method="post" id="addform" name="addform" action="">
        <section class="tryout_connection">
            <div class="tryout_connection_title">
                <h3><i class="far fa-flag"></i>隊伍名稱</h3>
            </div>
            <div class="blue_form">
                <div class="">
                  <?php
                  $disabled=$nowTime>$rOTSR_final_time?'disabled':'';
                  $disabled2=$nowTime>$rOTSR_final_time?1:0;
                  $connectSqlRowsTeam_name=!empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['team_name']:'';
                  if($c_status==1)
                  {
                    echo '<input type="text" class="form-control" id="team_name" name="team_name" value="'.$connectSqlRowsTeam_name.'" '.$disabled.'>';
                  }
                  elseif($c_status==2)
                  {
                    echo '<p class="form-control" id="team_name" name="team_name">'.$connectSqlRowsTeam_name.'</p>';
                  }
                  ?>
                    <!-- <input type="text" class="form-control" id="team_name" name="team_name" value="<?php //echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['team_name']:'';?>"> -->
                </div>
            </div>
            <div class="tryout_connection_title">
                <h3><i class="far fa-file-alt"></i>領隊資訊</h3>
                <small>*下列資訊請填寫真實資料</small>
            </div>
            <div class="green_form">
                <div class="form-group">
                    <label for="">姓名</label>
                    <input type="text" class="form-control" id="leader_name" name="leader_name" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['leader_name']:'';?>" <?php echo $disabled;?>>
                </div>
                <div class="form-group">
                    <label for="">行動電話</label>
                    <input type="text" class="form-control" id="leader_mobile" name="leader_mobile" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['leader_mobile']:'';?>" <?php echo $disabled;?>>
                </div>
                <div class="form-group">
                    <label for="">email</label>
                    <input type="text" class="form-control"  id="leader_email" name="leader_email" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['leader_email']:'';?>" <?php echo $disabled;?>>
                </div>
            </div>
            <div class="tryout_connection_title">
                <h3><i class="far fa-file-alt"></i>教練資訊</h3>
                <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" onclick="theLeader(1)"> 同領隊資訊
                </label>
            </div>
            <div class="blue_form">
                <div class="form-group">
                    <label for="">姓名</label>
                    <input type="text" class="form-control" id="coach_name" name="coach_name" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['coach_name']:'';?>" <?php echo $disabled;?>>
                </div>
                <div class="form-group">
                    <label for="">電話</label>
                    <input type="text" class="form-control" id="coach_mobile" name="coach_mobile" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['coach_mobile']:'';?>" <?php echo $disabled;?>>
                </div>
                <div class="form-group">
                    <label for="">email</label>
                    <input type="text" class="form-control" id="coach_email" name="coach_email" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['coach_email']:'';?>" <?php echo $disabled;?>>
                </div>
            </div>
            <div class="tryout_connection_title">
                <h3><i class="far fa-file-alt"></i>管理人員資訊</h3>
                <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" onclick="theLeader(2)"> 同領隊資訊
                </label>
                <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" onclick="theLeader(3)"> 同教練資訊
                </label>
            </div>
            <div class="green_form green_form_bottom">
                <div class="form-group">
                    <label for="">姓名</label>
                    <input type="text" class="form-control" id="supervise_name" name="supervise_name" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['supervise_name']:'';?>" <?php echo $disabled;?>>
                </div>
                <div class="form-group">
                    <label for="">電話</label>
                    <input type="text" class="form-control" id="supervise_mobile" name="supervise_mobile" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['supervise_mobile']:'';?>" <?php echo $disabled;?>>
                </div>
                <div class="form-group">
                    <label for="">email</label>
                    <input type="text" class="form-control" id="supervise_email" name="supervise_email" value="<?php echo !empty($connect_tid)&&trim($connect_tid)?$connectSqlRows[0]['supervise_email']:'';?>" <?php echo $disabled;?>>
                </div>
            </div>
        </section>
        <!-- <a class="green_btn">下一步 (2/2)</a> -->
        <input class="green_btn" type="button" id="sendout" name="" value="下一步 (2/2)" onclick="AddSend();">
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
    function theLeader(same)
    {
      let str='';
      let str2='';
      switch(same)
      {
        case 1:
          str='leader_';
          str2='coach_';
          break;
        case 2:
          str='leader_';
          str2='supervise_';
          break;
        case 3:
          str='coach_';
          str2='supervise_';
          break;
        // default:
        //   alert('沒有符合的條件');
      }
      const name=document.getElementById(str+'name').value;
      const mobile=document.getElementById(str+'mobile').value;
      const email=document.getElementById(str+'email').value;
      document.getElementById(str2+'name').value=name;
      document.getElementById(str2+'mobile').value=mobile;
      document.getElementById(str2+'email').value=email;
    }

    function AddSend()
    {
      // validate.isMobile.手機號碼驗證
      jQuery.validator.addMethod("isMobile", function(value, element)
      {
        const length = value.length;
        const mobile = /^09[0-9]{2}[0-9]{6}$/;
        return this.optional(element) || (length == 10 && mobile.test(value));
      }, "請正確填寫您的手機");
      // validate.isEmail.email驗證
      jQuery.validator.addMethod("isEmail", function(value, element)
      {
        const email = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
        return this.optional(element) || (email.test(value));
      }, "請正確填寫您的email");
      // 在键盘按下并释放及提交后验证提交表单
      const validform=$("#addform").validate(
      {
        rules:
        {
          team_name:"required",
          leader_name:"required",
          leader_mobile:
          {
            required:true,
            isMobile:true,
            minlength: 10,
            maxlength: 10
          },
          leader_email:
          {
            required:true,
            isEmail:true,
          },
          coach_name:"required",
          coach_mobile:
          {
            required:true,
            isMobile:true,
            minlength: 10,
            maxlength: 10
          },
          coach_email:
          {
            required:true,
            isEmail:true,
          },
          supervise_name:"required",
          supervise_mobile:
          {
            required:true,
            isMobile:true,
            minlength: 10,
            maxlength: 10
          },
          supervise_email:
          {
            required:true,
            isEmail:true,
          }
        },
        messages:
        {
          team_name:"請輸入隊伍名稱",
          leader_name:"請輸入領隊姓名",
          leader_mobile:
          {
            required:"請輸入領隊手機",
            isMobile:"請輸入09開頭的10碼號碼"
          },
          leader_email:
          {
            required:"請輸入領隊email",
            isEmail:"請輸入xxx@xxx.xxx"
          },
          coach_name:"請輸入教練姓名",
          coach_mobile:
          {
            required:"請輸入教練手機",
            isMobile:"請輸入09開頭的10碼號碼"
          },
          coach_email:
          {
            required:"請輸入教練email",
            isEmail:"請輸入xxx@xxx.xxx"
          },
          supervise_name:"請輸入管理人員姓名",
          supervise_mobile:
          {
            required:"請輸入管理人員手機",
            isMobile:"請輸入09開頭的10碼號碼"
          },
          supervise_email:
          {
            required:"請輸入管理人員email",
            isEmail:"請輸入xxx@xxx.xxx"
          }
        },
      });

      const chkResult=validform.form();
      if(chkResult==true)
      {
        const link='site_connect_ajax.php';
        const place=$('#place').val();
        const age=$('#age').val();
        const team_name=$('#team_name').val();
        const leader_name=$('#leader_name').val();
        const leader_mobile=$('#leader_mobile').val();
        const leader_email=$('#leader_email').val();
        const coach_name=$('#coach_name').val();
        const coach_mobile=$('#coach_mobile').val();
        const coach_email=$('#coach_email').val();
        const supervise_name=$('#supervise_name').val();
        const supervise_mobile=$('#supervise_mobile').val();
        const supervise_email=$('#supervise_email').val();

        $.ajax(
        {
          url: link,
          type:"post",
          cache: true,
          async:false,
          datatype:"json",
          data:
          {
            "connect_tid":<?php echo $connect_tid;?>,
            "cid":<?php echo $cid;?>,
            "mid":<?php echo $mid;?>,
            "tid":<?php echo $tid;?>,
            "rid":<?php echo $rid;?>,
            "r_nid":<?php echo $r_nid;?>,
            "sid":<?php echo $sid;?>,
            "gid":<?php echo $gid;?>,
            "status":<?php echo $c_status?>,
            "team_name":team_name,
            "leader_name":leader_name,
            "leader_mobile":leader_mobile,
            "leader_email":leader_email,
            "coach_name":coach_name,
            "coach_mobile":coach_mobile,
            "coach_email":coach_email,
            "supervise_name":supervise_name,
            "supervise_mobile":supervise_mobile,
            "supervise_email":supervise_email
          },
          error:function(data)
          {
            alert("填寫失敗");
          },
          success:function(data)
          {
            // console.log(data);return;
            const disabled2=<?php echo $disabled2;?>;
            const dataobj = $.parseJSON($.trim(data));
            if( dataobj.status == "success" )
            {
              if(disabled2)
              {
                alert("返回盃賽報名列表");
              }
              else if(!disabled2)
              {
                alert("填寫成功");
              }
              window.location='index.php?m_id=<?php echo $m_id;?>&mid=<?php echo $mid;?>';
            }
            else if(dataobj.status == "jump")
            {
              alert("請稍等跳轉頁面");
              window.location='index.php?m_id=<?php echo $m_id;?>&mid=<?php echo $mid;?>';
            }
          }
        });
      }
    }
    </script>
</html>
