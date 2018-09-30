<?php
include_once('link.php');
include_once('function.php');
$m_id=isset($_GET['m_id'])&&trim($_GET['m_id'])?trim($_GET['m_id']):0;
$mid=isset($_GET['mid']) && trim($_GET['mid']) ? trim($_GET['mid']):0;
$playerSql="SELECT * from player where mid=$mid and status=1 order by pid desc";
$playerSqlResult=$link->prepare($playerSql);
$playerSqlResult->execute();
$playerSqlRows=$playerSqlResult->fetchall();
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

    <body>
        <!--表頭-->
        <header class="head_title">
            <div class="title">
                <h2>新增/編輯球員資訊</h2>
            </div>
            <a class="add_player"  href="player_add.php?<?php echo 'm_id='.$m_id.'&mid='.$mid;?>">新增球員 <i class="fas fa-plus-circle pull-right plus"></i></a>
            <h3 class="">球員資訊</h3>
        </header>
        <!--表頭 end-->
        <!--主體 -->
        <section class="main">
            <!-- <div class="leary_text" id="fill_in"><?php //echo !empty($playerSqlRows[0]['mid'])&&trim($playerSqlRows[0]['mid'])?'已填寫':'未填寫';?></div> -->
              <ul class="player_edit_btn">
                <?php foreach($playerSqlRows as $p){;?>
                  <?php $pid=$p['pid'];?>
                  <li><a href="player_update.php?<?php echo 'pid='.$pid.'&m_id='.$m_id.'&mid='.$mid;?>">
                    <?php echo $p['name_player'];?>
                    <i class="far fa-edit pull-right"></i></a></li>
                <?php };?>
              </ul>
        </section>
        <input class="green_btn" id="addsend" type="button" id="sendout" name="" value="返回首頁" onclick="//AddSend();">
        <!--主體 end-->

    </body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script src="js/all.js"></script>
    <script>
      $().ready(function(){
        $('#addsend').click(function(){
          window.location="index.php?<?php echo 'm_id='.$m_id.'&mid='.$mid;?>";
        })
      })
    </script>

</html>
