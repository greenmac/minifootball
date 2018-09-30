<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
header('Content-Type:text/html; charset=utf-8');

$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 1;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
$t_status = !empty($_GET['t_status']) && trim($_GET['t_status']) ? trim($_GET['t_status']) : 0;

if (empty($kind)) {
    $race_nameSql = "SELECT
  race_name.r_nid,
  race_name.appmaker_name,
  race_name.name,
  race_name.sku,
  race_name.status,
  race.rid,
  race.r_nid,
  race.sid,
  race.status,
  race.kind,
  race.race_date,
  race.gid,
  race.begin_date,
  race.begin_hour,
  race.begin_minutes,
  race.final_date,
  race.final_hour,
  race.final_minutes,
  race.note,
  race.start_time
  FROM
  (
    SELECT *
    FROM race
    WHERE status=$c_status
    group by r_nid
    order by rid desc
  )race
  inner join race_name
  on race.r_nid=race_name.r_nid
  order by race_name.r_nid desc";
} elseif (!empty($kind)) {
    $c_status = 0;
    $race_nameSql = "SELECT
  race_name.r_nid,
  race_name.appmaker_name,
  race_name.name,
  race_name.sku,
  race_name.status,
  race.rid,
  race.r_nid,
  race.sid,
  race.status,
  race.kind,
  race.race_date,
  race.gid,
  race.begin_date,
  race.begin_hour,
  race.begin_minutes,
  race.final_date,
  race.final_hour,
  race.final_minutes,
  race.note,
  race.start_time,
  race.end_time
  FROM
  (
    SELECT *
    FROM race
    WHERE status=$c_status and kind=$kind
    group by r_nid
  )race
  inner join race_name
  on race.r_nid=race_name.r_nid
  order by race.end_time desc";
}
// pre($race_nameSql);exit;
$race_nameSqlResult = $link->prepare($race_nameSql);
$race_nameSqlResult->execute();
$race_nameSqlRows = $race_nameSqlResult->fetchall();

