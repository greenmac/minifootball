<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';

$r_nid = isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$said = isset($_GET['said']) && trim($_GET['said']) ? trim($_GET['said']) : 0;
$connect_tid = isset($_GET['connect_tid']) && trim($_GET['connect_tid']) ? trim($_GET['connect_tid']) : 0;
$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
$t_status = !empty($_GET['t_status']) && trim($_GET['t_status']) ? trim($_GET['t_status']) : 0;

if (empty($c_status)) {
    if (empty($kind)) {
        if (empty($keyword)) {
            $participateSql = 'SELECT * from player where status=1 order by pid desc';
        } elseif (!empty($keyword)) {
            $participateSql = "SELECT *
      from player
      where status=1 and concat (player.name_player,player.birth,player.id_card,player.name_parents,player.mobile,player.address,player.clothes_back_num,player.clothes_size) like'%$keyword%'
      order by player.pid desc";
        }
    } elseif (!empty($kind)) {
        $connectSql = "SELECT
    connect.team_name,
    site.said,
    site.place,
    grouping.age
    from
    (
      select *
      from connect
      where status=$kind and tid=$connect_tid
    )connect
    inner JOIN site
    on connect.sid=site.sid
    inner join grouping
    on connect.gid=grouping.gid
    ";
        $connectSqlResult = $link->prepare($connectSql);
        $connectSqlResult->execute();
        $connectSqlRows = $connectSqlResult->fetchall();

        if ($kind == 1) {
            $participateSql = "SELECT
      participate.partid,
      participate.mid as participate_mid,
      participate.tid,
      participate.r_nid,
      participate.pid,
      participate.status,
      player.mid,
      player.name_player,
      player.birth,
      player.id_card,
      player.name_parents,
      player.mobile,
      player.address,
      player.clothes_back_num,
      player.clothes_size
      FROM
      (
        SELECT *
        from participate
        where status>=1 and tid=$connect_tid
      )participate
      inner join
      (
        select *
        from player
        where status=1
      )player
      on participate.pid=player.pid
      order by player.pid desc";
        } elseif ($kind == 2) {
            $participateSql = "SELECT
      participate_finals.part_fid,
      participate_finals.mid as participate_mid,
      participate_finals.tid,
      participate_finals.r_nid,
      participate_finals.pid,
      participate_finals.status,
      player.mid,
      player.name_player,
      player.birth,
      player.id_card,
      player.name_parents,
      player.mobile,
      player.address,
      player.clothes_back_num,
      player.clothes_size
      FROM
      (
        SELECT *
        from participate_finals
        where status>=1 and tid=$connect_tid
      )participate_finals
      inner join
      (
        select *
        from player
        where status=1
      )player
      on participate_finals.pid=player.pid
      order by player.pid desc";
        }
    }
} elseif (!empty($c_status)) {
    if (!empty($keyword)) {
        if ($c_status == 1) {
            $participateSql = "SELECT *
      FROM
      (
        select participate.*
        from participate
        where status=1 and tid=$connect_tid
      )participate
      inner join
      (
        select player.*
        from player
        where status=1
      )player
      on participate.pid=player.pid
      where concat (player.name_player,player.birth,player.id_card,player.name_parents,player.mobile,player.address,player.clothes_back_num,player.clothes_size) like'%$keyword%'
      order by player.pid desc";
        } elseif ($c_status == 2) {
            $participateSql = "SELECT *
      FROM
      (
        select participate_finals.*
        from participate_finals
        where status=1 and tid=$connect_tid
      )participate_finals
      inner join
      (
        select player.*
        from player
        where status=1
      )player
      on participate_finals.pid=player.pid
      where concat (player.name_player,player.birth,player.id_card,player.name_parents,player.mobile,player.address,player.clothes_back_num,player.clothes_size) like'%$keyword%'
      order by player.pid desc";
        }
    } elseif (empty($keyword)) {
        $connectSql = "SELECT
    connect.team_name,
    site.said,
    site.place,
    grouping.age
    from
    (
      select *
      from connect
      where status=$c_status and tid=$connect_tid
    )connect
    inner JOIN site
    on connect.sid=site.sid
    inner join grouping
    on connect.gid=grouping.gid
    ";
        $connectSqlResult = $link->prepare($connectSql);
        $connectSqlResult->execute();
        $connectSqlRows = $connectSqlResult->fetchall();

        if ($c_status == 1) {
            $participateSql = "SELECT
      participate.partid,
      participate.mid as participate_mid,
      participate.tid,
      participate.r_nid,
      participate.pid,
      participate.status,
      player.mid,
      player.name_player,
      player.birth,
      player.id_card,
      player.name_parents,
      player.mobile,
      player.address,
      player.clothes_back_num,
      player.clothes_size
      FROM
      (
        SELECT *
        from participate
        where status>=1 and tid=$connect_tid
      )participate
      inner join
      (
        select *
        from player
        where status=1
      )player
      on participate.pid=player.pid
      order by player.pid desc";
        } elseif ($c_status == 2) {
            $participateSql =
      "SELECT
      participate_finals.part_fid,
      participate_finals.mid as participate_mid,
      participate_finals.tid,
      participate_finals.r_nid,
      participate_finals.pid,
      participate_finals.status,
      player.mid,
      player.name_player,
      player.birth,
      player.id_card,
      player.name_parents,
      player.mobile,
      player.address,
      player.clothes_back_num,
      player.clothes_size
      FROM
      (
        SELECT *
        from participate_finals
        where status>=1 and tid=$connect_tid
      )participate_finals
      inner join
      (
        select *
        from player
        where status=1
      )player
      on participate_finals.pid=player.pid
      order by player.pid desc";
        }
    }
}
// pre($participateSql);exit;
$participateSqlResult = $link->prepare($participateSql);
$participateSqlResult->execute();
$participateSqlRows = $participateSqlResult->fetchall();
// pre($participateSqlRows);exit;

