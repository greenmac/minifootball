<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';

$r_nid = isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$said = isset($_GET['said']) && trim($_GET['said']) ? trim($_GET['said']) : 0;
$connect_tid = isset($_GET['connect_tid']) && trim($_GET['connect_tid']) ? trim($_GET['connect_tid']) : 0;
$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$mid = isset($_GET['mid']) && trim($_GET['mid']) ? trim($_GET['mid']) : 0;
$pid = isset($_GET['pid']) && trim($_GET['pid']) ? trim($_GET['pid']) : 0;
$participatePage = isset($_GET['participatepage']) && trim($_GET['participatepage']) ? trim($_GET['participatepage']) : 0;
$keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
$t_status = !empty($_GET['t_status']) && trim($_GET['t_status']) ? trim($_GET['t_status']) : 0;

$playerSql = "SELECT
member.mid,
member.member_name,
member.member_phone,
member.card_num,
player.pid,
player.mid,
player.status,
player.name_player,
player.birth,
player.id_card,
player.name_parents,
player.mobile,
player.smcid,
player.smaid,
player.address,
player.clothes_back_num,
player.clothes_size
FROM
(
  SELECT *
  from player
  where pid=$pid and mid=$mid and status=1
)player
inner join member
on player.mid=member.mid";
$playerSqlResult = $link->prepare($playerSql);
$playerSqlResult->execute();
$playerSqlRows = $playerSqlResult->fetchall(PDO::FETCH_BOTH);
$smcid = isset($playerSqlRows[0]['smcid']) ? $playerSqlRows[0]['smcid'] : '';

$sys_map_citySql = 'SELECT * FROM sys_map_city where smsid=1';
$sys_map_citySqlResult = $link->prepare($sys_map_citySql);
$sys_map_citySqlResult->execute();
$sys_map_citySqlRows = $sys_map_citySqlResult->fetchall();

