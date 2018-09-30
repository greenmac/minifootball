<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
$r_nid = isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$said = isset($_GET['said']) && trim($_GET['said']) ? trim($_GET['said']) : 0;
$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$connect_tid = isset($_GET['connect_tid']) && trim($_GET['connect_tid']) ? trim($_GET['connect_tid']) : 0;
$mid = isset($_GET['mid']) && trim($_GET['mid']) ? trim($_GET['mid']) : 0;
$pid = isset($_GET['pid']) && trim($_GET['pid']) ? trim($_GET['pid']) : 0;
$keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
$t_status = !empty($_GET['t_status']) && trim($_GET['t_status']) ? trim($_GET['t_status']) : 0;

if (empty($kind)) {
    $connectSql = "SELECT
  race_name.name,
  member.member_name,
  connect.cid,
  connect.mid,
  connect.tid,
  connect.rid,
  connect.r_nid,
  connect.sid,
  connect.gid,
  connect.status as c_status,
  connect.calculate,
  connect.team_name,
  connect.leader_name,
  connect.leader_mobile,
  connect.leader_email,
  connect.coach_name,
  connect.coach_mobile,
  connect.coach_email,
  connect.supervise_name,
  connect.supervise_mobile,
  connect.supervise_email
  from
  (
    select *
    from connect
    where r_nid=$r_nid and status=$c_status and tid=$connect_tid
  )connect
  inner join race_name
  on connect.r_nid=race_name.r_nid
  inner join member
  on connect.mid=member.mid";
} elseif (!empty($kind)) {
    $connectSql = "SELECT
  race_name.name,
  member.member_name,
  connect.cid,
  connect.mid,
  connect.tid,
  connect.rid,
  connect.r_nid,
  connect.sid,
  connect.gid,
  connect.status as c_status,
  connect.calculate,
  connect.team_name,
  connect.leader_name,
  connect.leader_mobile,
  connect.leader_email,
  connect.coach_name,
  connect.coach_mobile,
  connect.coach_email,
  connect.supervise_name,
  connect.supervise_mobile,
  connect.supervise_email
  from
  (
    select *
    from connect
    where r_nid=$r_nid and kind=$kind and tid=$connect_tid
  )connect
  inner join race_name
  on connect.r_nid=race_name.r_nid
  inner join member
  on connect.mid=member.mid";
}
// pre($connectSql);exit;
$connectSqlResult = $link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows = $connectSqlResult->fetchall(PDO::FETCH_BOTH);
$cid = $connectSqlRows[0]['cid'];
$rid = $connectSqlRows[0]['rid'];
$sid = $connectSqlRows[0]['sid'];
$gid = $connectSqlRows[0]['gid'];
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>編輯球隊聯絡資訊</title>
    <meta name="description" content="AppUI is a Web App Bootstrap Admin Template created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
      <!--vaildate-->
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css" rel="stylesheet">
      <!--日期選擇器css-->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
      <!--日期選擇器上面那個會跟validate衝突,不要開-->
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
      <!--日期選擇器-->
    <style>
     .error{color:red;}
    </style>
    <link rel="shortcut icon" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
    <!-- END Icons -->
    <!-- Stylesheets -->
    <!-- Bootstrap is included in its original form, unaltered -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Related styles of various icon packs and plugins -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
    <link rel="stylesheet" href="css/main.css">
    <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
    <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
    <link rel="stylesheet" href="css/themes.css">
    <link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css">
    <!-- END Stylesheets -->
    <!-- Modernizr (browser feature detection library) -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script src="SpryAssets/SpryAccordion.js" type="text/javascript"></script>
</head>