$race_nameSqlNums = $race_nameSqlResult->rowcount();
$race_nameSqlPer = 10; //每頁呈現幾筆
$race_nameSqlPages = ceil($race_nameSqlNums / $race_nameSqlPer); //(總筆數/每頁呈現幾筆),會出現幾頁
$race_nameSqlPage = !isset($_GET['race_nameSqlPage']) ? 1 : (int) $_GET['race_nameSqlPage']; //取get值
$race_nameSqlStart = ($race_nameSqlPage - 1) * $race_nameSqlPer; //每頁從陣列['0']開始顯示
$race_nameSqlRange = 10; //每頁顯示的頁碼數
$start = (int) (($race_nameSqlPage - 1) / $race_nameSqlRange) * $race_nameSqlRange + 1;  //$start是設定顯示每頁頁碼的開始值
$end = $start + $race_nameSqlRange - 1;  //$end是設定顯示每頁頁碼的結束值
$race_nameSql .= " LIMIT $race_nameSqlStart,$race_nameSqlPer"; //陣列['0']開始顯示,呈現幾筆
$race_nameSqlResult = $link->prepare($race_nameSql);
$race_nameSqlResult->execute();
$race_nameSqlRows = $race_nameSqlResult->fetchall();
// pre($race_nameSqlRows);exit;
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>盃賽報名管理</title>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script> -->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> -->
    <!-- <script src="http://malsup.github.com/jquery.form.js"></script> -->
    <style>
      a{text-decoration:none}
    </style>
    <meta name="description" content="AppUI is a Web App Bootstrap Admin Template created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
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
                                <a href="backstage_index.php?<?php echo 'c_status='.$c_status; ?>" class=""> <i class="fa fa-caret-right sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">盃賽報名管理</span></a>
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
                                  echo empty($kind) ? '<h1>盃賽報名管理</h1>' : '<h1>封存盃賽查詢</h1>';
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Widgets Header -->
                    <!-- Partial Responsive Block -->
                    <div class="block">
                        <!-- Partial Responsive Title -->
                        <div class="block-title">
                        <?php
                          echo empty($kind) ? '<h2>盃賽報名管理</h2>' : '<h2>封存盃賽查詢</h2>';
                        ?>
                        </div>
                        <!-- END Partial Responsive Title -->
                        <!-- Partial Responsive Content -->
                        <div class="row col-lg-12">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <input type="text"  id="keyword" name="keyword" class="form-control" placeholder="票券編號/負責人名稱/電話/E-mail/球隊名稱">
                                    </div>
                                    <div class="col-md-4" style="">
                                        <select id="said" name="said" class="form-control">
                                          <option value="0">全部區域</option>
                                          <option value="1">北區</option>
                                          <option value="2">中區</option>
                                          <option value="3">南區</option>
                                          <option value="4">東區</option>
                                          <option value="5">西區</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-effect-ripple btn-info" id="search" name="search" style="overflow: hidden; position: relative;">查詢</button>
                                </div>
                            </div>
                            <div class="col-lg-6">
                              <?php echo $c_status == 2 ? '<p style="color:red;font-size:18px;">盃賽進入決賽階段，管理員請先編輯決賽資訊，在選擇進入決賽隊伍。</p>' : ''; ?>
                              <?php echo !empty($kind) ? '<p style="color:#ff5809;font-size:18px;">此區為<封存盃賽查詢></p>' : ''; ?>
                            </div>
                            <?php
                              switch ($c_status) {
                                case 1:
                                  echo '<a class="btn btn-warning pull-right" id="add_race" name="add_race">新增盃賽活動</a>';
                                  break;
                                case 2:
                                  echo '';
                                  break;
                                case 0:
                                  echo '';
                                  break;
                                default:
                                  echo 'error';
                              }
                            ?>
                            <div class="clear"></div>
                        </div>
                        <div class=" col-lg-12 clearfix" style="    margin-bottom: 20px;">
                            <!-- <ul class="nav nav-tabs" data-toggle="tabs" style="    margin-bottom: 10px;"> -->
                                <!-- <li class="active"><a href="#tabs_1">預賽</a></li> -->
                                <!-- <li><a href="#tabs_2">決賽</a></li> -->
                            <!-- </ul> -->

                            <ul class="nav nav-tabs" data-toggle="tabs" style="margin-bottom: 10px;">
                              <?php
                              if (empty($kind)) {
                                  switch ($c_status) {
                                  case 1:
                                    echo '
                                    <a class="btn btn-xs btn-success">預賽</a>
                                    <a class="active" onclick="onHref(2);return false;" href="#");>決賽</a>
                                    ';
                                    break;
                                  case 2:
                                    echo '
                                    <a class="active" onclick="onHref(1);return false;" href="#");>預賽</a>
                                    <a class="btn btn-effect-ripple btn-xs btn-danger">決賽</a>
                                    ';
                                    break;
                                  default:
                                    echo 'ERROR';
                                }
                              } elseif (!empty($kind)) {
                                  switch ($kind) {
                                  case 1:
                                    echo '
                                    <a class="btn btn-xs btn-success">預賽(已封存)</a>
                                    <a class="active" onclick="onHref(4);return false;" href="#");>決賽(已封存)</a>
                                    ';
                                    break;
                                  case 2:
                                    echo '
                                    <a class="active" onclick="onHref(3);return false;" href="#");>預賽(已封存)</a>
                                    <a class="btn btn-effect-ripple btn-xs btn-danger">決賽(已封存)</a>
                                    ';
                                    break;
                                  default:
                                    echo 'ERROR';
                                }
                              }
                              ?>
                                <!-- <li><a class="active" onclick="onHref('backstage_index.php?c_status=1');return false;" href="#");>預賽</a></li>
                                <li><a class="active" onclick="onHref('backstage_index.php?c_status=2');return false;" href="#");>決賽</a></li> -->
                            </ul>
                        </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs_1">
                                    <!-- <table id="example-datatable" class="table table-striped table-bordered table-vcenter dataTable no-footer" role="grid" aria-describedby="example-datatable_info"> -->
                                        <!-- <thead> -->
                                            <!-- <tr role="row"> -->
                                                <!-- <th class="text-center ">盃賽開放時間</th> -->
                                                <!-- <th class="text-center ">盃賽票券名稱</th> -->
                                                <!-- <th class="text-center ">活動報名數量</th> -->
                                                <!-- <th class="text-center ">功能</th> -->
                                            <!-- </tr> -->
                                        <!-- </thead> -->
                                        <!-- <tbody> -->
                                            <!-- <tr role="row" class="odd"> -->
                                                <!-- <td class="text-center">2018/01/15</td> -->
                                                <!-- <td class="text-center">MiniSoccer迷你足球領導春季盃</td> -->
                                                <!-- <td class="text-center"><a href="apply_quantity.html">10</a></td> -->
                                                <!-- <td class="text-center"> -->
                                                    <!-- <a href="apply_add.html" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;">編輯</a> -->
                                                    <!-- <a href="" data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;">封存</a></td> -->
                                            <!-- </tr> -->
                                            <!-- <tr role="row" class="odd"> -->
                                               <!--  <td class="text-center">2018/01/14</td> -->
                                                <!-- <td class="text-center">2017MINI CUP U6</td> -->
                                                <!-- <td class="text-center"><a style="margin:0 5px;"  href="apply_quantity.html">北(10)</a><a style="margin:0 5px;"  href="apply_quantity.html">中(10)</a><a style="margin:0 5px;"  href="apply_quantity.html">南(10)</a></td> -->
                                                <!-- <td class="text-center"> -->
                                                    <!-- <a href="apply_add.html" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;">編輯</a> -->
                                                    <!-- <a href="" data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;">封存</a></td> -->
                                            <!-- </tr> -->
                                        <!-- </tbody> -->
                                    <!-- </table> -->
                                    <!-- <div class="text-center"> -->
                                        <!-- <ul class="pagination"> -->
                                            <!-- <li><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></li> -->
                                            <!-- <li><a href="javascript:void(0)">1</a></li> -->
                                            <!-- <li class="active"><a href="javascript:void(0)">2</a></li> -->
                                            <!-- <li><a href="javascript:void(0)">3</a></li> -->
                                            <!-- <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></li> -->
                                        <!-- </ul> -->
                                    <!-- </div> -->
                                <!-- </div> -->
                                <div class="tab-pane" id="tabs_2">
                                    <table id="example-datatable" class="table table-striped table-bordered table-vcenter dataTable no-footer" role="grid" aria-describedby="example-datatable_info">
                                        <thead>
                                            <tr role="row">
                                                <!-- <th class="text-center ">盃賽開放時間</th> -->
                                                <th class="text-center ">AppMaker名稱</th>
                                                <th class="text-center ">盃賽票券名稱</th>
                                                <th class="text-center ">活動報名數量</th>
                                                <th class="text-center ">功能</th>
                                            </tr>
                                        </thead>
                                        <!-- <tbody> --><!--套版用-->
                                            <!-- <tr role="row" class="odd"> -->
                                                <!-- <td class="text-center">2018/01/15</td> -->
                                                <!-- <td class="text-center">MiniSoccer迷你足球領導春季盃</td> -->
                                                <!-- <td class="text-center"><a  href="apply_quantity.html">10</a></td> -->
                                                <!-- <td class="text-center"> -->
                                                    <!-- <a href="apply_add_finals.html" data-toggle="tooltip" class="btn btn-xs btn-warning" style="margin-left: 5px;">尚未設定報名期限</a> -->
                                                    <!-- <a href="" data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;">封存</a></td> -->
                                            <!-- </tr> -->
                                            <!-- <tr role="row" class="odd"> -->
                                                <!-- <td class="text-center">2018/01/14</td> -->
                                                <!-- <td class="text-center">2017MINI CUP U6</td> -->
                                                <!-- <td class="text-center"><a style="margin:0 5px;"   href="apply_quantity.html">北(10)</a><a style="margin:0 5px;"   href="apply_quantity.html">中(10)</a><a   href="apply_quantity.html" style="margin:0 5px;">南(10)</a></td> -->
                                                <!-- <td class="text-center"> -->
                                                    <!-- <a href="apply_add.html" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;">編輯</a> -->
                                                    <!-- <a href="" data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;">封存</a></td> -->
                                            <!-- </tr> -->
                                        <!-- </tbody> -->

                                        <tbody>
                                            <?php
                                              foreach ($race_nameSqlRows as $r_n1) {
                                                  ?>
                                            <tr role="row" class="odd">
                                              <td class="text-center"><?php echo $r_n1['appmaker_name']; ?><?php ?></td>
                                              <td class="text-center"><?php echo $r_n1['name']; ?><?php ?></td>
                                              </div>

                                              <?php
                                                $r_nid = $r_n1['r_nid'];
                                                  if (empty($kind)) {
                                                      $siteCount = "SELECT
                                                  connect.r_nid,
                                                  site.said,
                                                  connect.status,
                                                  count(site.said) as count_said
                                                  FROM
                                                  (
                                                    select *
                                                    FROM connect
                                                    where status=$c_status
                                                  )connect
                                                  inner join site
                                                  on connect.sid=site.sid
                                                  where connect.r_nid=$r_nid
                                                  group by connect.r_nid,site.said
                                                  order by said";
                                                  } elseif (!empty($kind)) {
                                                      $siteCount = "SELECT
                                                    connect.r_nid,
                                                    site.said,
                                                    connect.status,
                                                    count(site.said) as count_said
                                                    FROM
                                                    (
                                                      select *
                                                      FROM connect
                                                      where status=$kind
                                                    )connect
                                                    inner join site
                                                    on connect.sid=site.sid
                                                    where connect.r_nid=$r_nid
                                                    group by connect.r_nid,site.said
                                                    order by said";
                                                  }
                                                  $siteCountResult = $link->prepare($siteCount);
                                                  $siteCountResult->execute();
                                                  $siteCountRows = $siteCountResult->fetchall(); ?>
                                              <td class="text-center">
                                                <?php foreach ($siteCountRows as $s1) {
                                                      $said = $s1['said'];
                                                      $count_said = $s1['count_said'];
                                                      $c_status = empty($kind) ? $s1['status'] : 0;
                                                      // pre($s1);
                                                      switch ($said) {
                                                      case 1:
                                                        echo '<a style="margin:0 5px;" href="backstage_connect.php?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&keyword=">北('.$count_said.')</a>';
                                                        break;
                                                      case 2:
                                                        echo '<a style="margin:0 5px;" href="backstage_connect.php?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&keyword=">中('.$count_said.')</a>';
                                                        break;
                                                      case 3:
                                                        echo '<a style="margin:0 5px;" href="backstage_connect.php?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&keyword=">南('.$count_said.')</a>';
                                                        break;
                                                      case 4:
                                                        echo '<a style="margin:0 5px;" href="backstage_connect.php?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&keyword=">中('.$count_said.')</a>';
                                                        break;
                                                      case 5:
                                                        echo '<a style="margin:0 5px;" href="backstage_connect.php?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&keyword=">西('.$count_said.')</a>';
                                                        break;
                                                    }
                                                  } ?>
                                                </td>
                                              <td class="text-center">
                                                  <?php
                                                    if (empty($kind)) {
                                                        $raceBegin_date = "SELECT begin_date from race where status=$c_status and appear=1 and r_nid=$r_nid";
                                                        $raceBegin_dateResult = $link->prepare($raceBegin_date);
                                                        $raceBegin_dateResult->execute();
                                                        $raceBegin_dateRows = $raceBegin_dateResult->fetchall();
                                                        // $r_nB2=!empty($raceBegin_dateRows[0]['begin_date'])?$raceBegin_dateRows[0]['begin_date']:0;
                                                        if ($c_status == 1) {
                                                            echo !empty($raceBegin_dateRows) ? '<input type="button" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" id="edit'.$r_nid.'" name="edit" value="編輯" onclick="clickEdit('.$r_nid.')">' : '<input type="button" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" id="edit'.$r_nid.'" name="edit" value="尚未設定報名期限" onclick="clickEdit('.$r_nid.')">';
                                                        } elseif ($c_status) {
                                                            echo !empty($raceBegin_dateRows) ? '<input type="button" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" id="edit'.$r_nid.'" name="edit" value="編輯" onclick="clickEdit('.$r_nid.')">' : '<input type="button" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" id="edit'.$r_nid.'" name="edit" value="尚未設定報名期限" onclick="clickEdit('.$r_nid.')">';
                                                        }
                                                        echo '<input type="button" data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;" id="race_name_seal'.$r_n1['r_nid'].'" name="seal" value="封存" onclick="clickSeal('.$r_n1['r_nid'].');">';
                                                    } elseif (!empty($kind)) {
                                                        echo '<input type="button" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" id="edit'.$r_nid.'" name="edit" value="查詢" onclick="clickEdit('.$r_nid.')">';
                                                    } ?>
                                                    <input type="button" data-toggle="tooltip" class="active" style="margin-left: 5px;" id="excel_send" name="excel_send" value="產出excel表" onclick="excelSend(<?php echo $r_n1['r_nid']; ?>);">
                                              </td>
                                            </tr>
                                            <?php
                                              }?>
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <!-- <ul class="pagination">
                                            <li><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></li>
                                            <li><a href="javascript:void(0)">1</a></li>
                                            <li class="active"><a href="javascript:void(0)">2</a></li>
                                            <li><a href="javascript:void(0)">3</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></li>
                                        </ul> -->

                                        <ul class="pagination">
                                        <?php
                                          echo $race_nameSqlPage == 1 ? '' : '<li><a href=?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage=1>首頁</i></a></li>'.'　';
                                          echo $race_nameSqlPage == 1 ? '' : '<li><a href=?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage='.($race_nameSqlPage - 1).'><i class="fa fa-chevron-left"></i></a></li>'.'　'; //上一頁
                                          if ($race_nameSqlPages <= $race_nameSqlRange) { //開始輸出頁碼
                                            for ($i = 1; $i <= $race_nameSqlPages; ++$i) {
                                                echo $i == $race_nameSqlPage ? '<li class="active"><a>'.$i.'</a></li>' : '<li><a href="?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage='.$i.'">'.$i.'</a></li>'; //當前顯示頁不會有連結,且放大
                                            }
                                          } else { //如果總頁數大於每頁要顯示的頁碼數
                                              //如果目前的頁數大於5，預設定為第6頁開始，每頁的頁碼就往前移動1位  ex 目前的頁數為第6頁，所以輸出 2 3 4 5 6 7 8 9 10 11，如果是第7頁就輸出 3 4 5 6 7 8 9 10 11 12，依此類推
                                              if ($race_nameSqlPage > 5) {
                                                  $end = $race_nameSqlPage + 5;  //每頁結尾的頁碼就+5
                                              if ($end > $race_nameSqlPages) {  //如果每頁結尾的頁碼大於總頁數
                                                $end = $race_nameSqlPages;  //就將每頁結尾的頁碼改寫為最後一頁
                                              }
                                                  $start = $end - 9;  //將每頁開頭的頁碼設為結尾的頁碼-9
                                              //開始輸出頁碼
                                              for ($i = $start; $i <= $end; ++$i) { //在目前頁數裡本身頁數的頁碼就不要連結，如果不是就加上連結
                                                echo $i == $race_nameSqlPage ? '<li class="active"><a>'.$i.'</a></li>' : '<li><a href="?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage='.$i.'">'.$i.'</a></li>'; //當前顯示頁不會有連結,且放大
                                              }
                                              } else { //如果目前的頁數小於5
                                              if ($end > $race_nameSqlPages) { //如果每頁結尾的頁碼大於總頁數
                                                $end = $race_nameSqlPages;  //就將每頁結尾的頁碼改寫為最後一頁
                                              }
                                                  //開始輸出頁碼
                                              for ($i = $start; $i <= $end; ++$i) { //在目前頁數裡本身頁數的頁碼就不要連結，如果不是就加上連結
                                                echo $i == $race_nameSqlPage ? '<li class="active"><a>'.$i.'</a></li>' : '<li><a href="?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage='.$i.'">'.$i.'</a></li>'; //當前顯示頁不會有連結,且放大
                                              }
                                              }
                                          }
                                          echo $race_nameSqlPage == $race_nameSqlPages ? '' : '　'.'<li><a href=?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage='.($race_nameSqlPage + 1).'><i class="fa fa-chevron-right"></i></a></li>'; //下一頁
                                          echo $race_nameSqlPage == $race_nameSqlPages ? '' : '　'.'<li><a href=?c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&race_nameSqlPage='.$race_nameSqlPages.'>末頁</i></a></li>';
                                          echo '<li><a>共'.$race_nameSqlPages.'頁</a></li>';  //顯示目前總頁數
                                          echo '<li><a>共'.$race_nameSqlNums.'筆</a></li>'; //顯示總筆數
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- END Partial Responsive Content -->
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
    // !window.jQuery && document.write(decodeURI('%3Cscript src="js/vendor/jquery-2.1.1.min.js"%3E%3C/script%3E'));
    </script>
    <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/app.js"></script>
    <script>
    $(function()
    {
      $('#search').click(function()
      {
        const said=$('#said').val();
        const keyword=$('#keyword').val();
        if(!keyword)
        {
          return false;
        }
        else
        {
          window.location="backstage_connect.php?<?php echo 'c_status='.$c_status.'&kind='.$kind; ?>"+"&keyword="+keyword;
        }
      })

      $('#add_race').click(function()
      {
        window.location='backstage_race_edit.php?<?php echo 'c_status=0'; ?>';
      })
    })

    function onHref(to)
    {
      switch(to)
      {
        case 1:
          window.location='backstage_index.php?c_status=1&kind=0';
          break;
        case 2:
          window.location='backstage_index.php?c_status=2&kind=0';
          break;
        case 3:
          window.location='backstage_index.php?c_status=0&kind=1';
          break;
        case 4:
          window.location='backstage_index.php?c_status=0&kind=2';
          break;
        default:
          alert('ERROR');
      }
    }

    function clickEdit(edit_r_nid)
    {
      window.location='backstage_race_edit.php?r_nid='+edit_r_nid+'&c_status=<?php echo $c_status.'&kind='.$kind; ?>';
    }

    function clickSeal(seal_r_nid)
    {
      $.ajax(
      {
        url:'backstage_index_seal_ajax.php',
        type:"post",
        cache: true,
        async:false,
        datatype:"json",
        data:
        {
          "r_nid":seal_r_nid,
          "c_status":<?php echo $c_status; ?>,
        },
        error:function(data)
        {
          alert("編輯失敗");
        },
        success:function(data)
        {
          // console.log(data);return;
          var dataobj=$.parseJSON($.trim(data));
          if(dataobj.status=="success")
          {
            alert("已將 "+dataobj.name+" 盃賽封存");
            window.location="backstage_index.php?<?php echo 'c_status='.$c_status; ?>";
          }
        }
      });
    }

    //產出excel表
    function excelSend(excel_r_nid)
    {
      window.location='backstage_excel.php?r_nid='+excel_r_nid+'<?php echo '&c_status='.$c_status.'&kind='.$kind; ?>';
    }

    </script>
 </body>
</html>