$sys_map_areaSql = "SELECT * from sys_map_area where smcid=$smcid";
$sys_map_areaSqlResult = $link->prepare($sys_map_areaSql);
$sys_map_areaSqlResult->execute();
$sys_map_areaSqlRows = $sys_map_areaSqlResult->fetchall();
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
                                  echo empty($t_status) ? empty($kind) ? empty($pid) ? '<h1>盃賽報名管理/管理球員/新增球員</h1>' : '<h1>盃賽報名管理/管理球員/編輯球員</h1>' : '<h1>封存盃賽查詢/查詢球員</h1>' : '<h1>封存隊伍查詢/查詢球員</h1>';
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
                                  echo empty($t_status) ? empty($kind) ? empty($pid) ? '<h2>盃賽報名管理/管理球員/新增球員</h2>' : '<h2>盃賽報名管理/管理球員/編輯球員</h2>' : '<h2>封存盃賽查詢/查詢球員</h2>' : '<h2>封存隊伍查詢/查詢球員</h2>';
                                  ?>
                                </div>
                                <!-- END Clickable Wizard Title -->
                                <!-- Clickable Wizard Content -->
                                <form class="form-horizontal form-bordered" method="post" id="addform" name="addform" action="" method="post">
                                <!-- <form id="clickable-wizard" action="page_forms_wizard.html"  class=""> -->
                                    <!-- Second Step -->
                                    <div id="clickable-second" class="step">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">報名負責人：</label>
                                            <div class="col-md-6">
                                                <p class="form-control-static"><?php echo empty($pid) ? '' : $playerSqlRows[0]['member_name']; ?></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">會員卡號：</label>
                                            <div class="col-md-6">
                                                <p class="form-control-static"><?php echo empty($pid) ? '' : $playerSqlRows[0]['card_num']; ?></p>
                                                <!-- <input type="text" id="card_num" name="card_num" value="<?php //echo empty($pid)?'':$playerSqlRows[0]['card_num'];?>" class="form-control-static"> -->
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">會員電話：</label>
                                            <div class="col-md-6">
                                                <p class="form-control-static"><?php //echo empty($pid)?'':$playerSqlRows[0]['member_phone'];?>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-firstname">球員姓名：</label>
                                            <div class="col-md-6">
                                            <?php
                                              $name_player = empty($pid) ? '' : $playerSqlRows[0]['name_player'];
                                              echo empty($kind) ? '<input type="text" id="name_player" name="name_player" value="'.$name_player.'" class="form-control" placeholder="">' : '<p>'.$playerSqlRows[0]['name_player'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-lastname">出生年月日：</label>
                                            <div class="col-md-6" >
                                            <?php
                                              $birth = empty($pid) ? '' : $playerSqlRows[0]['birth'];
                                              echo empty($kind) ? '<input type="text" data-date-format="yyyy/mm/dd" class="form-control input-datepicker" id="birth" name="birth" value="'.$birth.'" placeholder="">' : '<p>'.$playerSqlRows[0]['birth'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-country">身分證字號或護照號碼：</label>
                                            <div class="col-md-6">
                                            <?php
                                              $id_card = empty($pid) ? '' : $playerSqlRows[0]['id_card'];
                                              echo empty($kind) ? '<input type="text" id="id_card" name="id_card" value="'.$id_card.'" class="form-control" placeholder="">' : '<p>'.$playerSqlRows[0]['id_card'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">家長姓名：</label>
                                            <div class="col-md-6">
                                            <?php
                                              $name_parents = empty($pid) ? '' : $playerSqlRows[0]['name_parents'];
                                              echo empty($kind) ? '<input type="text" id="name_parents" name="name_parents" value="'.$name_parents.'" class="form-control" placeholder="">' : '<p>'.$playerSqlRows[0]['name_parents'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">連絡電話：</label>
                                            <div class="col-md-6">
                                            <?php
                                              $mobile = empty($pid) ? '' : $playerSqlRows[0]['mobile'];
                                              echo empty($kind) ? '<input type="text" id="mobile" name="mobile" value="'.$mobile.'" class="form-control" placeholder="">' : '<p>'.$playerSqlRows[0]['mobile'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">通訊地址：</label>
                                            <div class="col-md-6">
                                                <div class="row" style="margin-bottom: 10px;">
                                                <div class="col-md-6" style="">
                                                <?php
                                                if (empty($kind)) {
                                                    if (!empty($pid) && trim($pid)) {
                                                        echo '<select class="form-control" id="smcid" name="smcid">';
                                                        echo '<option value="">請選擇縣市</option>';
                                                        foreach ($sys_map_citySqlRows as $smc) {
                                                            $factor = $playerSqlRows[0]['smcid'] == $smc['smcid'] ? 'selected' : '';
                                                            echo '<option value="'.$smc['smcid'].'"'.$factor.'>'.$smc['city'].'</option>';
                                                        }
                                                        echo '</select>';
                                                    } elseif (empty($pid)) {
                                                        echo '<select class="form-control" id="smcid" name="smcid">';
                                                        echo '<option value="">請選擇縣市</option>';
                                                        foreach ($sys_map_citySqlRows as $smc) {
                                                            echo '<option value="'.$smc['smcid'].'">'.$smc['city'].'</option>';
                                                        }
                                                        echo '</select>';
                                                    }
                                                } elseif (!empty($kind)) {
                                                    if (!empty($pid) && trim($pid)) {
                                                        echo '<select class="form-control" id="smcid" name="smcid" disabled>';
                                                        echo '<option value="">請選擇縣市</option>';
                                                        foreach ($sys_map_citySqlRows as $smc) {
                                                            $factor = $playerSqlRows[0]['smcid'] == $smc['smcid'] ? 'selected' : '';
                                                            echo '<option value="'.$smc['smcid'].'"'.$factor.'>'.$smc['city'].'</option>';
                                                        }
                                                        echo '</select>';
                                                    } elseif (empty($pid)) {
                                                        echo '<select class="form-control" id="smcid" name="smcid" disabled>';
                                                        echo '<option value="">請選擇縣市</option>';
                                                        foreach ($sys_map_citySqlRows as $smc) {
                                                            echo '<option value="'.$smc['smcid'].'">'.$smc['city'].'</option>';
                                                        }
                                                        echo '</select>';
                                                    }
                                                }
                                                ?>
                                                </div>
                                                <div class="col-md-6" style="">
                                                <?php
                                                if (empty($kind)) {
                                                    if (!empty($pid)) {
                                                        echo '<select class="form-control" id="smaid" name="smaid">';
                                                        echo '<option value="">請選擇鄉鎮市區</option>';
                                                        foreach ($sys_map_areaSqlRows as $sma) {
                                                            $factor = $playerSqlRows[0]['smaid'] == $sma['smaid'] ? 'selected' : '';
                                                            echo '<option value="'.$sma['smaid'].'"'.$factor.'>'.$sma['area'].'</option>';
                                                        }
                                                        echo '</select>';
                                                    } elseif (empty($pid)) {
                                                        echo '<select class="form-control" id="smaid" name="smaid">';
                                                        echo '<option value="">請選擇鄉鎮市區</option>';
                                                        echo '</select>';
                                                    }
                                                } elseif (!empty($kind)) {
                                                    if (!empty($pid)) {
                                                        echo '<select class="form-control" id="smaid" name="smaid" disabled>';
                                                        echo '<option value="">請選擇鄉鎮市區</option>';
                                                        foreach ($sys_map_areaSqlRows as $sma) {
                                                            $factor = $playerSqlRows[0]['smaid'] == $sma['smaid'] ? 'selected' : '';
                                                            echo '<option value="'.$sma['smaid'].'"'.$factor.'>'.$sma['area'].'</option>';
                                                        }
                                                        echo '</select>';
                                                    } elseif (empty($pid)) {
                                                        echo '<select class="form-control" id="smaid" name="smaid" disabled>';
                                                        echo '<option value="">請選擇鄉鎮市區</option>';
                                                        echo '</select>';
                                                    }
                                                }
                                                ?>
                                                </div>
                                                </div>
                                                <?php
                                                  $address = empty($pid) ? '' : $playerSqlRows[0]['address'];
                                                  echo empty($kind) ? '<input type="text" id="address" name="address" value="'.$address.'" class="form-control" placeholder="">' : '<p>'.$playerSqlRows[0]['address'].'</p>';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">球衣背號：</label>
                                            <div class="col-md-6">
                                            <?php
                                              $clothes_back_num = empty($pid) ? '' : $playerSqlRows[0]['clothes_back_num'];
                                              echo empty($kind) ? '<input type="text" id="clothes_back_num" name="clothes_back_num" value="'.$clothes_back_num.'" class="form-control" placeholder="">' : '<p>'.$playerSqlRows[0]['clothes_back_num'].'</p>';
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-city">球衣尺寸（身高：公分）：</label>
                                            <div class="col-md-6">
                                                <?php
                                                if (empty($kind)) {
                                                    // echo "aaa";
                                                    if (!empty($pid)) {
                                                        echo '<select class="" id="clothes_size" name="clothes_size" style="width:300px;height:30px;">';
                                                        $size120 = $playerSqlRows[0]['clothes_size'] == '120' ? 'selected' : '';
                                                        $size125 = $playerSqlRows[0]['clothes_size'] == '125' ? 'selected' : '';
                                                        $size130 = $playerSqlRows[0]['clothes_size'] == '130' ? 'selected' : '';
                                                        $size135 = $playerSqlRows[0]['clothes_size'] == '135' ? 'selected' : '';
                                                        $size140 = $playerSqlRows[0]['clothes_size'] == '140' ? 'selected' : '';
                                                        $size145 = $playerSqlRows[0]['clothes_size'] == '145' ? 'selected' : '';
                                                        $size150 = $playerSqlRows[0]['clothes_size'] == '150' ? 'selected' : '';
                                                        $size155 = $playerSqlRows[0]['clothes_size'] == '155' ? 'selected' : '';
                                                        $size160 = $playerSqlRows[0]['clothes_size'] == '160' ? 'selected' : '';
                                                        $size165 = $playerSqlRows[0]['clothes_size'] == '165' ? 'selected' : '';
                                                        $size170 = $playerSqlRows[0]['clothes_size'] == '170' ? 'selected' : '';
                                                        $size175 = $playerSqlRows[0]['clothes_size'] == '175' ? 'selected' : '';
                                                        $size180 = $playerSqlRows[0]['clothes_size'] == '180' ? 'selected' : '';
                                                        echo '
                                                    <option value="">請選擇球衣尺寸</option>
                                                    <option value="120"'.$size120.'>120</option>
                                                    <option value="125"'.$size125.'>125</option>
                                                    <option value="130"'.$size130.'>130</option>
                                                    <option value="135"'.$size135.'>135 (原本的S)</option>
                                                    <option value="140"'.$size140.'>140</option>
                                                    <option value="145"'.$size145.'>145 (原本的M)</option>
                                                    <option value="150"'.$size150.'>150</option>
                                                    <option value="155"'.$size155.'>155 (原本的L)</option>
                                                    <option value="160"'.$size160.'>160 (原本的XL)</option>
                                                    <option value="165"'.$size165.'>165 (原本的2XL)</option>
                                                    <option value="170"'.$size170.'>170 (原本的3XL)</option>
                                                    <option value="175"'.$size175.'>175 (原本的4XL)</option>
                                                    <option value="180"'.$size180.'>180 (原本的5XL)</option>
                                                    ';
                                                        echo '</select>';
                                                    } elseif (empty($pid)) {
                                                        echo '<select class="" id="clothes_size" name="clothes_size" style="width:300px;height:30px;">';
                                                        echo '
                                                    <option value="">請選擇球衣尺寸</option>
                                                    <option value="120">120</option>
                                                    <option value="125">125</option>
                                                    <option value="130">130</option>
                                                    <option value="135">135 (原本的S)</option>
                                                    <option value="140">140</option>
                                                    <option value="145">145 (原本的M)</option>
                                                    <option value="150">150</option>
                                                    <option value="155">155 (原本的L)</option>
                                                    <option value="160">160 (原本的XL)</option>
                                                    <option value="165">165 (原本的2XL)</option>
                                                    <option value="170">170 (原本的3XL)</option>
                                                    <option value="175">175 (原本的4XL)</option>
                                                    <option value="180">180 (原本的5XL)</option>
                                                    ';
                                                        echo '</select>';
                                                    }
                                                } elseif (!empty($kind)) {
                                                    // echo "bbb";
                                                    if (!empty($pid)) {
                                                        echo '<select class="" id="clothes_size" name="clothes_size" style="width:300px;height:30px;" disabled>';
                                                        $size120 = $playerSqlRows[0]['clothes_size'] == '120' ? 'selected' : '';
                                                        $size125 = $playerSqlRows[0]['clothes_size'] == '125' ? 'selected' : '';
                                                        $size130 = $playerSqlRows[0]['clothes_size'] == '130' ? 'selected' : '';
                                                        $size135 = $playerSqlRows[0]['clothes_size'] == '135' ? 'selected' : '';
                                                        $size140 = $playerSqlRows[0]['clothes_size'] == '140' ? 'selected' : '';
                                                        $size145 = $playerSqlRows[0]['clothes_size'] == '145' ? 'selected' : '';
                                                        $size150 = $playerSqlRows[0]['clothes_size'] == '150' ? 'selected' : '';
                                                        $size155 = $playerSqlRows[0]['clothes_size'] == '155' ? 'selected' : '';
                                                        $size160 = $playerSqlRows[0]['clothes_size'] == '160' ? 'selected' : '';
                                                        $size165 = $playerSqlRows[0]['clothes_size'] == '165' ? 'selected' : '';
                                                        $size170 = $playerSqlRows[0]['clothes_size'] == '170' ? 'selected' : '';
                                                        $size175 = $playerSqlRows[0]['clothes_size'] == '175' ? 'selected' : '';
                                                        $size180 = $playerSqlRows[0]['clothes_size'] == '180' ? 'selected' : '';
                                                        echo '
                                                    <option value="">請選擇球衣尺寸</option>
                                                    <option value="120"'.$size120.'>120</option>
                                                    <option value="125"'.$size125.'>125</option>
                                                    <option value="130"'.$size130.'>130</option>
                                                    <option value="135"'.$size135.'>135 (原本的S)</option>
                                                    <option value="140"'.$size140.'>140</option>
                                                    <option value="145"'.$size145.'>145 (原本的M)</option>
                                                    <option value="150"'.$size150.'>150</option>
                                                    <option value="155"'.$size155.'>155 (原本的L)</option>
                                                    <option value="160"'.$size160.'>160 (原本的XL)</option>
                                                    <option value="165"'.$size165.'>165 (原本的2XL)</option>
                                                    <option value="170"'.$size170.'>170 (原本的3XL)</option>
                                                    <option value="175"'.$size175.'>175 (原本的4XL)</option>
                                                    <option value="180"'.$size180.'>180 (原本的5XL)</option>
                                                    ';
                                                        echo '</select>';
                                                    } elseif (empty($pid)) {
                                                        // echo "bbb2";
                                                        echo '<select class="" id="clothes_size" name="clothes_size" style="width:300px;height:30px;" disabled>';
                                                        echo '
                                                    <option value="">請選擇球衣尺寸</option>
                                                    <option value="120">120</option>
                                                    <option value="125">125</option>
                                                    <option value="130">130</option>
                                                    <option value="135">135 (原本的S)</option>
                                                    <option value="140">140</option>
                                                    <option value="145">145 (原本的M)</option>
                                                    <option value="150">150</option>
                                                    <option value="155">155 (原本的L)</option>
                                                    <option value="160">160 (原本的XL)</option>
                                                    <option value="165">165 (原本的2XL)</option>
                                                    <option value="170">170 (原本的3XL)</option>
                                                    <option value="175">175 (原本的4XL)</option>
                                                    <option value="180">180 (原本的5XL)</option>
                                                    ';
                                                        echo '</select>';
                                                    }
                                                }
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
                                              echo '<button type="button" id="add_send" name="add_send" class="btn btn-effect-ripple btn-primary" id="next1">儲存</button>';
                                              echo '<button type="reset" id="cancel" name="cancel" class="btn btn-effect-ripple btn-danger" id="back1">取消</button>';
                                          } elseif (!empty($kind)) {
                                              echo '<button type="reset" id="cancel" name="cancel" class="btn btn-effect-ripple btn-danger" id="back1">返回上一頁</button>';
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
      $().ready(function()
      {
        $('#add_send').click(function()
        {
          // validate.手機號碼驗證
          jQuery.validator.addMethod("isMobile", function(value, element) {
              var length = value.length;
              var mobile = /^09[0-9]{2}[0-9]{6}$/;
              return this.optional(element) || (length == 10 && mobile.test(value));
          }, "請正確填寫您的手機");
          // validate.身分證驗證
          jQuery.validator.addMethod("isIdCardNo", function(value, element) {
              var length = value.length;
              var id_card = /^[A-Z]{1}[1-2]{1}[0-9]{1}[0-9]{7}$/;
              return this.optional(element) || (length == 10 && id_card.test(value));
          }, "請正確填寫您的身分證");
          //validate主要程式
          // 在键盘按下并释放及提交后验证提交表单
          var validform=$("#addform").validate(
            {
              rules:
                 {
                    card_num:{
                      required: true,
                    },
                    name_player: "required",
                    birth: {
                      required: true,
                      //dateISO: true
                    },
                    id_card: {
                      required: true,
                      // isIdCardNo:true,
                      // minlength: 10,
                      // maxlength: 10
                    },
                    name_parents: "required",
                    mobile: {
                      required: true,
                      isMobile:true,
                      minlength: 10,
                      maxlength: 10
                    },
                    // smcid: "required",
                    // smaid: "required",
                    // address: "required",
                    // clothes_back_num: {
                    //   required: true,
                    //   digits: true,
                    //   minlength: 1,
                    //   maxlength: 2
                    // },
                    // clothes_size: "required"
                 },
              messages:
                {
                  card_num:{
                    required:"請輸入卡號",
                  },
                  name_player: "請輸入球員名字",
                  birth: {
                    required:"请输入球員出生年月日",
                    //dateISO: "請輸入正確格式YYYY-mm-dd"
                  },
                  id_card: {
                    required: "請輸入身分證字號或護照號碼",
                    // isIdCardNo:"例:A123456789(字母大寫)",
                    // minlength: "請符合身分證格式",
                    // maxlength: "請符合身分證格式"
                  },
                  name_parents: "請輸入球員家長名字",
                  mobile: {
                    required: "請輸入手機號碼",
                    isMobile: "請輸入09開頭的10碼號碼",
                    minlength: "不可小於10碼",
                    maxlength: "不可大於10碼"
                  },
                  // smcid: "請選擇縣市",
                  // smaid: "請選擇鄉鎮市區",
                  // address: "請輸入地址",
                  // clothes_back_num: {
                  //   required:"請輸入球員背號",
                  //   digits:"請輸入數字",
                  //   minlength: "不可小於1碼",
                  //   maxlength: "不可大於2碼"
                  // },
                  // clothes_size: "請輸入球員球衣尺寸"
                }
           });

          var chkResult=validform.form();
          if (chkResult==true)
          {
            const link='backstage_player_ajax.php';
            const card_num=$('#card_num').val();
            const name_player=$('#name_player').val();
            const birth=$('#birth').val();
            const id_card=$('#id_card').val();
            const name_parents=$('#name_parents').val();
            const mobile=$('#mobile').val();
            const smcid=$('#smcid').val()?$('#smcid').val():0;
            const smaid=$('#smaid').val()?$('#smaid').val():0;
            const address=$('#address').val()?$('#address').val():0;
            const clothes_back_num=$('#clothes_back_num').val()?$('#clothes_back_num').val():0;
            const clothes_size=$('#clothes_size').val()?$('#clothes_size').val():0;

            $.ajax(
            {
              url: link,
              type:"post",
              cache: true,
              async:false,
              datatype:"json",
              data:
              {
                "card_num":card_num,
                "mid":<?php echo $mid; ?>,
                "pid":<?php echo $pid; ?>,
                "tid":<?php echo $connect_tid; ?>,
                "r_nid":<?php echo $r_nid; ?>,
                "name_player":name_player,
                "birth":birth,
                "id_card":id_card,
                "name_parents":name_parents,
                "mobile":mobile,
                "smcid":smcid,
                "smaid":smaid,
                "address":address,
                "clothes_back_num":clothes_back_num,
                "clothes_size":clothes_size
              },
              error:function(data)
              {
                alert("Ajax request 發生錯誤");
              },
              success:function(data)
              {
                // console.log(data);return;
                var dataobj=$.parseJSON($.trim(data));
                if(dataobj.status=="success")
                {
                  alert("編輯成功");
                    window.location='backstage_participate.php?<?php echo '&r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status.'&participatepage='.$participatePage.'&keyword='.$keyword; ?>';
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
      // $(document).ready(function()
      // {
      //   var opt=
      //   {
      //      dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
      //      dayNamesMin:["日","一","二","三","四","五","六"],
      //      monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
      //      monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
      //      prevText:"上月",
      //      nextText:"次月",
      //      weekHeader:"週",
      //      showMonthAfterYear:true,
      //      dateFormat:"yyyy/mm/dd"
      //   };
      //   $("#birth").datepicker(opt);
      // });


      $(function()
      {
        $('#cancel').click(function()
        {
          window.location='backstage_participate.php?<?php echo '&r_nid='.$r_nid.'&said='.$said.'&connect_tid='.$connect_tid.'&c_status='.$c_status.'&participatepage='.$participatePage.'&kind='.$kind.'&t_status='.$t_status.'&keyword='.$keyword;?>';
        })
      })
    </script>
</body>
</html>