<body>
    <!-- Page Wrapper -->
    <!-- In the PHP version you can set the following options from inc/config file -->
    <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
    <div id="page-wrapper" class="page-loading">
        <!-- Preloader -->
        <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
        <!-- Used only if page preloader enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
        <div class="preloader">
            <div class="inner">
                <!-- Animation spinner for all modern browsers -->
                <div class="preloader-spinner themed-background hidden-lt-ie10"></div>
                <!-- Text for IE9 -->
                <h3 class="text-primary visible-lt-ie10"><strong>Loading..</strong></h3>
            </div>
        </div>
        <!-- END Preloader -->
        <!-- Page Container -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
                Available #page-container classes:

                'sidebar-light'                                 for a light main sidebar (You can add it along with any other class)

                'sidebar-visible-lg-mini'                       main sidebar condensed - Mini Navigation (> 991px)
                'sidebar-visible-lg-full'                       main sidebar full - Full Navigation (> 991px)

                'sidebar-alt-visible-lg'                        alternative sidebar visible by default (> 991px) (You can add it along with any other class)

                'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
                'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

                'fixed-width'                                   for a fixed width layout (can only be used with a static header/main sidebar layout)

                'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links (You can add it along with any other class)
            -->
        <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
            <!-- Main Sidebar -->
            <div id="sidebar">
                <!-- Sidebar Brand -->
                <div id="sidebar-brand" class="themed-background">
                    <a href="index.html" class="sidebar-title">
                            <i class="fa fa-cube"></i> <span class="sidebar-nav-mini-hide">喬立達數位</span>
                        </a>
                </div>
                <!-- END Sidebar Brand -->
                <!-- Wrapper for scrolling functionality -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Sidebar Navigation -->
                        <ul class="sidebar-nav">
                            <li>
                                <a href="backstage_index.php?<?php echo 'c_status='.$c_status; ?>" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">盃賽報名管理</span></a>
                            </li>
                            <li>
                                <a href="backstage_participate.php?c_status=&keyword=" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">球員管理</span></a>
                            </li>
                            <li>
                                <a href="backstage_index.php?c_status=&keyword=&kind=1" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">封存盃賽查詢</span></a>
                            </li>
                            <li>
                                <a href="backstage_connect.php?c_status=&keyword=&kind=1&t_status=1" class=" "> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">封存隊伍查詢</span></a>
                            </li>
                        </ul>
                        <!-- END Sidebar Navigation -->
                    </div>
                    <!-- END Sidebar Content -->
                </div>
                <!-- END Wrapper for scrolling functionality -->
            </div>
            <!-- END Main Sidebar -->
            <!-- Main Container -->
            <div id="main-container">
                <!-- Header -->
                <!-- In the PHP version you can set the following options from inc/config file -->
                <!--
                        Available header.navbar classes:

                        'navbar-default'            for the default light header
                        'navbar-inverse'            for an alternative dark header

                        'navbar-fixed-top'          for a top fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                            'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added

                        'navbar-fixed-bottom'       for a bottom fixed header (fixed main sidebar with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                            'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
                    -->
                <header class="navbar navbar-inverse navbar-fixed-top">
                    <!-- Left Header Navigation -->
                    <ul class="nav navbar-nav-custom">
                        <!-- Main Sidebar Toggle Button -->
                        <li>
                            <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');">
                                    <i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                                    <i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                                </a>
                        </li>
                        <!-- END Main Sidebar Toggle Button -->
                        <!-- Header Link -->
                        <!-- END Header Link -->
                    </ul>
                    <!-- END Left Header Navigation -->
                    <!-- Right Header Navigation -->
                    <ul class="nav navbar-nav-custom pull-right">
                        <!-- User Dropdown -->
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="img/logo.png" alt="avatar">
                                </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="logout.php">
                                            <i class="fa fa-power-off fa-fw pull-right"></i>
                                            Log out
                                        </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END User Dropdown -->
                    </ul>
                    <!-- END Right Header Navigation -->
                </header>
                <!-- END Header -->
                <!-- Page content -->
                <div id="page-content">
                    <!-- Widgets Header -->
                    <div class="content-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="header-section">
                                  <?php
                                    echo empty($t_status) ? empty($kind) ? '<h1>盃賽報名管理/編輯球隊聯絡資訊</h1>' : '<h1>封存盃賽管理/球隊聯絡資訊</h1>' : '<h1>封存隊伍查詢/球隊聯絡資訊</h1>';
                                  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Widgets Header -->
                    <!-- Partial Responsive Block -->
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                            <!-- Clickable Wizard Block -->
                            <div class="block">
                                <!-- Clickable Wizard Title -->
                                <div class="block-title">
                                  <?php
                                    echo empty($t_status) ? empty($kind) ? '<h2>編輯球隊聯絡資訊</h2>' : '<h2>封存盃賽管理/球隊聯絡資訊</h2>' : '<h2>封存隊伍查詢/球隊聯絡資訊</h2>';
                                  ?>
                                </div>
                                <!-- END Clickable Wizard Title -->
                                <!-- Clickable Wizard Content -->
                                <form class="form-horizontal form-bordered" method="post" id="addform" name="addform" action="">
                                    <!-- Second Step -->
                                    <div id="clickable-second" class="">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">隊伍名稱：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="team_name" name="team_name" value="'.$connectSqlRows[0]['team_name'].'" class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['team_name'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">盃賽票券名稱：</label>
                                            <div class="col-md-6">
                                              <p class="form-control-static"><?php echo $connectSqlRows[0]['name']; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">報名負責人：</label>
                                            <div class="col-md-6">
                                              <p class="form-control-static"><?php echo $connectSqlRows[0]['member_name']; ?></p>
                                            </div>
                                        </div>
                                       <h3 class="text-center" >領隊資訊</h3>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">姓名：</label>
                                            <div class="col-md-6">
                                              <?php
                                                echo empty($kind) ? '<input type="text" id="leader_name" name="leader_name" value="'.$connectSqlRows[0]['leader_name'].'" class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['leader_name'].'</p>';
                                              ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">連絡電話：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="leader_mobile" name="leader_mobile" value="'.$connectSqlRows[0]['leader_mobile'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['leader_mobile'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">Email：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="leader_email" name="leader_email" value="'.$connectSqlRows[0]['leader_email'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['leader_email'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <h3 class="text-center" >教練資訊</h3>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" onclick="theLeader(1)"> 同領隊資訊' : '';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">姓名：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="coach_name" name="coach_name" value="'.$connectSqlRows[0]['coach_name'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['coach_name'].'</p>';
                                            ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">連絡電話：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="coach_mobile" name="coach_mobile" value="'.$connectSqlRows[0]['coach_mobile'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['coach_mobile'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">Email：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="coach_email" name="coach_email" value="'.$connectSqlRows[0]['coach_email'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['coach_email'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                           <h3 class="text-center" >管理人員資訊</h3>
                                           <div class="form-group">
                                             <div class="col-md-6">
                                             <?php
                                              echo empty($kind) ?
                                              '<input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" onclick="theLeader(2)"> 同領隊資訊
                                              <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" onclick="theLeader(3)"> 同教練資訊'
                                              : '';
                                             ?>
                                             </div>
                                           </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">姓名：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="supervise_name" name="supervise_name" value="'.$connectSqlRows[0]['supervise_name'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['supervise_name'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">連絡電話：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="supervise_mobile" name="supervise_mobile" value="'.$connectSqlRows[0]['supervise_mobile'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['supervise_mobile'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">Email：</label>
                                            <div class="col-md-6">
                                            <?php
                                              echo empty($kind) ? '<input type="text" id="supervise_email" name="supervise_email" value="'.$connectSqlRows[0]['supervise_email'].'"  class="form-control" placeholder="">' : '<p>'.$connectSqlRows[0]['supervise_email'].'</p>';
                                            ?>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- END Second Step -->
                                    <!-- Form Buttons -->
                                    <div class="form-group form-actions">
                                        <div class="col-md-8 col-md-offset-4">
                                        <?php
                                          if (empty($kind)) {
                                              echo '<input type="button" id="add_send" name="add_send" value="儲存" class="btn btn-effect-ripple btn-primary" onclick="//addSend();">';
                                              echo '<input type="button" id="cancel" name="cancel" value="取消" class="btn btn-effect-ripple btn-danger">';
                                          } elseif (!empty($kind)) {
                                              echo '<input type="button" id="cancel" name="cancel" value="返回上一頁" class="btn btn-effect-ripple btn-danger">';
                                          }
                                        ?>
                                        </div>
                                    </div>
                                    <!-- END Form Buttons -->
                                </form>
                                <!-- END Clickable Wizard Content -->
                            </div>
                            <!-- END Clickable Wizard Block -->
                        </div>
                    </div>
                    <!-- END Partial Responsive Block -->
                </div>
                <!-- END Page Content -->
            </div>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
    </div>
    <!-- END Page Wrapper -->
    <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file (Remove 'http:' if you have SSL) -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
    !window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-2.1.1.min.js"%3E%3C/script%3E'));
    </script>
    <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/app.js"></script>
    <!-- Load and execute javascript code used only in this page -->
    <script src="js/pages/readyDashboard.js"></script>
    <!-- <script>
    $(function() { ReadyDashboard.init(); });
    </script> -->
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

    $().ready(function()
    {
      $('#add_send').click(function()
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
          const link='backstage_connect_list_ajax.php';
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
              "connect_tid":<?php echo $connect_tid; ?>,
              "cid":<?php echo $cid; ?>,
              "mid":<?php echo $mid; ?>,
              "tid":<?php echo $connect_tid; ?>,
              "rid":<?php echo $rid; ?>,
              "r_nid":<?php echo $r_nid; ?>,
              "sid":<?php echo $sid; ?>,
              "gid":<?php echo $gid; ?>,
              "status":<?php echo $c_status; ?>,
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
              alert("Ajax request 發生錯誤");
            },
            success:function(data)
            {
              //console.log(data);
              //return;
              const dataobj = $.parseJSON($.trim(data));
              if( dataobj.status == "success" )
              {
                alert("填寫成功");
                window.location='backstage_connect.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status.'&keyword='.$keyword.'&kind='.$kind; ?>';
              }
            }
          });
        }
      })
    })

    //區域連動
    $(document).ready(function()
    {
          //利用jQuery的ajax把縣市編號(CNo)傳到Town_ajax.php把相對應的區域名稱回傳後印到選擇區域(鄉鎮)下拉選單
          $('#smcid').change(function()
          {
              var CNo= $('#smcid').val();
              $.ajax(
              {
                  type: "POST",
                  url: 'backstage_player_city_ajax.php',
                  cache: false,
                  data:{'CNo':CNo},
                  error: function(data)
                  {
                    // console.log(data);
                    // return;
                      alert('Ajax request 發生錯誤');
                  },
                  success: function(data)
                  {
                      $('#smaid').html(data);
                  }
              });
          });
      });


    //日期選擇器
    $(document).ready(function()
    {
      var opt=
      {
         dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
         dayNamesMin:["日","一","二","三","四","五","六"],
         monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
         monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
         prevText:"上月",
         nextText:"次月",
         weekHeader:"週",
         showMonthAfterYear:true,
         dateFormat:"yy-mm-dd"
      };
      $("#birth").datepicker(opt);
    });

    $(function()
    {
      $('#cancel').click(function()
      {
        window.location='backstage_connect.php?<?php echo '&r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status.'&keyword='.$keyword.'&kind='.$kind.'&t_status='.$t_status;?>';
      })
    })
    </script>
</body>

</html>