$participateSqlNums = $participateSqlResult->rowcount();
$participatePer = 10; //每頁呈現幾筆
$participatePages = ceil($participateSqlNums / $participatePer); //(總筆數/每頁呈現幾筆),會出現幾頁
$participatePage = !isset($_GET['participatepage']) ? 1 : (int) $_GET['participatepage']; //取get值
$participateStart = ($participatePage - 1) * $participatePer; //每頁從陣列['0']開始顯示
$participateRange = 10; //每頁顯示的頁碼數
$start = (int) (($participatePage - 1) / $participateRange) * $participateRange + 1;  //$start是設定顯示每頁頁碼的開始值
$end = $start + $participateRange - 1;  //$end是設定顯示每頁頁碼的結束值
$participateSql .= " LIMIT $participateStart,$participatePer"; //陣列['0']開始顯示,呈現幾筆
$participateSqlResult = $link->prepare($participateSql);
$participateSqlResult->execute();
$participateSqlRows = $participateSqlResult->fetchall();

$mid = isset($participateSqlRows[0]['participate_mid']) ? $participateSqlRows[0]['participate_mid'] : 0;
$memberSql = "SELECT * from member where mid=$mid";
$memberSqlResult = $link->prepare($memberSql);
$memberSqlResult->execute();
$memberSqlRows = $memberSqlResult->fetchall();
$memberName = isset($memberSqlRows[0]['member_name']) ? $memberSqlRows[0]['member_name'] : 0;
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>管理球員</title>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
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
                                    echo empty($t_status) ? empty($kind) ? '<h1>管理球員</h1>' : '<h1>封存盃賽查詢/管理球員</h1>' : '<h1>封存隊伍查詢/管理球員</h1>';
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
                            echo empty($t_status) ? empty($kind) ? '<h2>管理球員</h2>' : '<h2>封存盃賽查詢/管理球員</h2>' : '<h2>封存隊伍查詢/管理球員</h2>';
                          ?>
                            <div class="" style="display:inline-block;font-size:20px;font-weight:bold;">
                              <?php echo !empty($connectSqlRows[0]['team_name']) ? '&nbsp;&nbsp;&nbsp;球隊名稱:'.$connectSqlRows[0]['team_name'].'&nbsp;&nbsp;&nbsp' : ''; ?>
                            </div>
                            <div class="" style="display:inline-block;font-size:20px;font-weight:bold;">
                              <?php
                              if (!empty($connectSqlRows[0]['said'])) {
                                  ?>
                               &nbsp;&nbsp;&nbsp;區域:
                               <?php
                                switch ($connectSqlRows[0]['said']) {
                                  case 1:
                                    echo '北部';
                                    break;
                                  case 2:
                                    echo '中部';
                                    break;
                                  case 3:
                                    echo '南部';
                                    break;
                                  case 4:
                                    echo '東部';
                                    break;
                                  case 5:
                                    echo '西部';
                                    break;
                                  default:
                                    echo 'error';
                                } ?>
                                &nbsp;&nbsp;&nbsp;
                              <?php
                              } elseif (empty($connectSqlRows[0]['said'])) {
                                  echo '';
                              }
                              ?>
                            </div>
                            <div class="" style="display:inline-block;font-size:20px;font-weight:bold;">
                              <?php echo !empty($connectSqlRows[0]['place']) ? '&nbsp;&nbsp;&nbsp;場區:'.$connectSqlRows[0]['place'].'&nbsp;&nbsp;&nbsp' : ''; ?>
                            </div>
                            <div class="" style="display:inline-block;font-size:20px;font-weight:bold;">
                              <?php echo !empty($connectSqlRows[0]['age']) ? '&nbsp;&nbsp;&nbsp;年齡分組:'.$connectSqlRows[0]['age'].'&nbsp;&nbsp;&nbsp' : ''; ?>
                            </div>
                        </div>

                        <!-- END Partial Responsive Title -->
                        <!-- Partial Responsive Content -->
                        <div class="row col-lg-12">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                      <?php $keywordKeep = !empty($keyword) && trim($keyword) ? trim($keyword) : ''; ?>
                                        <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $keywordKeep; ?>" placeholder="關鍵字">
                                    </div>
                                    <button type="button" id="search" name="search" class="btn btn-effect-ripple btn-info" style="overflow: hidden; position: relative;">查詢</button>
                                    <!-- <div class="btn btn-warning pull-right"> -->
                                      <!-- 負責人:<?php //echo $memberName;?> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                            <!-- <a id="add_participate" name="add_participate" class="btn btn-warning pull-right" onclick="//addParticipate(<?php //echo $mid;?>);">新增球員</a> -->
                            <div class="clear"></div>
                        </div>
                        <div class=" col-lg-12 clearfix" style="    margin-bottom: 20px;">
                            <table id="example-datatable" class="table table-striped table-bordered table-vcenter dataTable no-footer" role="grid" aria-describedby="example-datatable_info">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center ">球員姓名</th>
                                        <th class="text-center ">出生年月日</th>
                                        <th class="text-center ">身分證字號/護照號碼</th>
                                        <th class="text-center ">家長姓名</th>
                                        <th class="text-center ">連絡電話</th>
                                        <th class="text-center ">通訊地址</th>
                                        <th class="text-center ">球衣號碼</th>
                                        <th class="text-center ">球衣尺寸</th>
                                        <th class="text-center ">功能</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($participateSqlRows as $k) {
                                  ?>
                                    <tr role="row" class="odd">
                                        <td class="text-center"><?php echo $k['name_player']; ?></td>
                                        <td class="text-center"><?php echo $k['birth']; ?></td>
                                        <td class="text-center"><?php echo $k['id_card']; ?></td>
                                        <td class="text-center"><?php echo $k['name_parents']; ?></td>
                                        <td class="text-center"><?php echo $k['mobile']; ?></td>
                                        <td class="text-center"><?php echo $k['address']; ?></td>
                                        <td class="text-center"><?php echo $k['clothes_back_num']; ?></td>
                                        <td class="text-center"><?php echo $k['clothes_size']; ?></td>
                                        <td class="text-center">
                                          <?php
                                            if (empty($kind)) {
                                                echo '<a><input type="button" id="upd_participate'.$k['pid'].'" name="upd_participate" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" value="編輯" onclick="//updParticipate('.$k['pid'].');"></a>';
                                                echo '<a><input type="button" id="upd_seal'.$k['pid'].'" name="upd_seal"data-toggle="tooltip" class="btn btn-effect-ripple btn-xs btn-danger" style="margin-left: 5px;" value="刪除" onclick="//updSeal('.$k['pid'].');"></a>';
                                            } elseif (!empty($kind)) {
                                                echo '<a><input type="button" id="upd_participate'.$k['pid'].'" name="upd_participate" data-toggle="tooltip" class="btn btn-xs btn-success" style="margin-left: 5px;" value="查詢" onclick="//updParticipate('.$k['pid'].');"></a>';
                                            } ?>
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
                                   echo $participatePage == 1 ? '' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage=1>首頁</a></li>'.'　';
                                   echo $participatePage == 1 ? '' : '<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage='.($participatePage - 1).'><i class="fa fa-chevron-left"></i></a>'.'　'; //上一頁
                                   if ($participatePages <= $participateRange) {
                                       for ($i = $start; $i <= $participatePages; ++$i) {
                                           echo $i == $participatePage ? '<li class="active"><a>'.$i.'</a></li>'.'　' : '<li><a href="?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage='.$i.'">'.$i.'</a></li>'.'　'; //當前顯示頁不會有連結,且放大
                                       }
                                   } else { //如果總頁數大於每頁要顯示的頁碼數
                                       //如果目前的頁數大於5，預設定為第6頁開始，每頁的頁碼就往前移動1位  ex 目前的頁數為第6頁，所以輸出 2 3 4 5 6 7 8 9 10 11，如果是第7頁就輸出 3 4 5 6 7 8 9 10 11 12，依此類推
                                       if ($participatePage > 5) {
                                           $end = $participatePage + 5;  //每頁結尾的頁碼就+5
                                       if ($end > $participatePages) {  //如果每頁結尾的頁碼大於總頁數
                                         $end = $participatePages;  //就將每頁結尾的頁碼改寫為最後一頁
                                       }
                                           $start = $end - 9;  //將每頁開頭的頁碼設為結尾的頁碼-9
                                           //開始輸出頁碼
                                           for ($i = $start; $i <= $end; ++$i) {
                                               echo $i == $participatePage ? '<li class="active"><a>'.$i.'</a></li>'.'　' : '<li><a href="?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage='.$i.'">'.$i.'</a></li>'.'　'; //當前顯示頁不會有連結,且放大
                                           }
                                       } else { //如果目前的頁數小於5
                                       if ($end > $participatePages) { //如果每頁結尾的頁碼大於總頁數
                                         $end = $participatePages;  //就將每頁結尾的頁碼改寫為最後一頁
                                       }
                                           //開始輸出頁碼
                                       for ($i = $start; $i <= $end; ++$i) { //在目前頁數裡本身頁數的頁碼就不要連結，如果不是就加上連結
                                         echo $i == $participatePage ? '<li class="active"><a>'.$i.'</a></li>'.'　' : '<li><a href="?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage='.$i.'">'.$i.'</a></li>'.'　'; //當前顯示頁不會有連結,且放大
                                       }
                                       }
                                   }
                                   echo $participatePage == $participatePages ? '' : '　'.'<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage='.($participatePage + 1).'><i class="fa fa-chevron-right"></i></a></li>'; //下一頁
                                   echo $participatePage == $participatePages ? '' : '　'.'<li><a href=?r_nid='.$r_nid.'&said='.$said.'&c_status='.$c_status.'&kind='.$kind.'&t_status='.$t_status.'&connect_tid='.$connect_tid.'&keyword='.$keyword.'&participatepage='.$participatePages.'>末頁</a></li>';
                                   echo '<li><a>共'.$participatePages.'頁</a></li>';  //顯示目前總頁數
                                   echo '<li><a>共'.$participateSqlNums.'筆</a></li>'; //顯示總筆數
                                ?>
                              </ul>
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
    <?php
      //抓id的方式觸發
      foreach ($participateSqlRows as $pa) {
          $mid = !empty($pa['mid']) ? $pa['mid'] : 0;
          $pid = !empty($pa['pid']) ? $pa['pid'] : 0;
          $partid = !empty($pa['partid']) ? $pa['partid'] : 0;
          $part_fid = !empty($pa['part_fid']) ? $pa['part_fid'] : 0; ?>
        $("#upd_participate<?php echo $pid; ?>").click(function()
        {
          window.location='backstage_player.php?<?php echo 'mid='.$mid.'&pid='.$pid.'&c_status='.$c_status.'&r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&participatepage='.$participatePage.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword; ?>'
        }),
        $("#upd_seal<?php echo $pid; ?>").click(function()
        {
          $.ajax(
          {
            url:"backstage_participate_seal_ajax.php",
            type:"post",
            cache:true,
            async:false,
            datatype:"json",
            data:
            {
              "r_nid":<?php echo $r_nid; ?>,
              "said":<?php echo $said; ?>,
              "connect_tid":<?php echo $connect_tid; ?>,
              "c_status":<?php echo $c_status; ?>,
              "pid":<?php echo $pid; ?>,
              "partid":<?php echo $partid; ?>,
              "part_fid":<?php echo $part_fid; ?>,
            },
            error:function(data)
            {
              alert("填寫失敗");
            },
            success:function(data)
            {
              // console.log(data);return;
              var dataobj = $.parseJSON($.trim(data));
              if( dataobj.status == "success" )
              {
                alert("刪除　"+dataobj.name_player+"　成功");
                window.location="backstage_participate.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status.'&keyword='.$keyword; ?>";
              }
            }
          });
        }),
      <?php
      }
      ?>
    $(function()
    {
      $('#add_participate').click(function()
      {
        window.location='backstage_player.php?<?php echo 'mid='.$mid.'&pid=0&r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status.'&keyword='.$keyword;?>'
      })
      $('#search').click(function()
      {
        const keyword=$('#keyword').val();
        if(!keyword)
        {
          return false;
        }
        else
        {
          window.location='backstage_participate.php?<?php echo 'r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status;?>'+'&keyword='+keyword;
        }
      })
    })
    </script>
</body>

</html>
