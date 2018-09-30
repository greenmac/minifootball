<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
// include_once(dirname(dirname(__FILE__)).'/get_order_list.php');

$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$r_nid = isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$rid = isset($_GET['rid']) && trim($_GET['rid']) ? trim($_GET['rid']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
if (empty($c_status)) {
    if (empty($kind)) {
        $siteSql = 'SELECT * from site where status=3 order by sid';
    } elseif (!empty($kind)) {
        $siteSql = "SELECT *
    from
    (
      SELECT *
      from race
      where r_nid=$r_nid and status=$c_status and kind=$kind
    )race
    inner join site
    on race.sid=site.sid
    inner join race_name
    on race.r_nid=race_name.r_nid";

        $groupingAllSql = "SELECT * from grouping where status=1 and r_nid=$r_nid order by gid";
        $groupingAllSqlResult = $link->prepare($groupingAllSql);
        $groupingAllSqlResult->execute();
        $groupingAllSqlRows = $groupingAllSqlResult->fetchall();
        // pre($groupingAllSql);
    }
} elseif (!empty($c_status)) {
    $siteSql = "SELECT *
  from
  (
    SELECT *
    from race
    where appear=1 and r_nid=$r_nid and status=$c_status
  )race
  inner join site
  on race.sid=site.sid
  inner join race_name
  on race.r_nid=race_name.r_nid";
    $siteSqlResult = $link->prepare($siteSql);
    $siteSqlResult->execute();
    $siteSqlRows = $siteSqlResult->fetchall();

    if (empty($siteSqlRows)) {
        $siteSql = "SELECT *
    from
    (
      SELECT *
      from race
      where appear=0 and r_nid=$r_nid and status=$c_status
    )race
    inner join race_name
    on race.r_nid=race_name.r_nid";
    } elseif (!empty($siteSqlRows)) {
        $siteSql = "SELECT *
    from
    (
      SELECT *
      from race
      -- where appear=1 and r_nid=$r_nid and status=$c_status
      where r_nid=$r_nid and status=$c_status
    )race
    inner join site
    on race.sid=site.sid
    inner join race_name
    on race.r_nid=race_name.r_nid
    order by race.rid
    ";
    }

    $groupingAllSql = "SELECT * from grouping where status=1 and r_nid=$r_nid order by gid";
    $groupingAllSqlResult = $link->prepare($groupingAllSql);
    $groupingAllSqlResult->execute();
    $groupingAllSqlRows = $groupingAllSqlResult->fetchall();
}
// pre($siteSql);exit;
$siteSqlResult = $link->prepare($siteSql);
$siteSqlResult->execute();
$siteSqlRums = $siteSqlResult->rowcount();
$siteSqlRows = $siteSqlResult->fetchall();
// pre($siteSqlRows);
// exit;

if (empty($c_status)) {
    $siteSql2 = 'SELECT * from site where status=3 order by sid';
    $siteSql2Result = $link->prepare($siteSql);
    $siteSql2Result->execute();
    $siteSql2Rows = $siteSql2Result->fetchall();
}

if (empty($c_status)) {
    $groupingSql2 = 'SELECT * from grouping where status=3 order by gid';
    $groupingSql2Result = $link->prepare($groupingSql2);
    $groupingSql2Result->execute();
    $groupingSql2Rows = $groupingSql2Result->fetchall();
}

// $siteSqlNums=$siteSqlResult->rowcount();
// $siteSqlPer=3;//每頁呈現幾筆
// $siteSqlPages=ceil($siteSqlNums/$siteSqlPer);//(總筆數/每頁呈現幾筆),會出現幾頁
// $siteSqlPage=!isset($_GET['siteSqlPage'])?1:(int)$_GET['siteSqlPage'];//取get值
// $siteSqlStart=($siteSqlPage-1)*$siteSqlPer;//每頁從陣列['0']開始顯示
// $siteSql.=" LIMIT $siteSqlStart,$siteSqlPer";//陣列['0']開始顯示,呈現幾筆
// $siteSqlResult=$link->prepare($siteSql);
// $siteSqlResult->execute();
// $siteSqlRows=$siteSqlResult->fetchall();
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>管理球員</title>
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
    <!-- END Stylesheets -->
    <!-- Modernizr (browser feature detection library) -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
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
                                  if (empty($kind)) {
                                      echo empty($c_status) ? '<h1>新增盃賽活動</h1>' : '<h1>編輯盃賽活動</h1>';
                                  } elseif (!empty($kind)) {
                                      echo '<h1>封存盃賽查詢</h1>';
                                  }
                                  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Widgets Header -->
                    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                        <div class="block">
                            <!-- Partial Responsive Title -->
                            <div class="block-title">
                              <?php
                                echo empty($kind) ? '<h2>盃賽報名管理</h2>' : '<h2>封存盃賽查詢</h2>';
                              ?>
                            </div>
                            <!-- END Partial Responsive Title -->
                            <!-- Partial Responsive Content -->
							<form id="addform" action="backstage_race_edit_ajax.php" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
                <?php
                if (empty($c_status)) {
                    foreach ($siteSql2Rows as $ss2 => $ss2r) {
                        echo '<input type="hidden" name="apply2['.$ss2.'][sid]" value="'.$ss2r['sid'].'">';
                        echo '<input type="hidden" name="apply2['.$ss2.'][said]" value="'.$ss2r['said'].'">';
                        echo '<input type="hidden" name="apply2['.$ss2.'][place]" value="'.$ss2r['place'].'">';
                        echo '<input type="hidden" name="apply2['.$ss2.'][location]" value="'.$ss2r['location'].'">';
                        echo '<input type="hidden" name="apply2['.$ss2.'][address]" value="'.$ss2r['address'].'">';
                    }
                    foreach ($groupingSql2Rows as $gs2 => $gs2r) {
                        echo '<input type="hidden" name="apply3['.$gs2.'][gid]" value="'.$gs2r['gid'].'">';
                        echo '<input type="hidden" name="apply3['.$gs2.'][age]" value="'.$gs2r['age'].'">';
                        echo '<input type="hidden" name="apply3['.$gs2.'][lowest_birth]" value="'.$gs2r['lowest_birth'].'">';
                    }
                }
                ?>
                            <div class="col-lg-12 row">
                                <div class="col-lg-8 ">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            盃賽名稱：
                                            <?php
                                             $kindDisabled = !empty($kind) ? 'disabled' : '';
                                             if (!empty($c_status)) {
                                                 echo '<input type="text" id="race_name" name="race_name" value="'.$siteSqlRows[0]['name'].'" class="form-control " placeholder="" value="" '.$kindDisabled.'>';
                                             } else {
                                                 echo '<input type="text" id="race_name" name="race_name" value="" class="form-control " placeholder="" value="" '.$kindDisabled.'>';
                                             }
                                            ?>
                                        </div>
                                        <div class="col-lg-6" style="">
                                            SKU料號：
                                            <?php
                                             if (!empty($c_status)) {
                                                 echo '<input type="text" id="sku" name="sku" value="'.$siteSqlRows[0]['sku'].'" class="form-control" placeholder="" value="" '.$kindDisabled.'>';
                                             } else {
                                                 echo '<input type="text" id="sku" name="sku" value="" class="form-control" placeholder="" value="" '.$kindDisabled.'>';
                                             }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                  <a name="grouping_manage" onclick="grouping_manage();return false;" class="btn btn-info pull-right" >年齡管理</a>
                                  <a name="site_manage" onclick="site_manage();return false;" class="btn btn-warning pull-right" style="margin: 0 5px;">場區管理</a>
                                <div class="clear"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12" style="margin-bottom: 10px;">
									<?php
                                    foreach ($siteSqlRows as $k => $a) {
                                        //$chk1=!empty($c_status)?'checked':'';
                                        if (!empty($c_status)) {
                                            echo '<input type="hidden" name="apply['.$k.'][rid]" value="'.$a['rid'].'" '.$kindDisabled.'>';
                                        } else {
                                            echo '';
                                        }
                                        $chk1 = !empty($a['appear']) && $a['appear'] == 1 ? 'checked' : '';
                                        $rid = !empty($a['rid']) ? $a['rid'] : ''; ?>
                  <?php
                    $sid = $a['sid'];
                                        if (!empty($sid)) {
                                            ?>
                                    <h3 class="sub-header"><input type="checkbox" name="site_check[]" id="<?php echo $a['place']; ?>" value="<?php echo $a['sid']; ?>" <?php echo $chk1; ?> <?php echo $kindDisabled; ?>><?php echo $a['place']; ?></h3>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">比賽日期：</label>
                                        <div class="col-md-6">
                                          <?php
                                           if (!empty($c_status)) {
                                               echo '<textarea id="race_date'.$k.'" name="apply['.$k.'][race_date]" rows="7" class="form-control" placeholder="Description.." '.$kindDisabled.'>'.$a['race_date'].'</textarea>';
                                           } elseif (empty($c_status)) {
                                               if (empty($kind)) {
                                                   echo '<textarea id="race_date'.$k.'" name="apply['.$k.'][race_date]" rows="7" class="form-control" placeholder="Description.." '.$kindDisabled.'></textarea>';
                                               } elseif (!empty($kind)) {
                                                   echo '<textarea id="race_date'.$k.'" name="apply['.$k.'][race_date]" rows="7" class="form-control" placeholder="Description.." '.$kindDisabled.'>'.$a['race_date'].'</textarea>';
                                               }
                                           } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">年齡分組：</label>
                                        <div class="col-md-6">
											 <?php
                        if (empty($c_status)) {
                            if (empty($kind)) {
                                $groupingSql = 'SELECT * from grouping where status=3';
                            } elseif (!empty($kind)) {
                                $groupingSql = "SELECT *
                            from race
                            where appear=1 and status=$c_status and r_nid=$r_nid and rid=$rid and kind=$kind
                            order by rid";
                            }
                        } elseif (!empty($c_status)) {
                            $groupingSql = "SELECT *
                          from race
                          -- where appear=1 and status=$c_status and r_nid=$r_nid and rid=$rid
                          where status=$c_status and r_nid=$r_nid and rid=$rid
                          order by rid";
                            // $groupingSql="SELECT
                          // race.rid,
                          // race.r_nid,
                          // race.sid,
                          // race.status,
                          // race.gid,
                          // grouping.age,
                          // grouping.lowest_birth
                          // FROM
                          // (
                          //   SELECT race.rid,race.r_nid,race.sid,race.status,SUBSTRING_INDEX(SUBSTRING_INDEX(race.gid, ',', numbers.n), ',', -1) gid
                          //   FROM
                          //   (
                          //    SELECT 1 n
                          //    UNION ALL SELECT 2
                          //    UNION ALL SELECT 3
                          //    UNION ALL SELECT 4
                          //    UNION ALL SELECT 5
                          //    UNION ALL SELECT 6
                          //    UNION ALL SELECT 7
                          //    UNION ALL SELECT 8
                          //    UNION ALL SELECT 9
                          //    UNION ALL SELECT 10
                          //   ) numbers
                          //   INNER JOIN race
                          //   ON (CHAR_LENGTH(race.gid)-CHAR_LENGTH(REPLACE(race.gid, ',', '')))>=numbers.n-1
                          //   where appear=1 and status=$c_status and r_nid=$r_nid and rid=$rid
                          // )race
                          // inner JOIN grouping
                          // on race.gid=grouping.gid
                          // group by grouping.age
                          // order by rid,gid";
                        }
                                            $groupingResult = $link->prepare($groupingSql);
                                            $groupingResult->execute();
                                            $groupingRows = $groupingResult->fetchall();
                                            $groupingRowsGid = !empty($c_status) ? $groupingRows[0]['gid'] : '';
                                            if (!empty($c_status)) {
                                                $groupingRowsGid = $groupingRows[0]['gid'];
                                            } elseif (empty($c_status)) {
                                                $groupingRowsGid = empty($kind) ? '' : $groupingRows[0]['gid'];
                                            }
                                            // pre($groupingRows);
                                            $gid_arr = explode(',', $groupingRowsGid);
                                            // pre($gid_arr); ?>
                      <?php
                      if (!empty($c_status)) {
                          foreach ($groupingAllSqlRows as $ga1 => $gas1) {
                              // $chk2=!empty($gas1['gid'])?'checked':'';
                              if (in_array($gas1['gid'], $gid_arr)) {
                                  $chk2 = 'checked';
                              } else {
                                  $chk2 = '';
                              } ?>
                        <div class="checkbox">
                            <label for="example-checkbox1">
                                <input type="checkbox" id="gid<?php echo $k; ?>" name="apply[<?php echo $k; ?>][gid][check][]" value="<?php echo $gas1['gid']; ?>" <?php echo  $chk2; ?> <?php echo $kindDisabled; ?>><?php echo $gas1['age']; ?>:<?php echo $gas1['lowest_birth']; ?>後出生
                            </label>
                        </div>
    									<?php
                          }
                      } elseif (empty($c_status)) {
                          if (empty($kind)) {
                              foreach ($groupingRows as $gr1) {
                                  ?>
                          <div class="checkbox">
                              <label for="example-checkbox1">
                                  <input type="checkbox" id="gid<?php echo $k; ?>" name="apply[<?php echo $k; ?>][gid][check][]" value="<?php echo $gr1['gid']; ?>" <?php echo $kindDisabled; ?>><?php echo $gr1['age']; ?>:<?php echo $gr1['lowest_birth']; ?>後出生
                              </label>
                          </div>
                        <?php
                              }
                          } elseif (!empty($kind)) {
                              foreach ($groupingAllSqlRows as $ga2 => $gas2) {
                                  // $chk2=!empty($gas1['gid'])?'checked':'';
                                  if (in_array($gas2['gid'], $gid_arr)) {
                                      $chk3 = 'checked';
                                  } else {
                                      $chk3 = '';
                                  } ?>
                          <div class="checkbox">
                              <label for="example-checkbox1">
                                  <input type="checkbox" id="gid<?php echo $k; ?>" name="apply[<?php echo $k; ?>][gid][check][]" value="<?php echo $gas2['gid']; ?>" <?php echo  $chk3; ?> <?php echo $kindDisabled; ?>><?php echo $gas2['age']; ?>:<?php echo $gas2['lowest_birth']; ?>後出生
                              </label>
                          </div>
                        <?php
                              }
                          }
                      } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">盃賽開放時間：</label>
                                       <div class="col-md-6">
                                            <h5>日期</h5>
                                            <div class="input-group input-daterange" data-date-format="yyyy/mm/dd">
                                              <?php
                                               if (!empty($c_status)) {
                                                   echo '<input type="text" id="begin_date'.$k.'" name="apply['.$k.'][begin_date]" value="'.$a['begin_date'].'" class="form-control" placeholder="開始" '.$kindDisabled.'>';
                                               } elseif (empty($c_status)) {
                                                   if (empty($kind)) {
                                                       echo '<input type="text" id="begin_date'.$k.'" name="apply['.$k.'][begin_date]" value="" class="form-control" placeholder="開始" '.$kindDisabled.'>';
                                                   } elseif (!empty($kind)) {
                                                       echo '<input type="text" id="begin_date'.$k.'" name="apply['.$k.'][begin_date]" value="'.$a['begin_date'].'" class="form-control" placeholder="開始" '.$kindDisabled.'>';
                                                   }
                                               } ?>
                                                <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                              <?php
                                               if (!empty($c_status)) {
                                                   echo '<input type="text" id="final_date'.$k.'" name="apply['.$k.'][final_date]" value="'.$a['final_date'].'" class="form-control" placeholder="結束" '.$kindDisabled.'>';
                                               } elseif (empty($c_status)) {
                                                   if (empty($kind)) {
                                                       echo '<input type="text" id="final_date'.$k.'" name="apply['.$k.'][final_date]" value="" class="form-control" placeholder="結束" '.$kindDisabled.'>';
                                                   } elseif (!empty($kind)) {
                                                       echo '<input type="text" id="final_date'.$k.'" name="apply['.$k.'][final_date]" value="'.$a['final_date'].'" class="form-control" placeholder="結束" '.$kindDisabled.'>';
                                                   }
                                               } ?>
                                            </div>
                                            <div class="clearfix" style="margin-top:10px; ">
                                                <h5>開始時間</h5>
                                                <div class="col-md-6">
                                                  <?php
                                                   if (!empty($c_status)) {
                                                       echo '<input type="text" id="begin_hour'.$k.'" name="apply['.$k.'][begin_hour]" value="'.$a['begin_hour'].'" class="form-control" placeholder="時" '.$kindDisabled.'>';
                                                   } elseif (empty($c_status)) {
                                                       if (empty($kind)) {
                                                           echo '<input type="text" id="begin_hour'.$k.'" name="apply['.$k.'][begin_hour]" value="" class="form-control" placeholder="時" '.$kindDisabled.'>';
                                                       } elseif (!empty($kind)) {
                                                           echo '<input type="text" id="begin_hour'.$k.'" name="apply['.$k.'][begin_hour]" value="'.$a['begin_hour'].'" class="form-control" placeholder="時" '.$kindDisabled.'>';
                                                       }
                                                   } ?>
                                                </div>
                                                <div class="col-md-6">
                                                  <?php
                                                   if (!empty($c_status)) {
                                                       echo '<input type="text" id="begin_minutes'.$k.'" name="apply['.$k.'][begin_minutes]" value="'.$a['begin_minutes'].'" class="form-control" placeholder="分" '.$kindDisabled.'>';
                                                   } elseif (empty($c_status)) {
                                                       if (empty($kind)) {
                                                           echo '<input type="text" id="begin_minutes'.$k.'" name="apply['.$k.'][begin_minutes]" value="" class="form-control" placeholder="分" '.$kindDisabled.'>';
                                                       } elseif (!empty($kind)) {
                                                           echo '<input type="text" id="begin_minutes'.$k.'" name="apply['.$k.'][begin_minutes]" value="'.$a['begin_minutes'].'" class="form-control" placeholder="分" '.$kindDisabled.'>';
                                                       }
                                                   } ?>
                                                </div>
                                            </div>

                                            <div></div>

                                            <div class="clearfix" style="margin-top:10px; ">
                                                <h5>結束時間</h5>
                                                <div class="col-md-6">
                                                  <?php
                                                   if (!empty($c_status)) {
                                                       echo '<input type="text" id="final_hour'.$k.'" name="apply['.$k.'][final_hour]" value="'.$a['final_hour'].'" class="form-control" placeholder="時" '.$kindDisabled.'>';
                                                   } elseif (empty($c_status)) {
                                                       if (empty($kind)) {
                                                           echo '<input type="text" id="final_hour'.$k.'" name="apply['.$k.'][final_hour]" value="" class="form-control" placeholder="時" '.$kindDisabled.'>';
                                                       } elseif (!empty($kind)) {
                                                           echo '<input type="text" id="final_hour'.$k.'" name="apply['.$k.'][final_hour]" value="'.$a['final_hour'].'" class="form-control" placeholder="時" '.$kindDisabled.'>';
                                                       }
                                                   } ?>
                                                </div>
                                                <div class="col-md-6">
                                                  <?php
                                                   if (!empty($c_status)) {
                                                       echo '<input type="text" id="final_minutes'.$k.'" name="apply['.$k.'][final_minutes]" value="'.$a['final_minutes'].'" class="form-control" placeholder="分" '.$kindDisabled.'>';
                                                   } elseif (empty($c_status)) {
                                                       if (empty($kind)) {
                                                           echo '<input type="text" id="final_minutes'.$k.'" name="apply['.$k.'][final_minutes]" value="" class="form-control" placeholder="分" '.$kindDisabled.'>';
                                                       } elseif (!empty($kind)) {
                                                           echo '<input type="text" id="final_minutes'.$k.'" name="apply['.$k.'][final_minutes]" value="'.$a['final_minutes'].'" class="form-control" placeholder="分" '.$kindDisabled.'>';
                                                       }
                                                   } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                      if ($c_status == 2) {
                                          echo '
                                          <div class="form-group">
                                            <label class="col-md-4 control-label" for="example-clickable-username">決賽球員可更替人數：</label>
                                            <div class="col-md-6">
                                              <input type="text" name="apply['.$k.'][calculate]" value="'.$a['calculate'].'" class="form-control" placeholder="" '.$kindDisabled.'>
                                            </div>
                                          </div>';
                                      } else {
                                          echo '';
                                      } ?>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">注意事項：</label>
                                        <div class="col-md-6">
                                          <?php
                                           if (!empty($c_status)) {
                                               echo '<textarea id="note'.$k.'" name="apply['.$k.'][note]" rows="7" class="form-control" placeholder="Description.." '.$kindDisabled.'>'.$a['note'].'</textarea>';
                                           } elseif (empty($c_status)) {
                                               if (empty($kind)) {
                                                   echo '<textarea id="note'.$k.'" name="apply['.$k.'][note]" rows="7" class="form-control" placeholder="Description.." '.$kindDisabled.'></textarea>';
                                               } elseif (!empty($kind)) {
                                                   echo '<textarea id="note'.$k.'" name="apply['.$k.'][note]" rows="7" class="form-control" placeholder="Description.." '.$kindDisabled.'>'.$a['note'].'</textarea>';
                                               }
                                           } ?>
                                        </div>
                                    </div>
									<input type="hidden" name="apply[<?php echo $k; ?>][sid]" value="<?php echo $a['sid']; ?>">
									<?php
                                        } elseif (empty($sid)) {
                                            echo '';
                                        }
                                    }
                  ?>


								<!--
                                 <h3 class="sub-header">中部台中場</h3>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">比賽日期：</label>
                                        <div class="col-md-6">
                                            <textarea id="example-textarea-input" name="example-textarea-input" rows="7" class="form-control" placeholder="Description.."></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">年齡分組：</label>
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label for="example-checkbox1">
                                                    <input type="checkbox" id="example-checkbox1" name="example-checkbox1" value="option1">少年組U6 2010年1月1日後出生
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="example-checkbox2">
                                                    <input type="checkbox" id="example-checkbox2" name="example-checkbox2" value="option2">少年組U6 2010年1月1日後出生
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label for="example-checkbox3">
                                                    <input type="checkbox" id="example-checkbox3" name="example-checkbox3" value="option3">少年組U6 2010年1月1日後出生
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">盃賽開放時間：</label>
                                        <div class="col-md-6">
                                            <div class="input-group input-daterange" data-date-format="mm/dd/yyyy">
                                                <input type="text" id="example-daterange1" name="example-daterange1" class="form-control" placeholder="開始">
                                                <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                                <input type="text" id="example-daterange2" name="example-daterange2" class="form-control" placeholder="結束">
                                            </div>
                                            <div class="row" style="margin-top:10px; ">
                                                <div class="col-md-6">
                                                    <input type="text" id="example-clickable-city" name="example-clickable-city" class="form-control" placeholder="時">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="example-clickable-city" name="example-clickable-city" class="form-control" placeholder="分">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="example-clickable-username">注意事項：</label>
                                        <div class="col-md-6">
                                            <textarea id="example-textarea-input" name="example-textarea-input" rows="7" class="form-control" placeholder="Description.."></textarea>
                                        </div>
                                    </div>
									-->
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
                                          /*
                                           echo $siteSqlPage==1?'':'<li><a href=?c_status='.$c_status.'&r_nid='.$r_nid.'&siteSqlPage=1>首頁</i></a></li>'.'　';
                                           echo $siteSqlPage==1?'':'<li><a href=?c_status='.$c_status.'&r_nid='.$r_nid.'&siteSqlPage='.($siteSqlPage-1).'><i class="fa fa-chevron-left"></i></a></li>'.'　';//上一頁
                                           for($i=1;$i<=$siteSqlPages;$i++)
                                           {
                                             echo $i==$siteSqlPage ? '<li class="active"><a>'.$i.'</a></li>':'<li><a href="?c_status='.$c_status.'&r_nid='.$r_nid.'&siteSqlPage='.$i.'">'.$i.'</a></li>';//當前顯示頁不會有連結,且放大
                                           }
                                           echo $siteSqlPage==$siteSqlPages?'':'　'.'<li><a href=?c_status='.$c_status.'&r_nid='.$r_nid.'&siteSqlPage='.($siteSqlPage+1).'><i class="fa fa-chevron-right"></i></a></li>';//下一頁
                                           echo $siteSqlPage==$siteSqlPages?'':'　'.'<li><a href=?c_status='.$c_status.'&r_nid='.$r_nid.'&siteSqlPage='.$siteSqlPages.'>末頁</a></li>';
                                           */
                                          ?>
                                        </ul>
                                    </div>

                                    <div class="form-group form-actions row">
                                        <div class="col-md-8 col-md-offset-4">
                                          <?php
                                            if (empty($kind)) {
                                                echo '<input type="button" onclick="raceStorage();" class="btn btn-effect-ripple btn-primary ui-wizard-content ui-formwizard-button" value="儲存">';
                                                echo '<button type="reset" class="btn btn-effect-ripple btn-danger ui-wizard-content ui-formwizard-button" id="cancel">取消</button>';
                                            } elseif (!empty($kind)) {
                                                echo '<button type="reset" class="btn btn-effect-ripple btn-danger ui-wizard-content ui-formwizard-button" id="cancel">返回<封存盃賽查詢></button>';
                                            }
                                          ?>
                                        </div>
                                    </div>
                            </div>
							</form>
                            <div class="clearfix"></div>
                            <!-- END Partial Responsive Content -->
                        </div>
                    </div>
                    <div class="clearfix"></div>
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
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>-->
  <script src="../jquery-validation-1.17.0/dist/jquery.validate.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/app.js"></script>
    <!-- Load and execute javascript code used only in this page -->
	<script>
	  finals_Chk();

	  var c_status=<?php echo $c_status; ?>;
		  if(c_status==0){
  		  var getData=getCookie();
  		  if(getData){
    			$("#race_name").val(getData["race_name"]);
    			$("#sku").val(getData["sku"]);
  		  }
      }

	 function site_manage(){
		setCookie();
		window.location='backstage_sitemanage_colorbox.php?<?php echo 'c_status='.$c_status.'&r_nid='.$r_nid.'&kind='.$kind; ?>';
	 }

	 function grouping_manage(){
		setCookie();
		window.location='backstage_groupingmanage_colorbox.php?<?php echo 'c_status='.$c_status.'&r_nid='.$r_nid.'&rid='.$rid.'&kind='.$kind; ?>';
	 }

	function finals_Chk(){
		var r_nid=<?php echo $r_nid; ?>;
		$.ajax({
			url:  "backstage_race_edit_ajax.php",
			type: "post",
			data:
			{
			  "r_nid":<?php echo $r_nid; ?>,
			  "act":"check_finals",
			},
			success: function(result)
			{
				var dataobj = $.parseJSON($.trim(result));
				if(dataobj.sCount>0){
					$("#race_name").prop('readonly', true);
					$("#sku").prop('readonly', true);
					// $('a[name="site_manage"]').attr('onclick',"javascript:alert('此盃賽已晉級決賽,無法進行修改');");
					// $('a[name="grouping_manage"]').attr('onclick',"javascript:alert('此盃賽已晉級決賽,無法進行修改');");
				}
			}
		});
	}

	function field_Chk(){
		var paramList = "";
		$.ajax({
			url:  "backstage_race_edit_ajax.php",
			type: "post",
			data:
			{
			  "c_status":<?php echo $c_status; ?>,
			  "r_nid":<?php echo $r_nid; ?>,
			  "act":"check_SiteAge",
			},
			success: function(result)
			{
				// console.log(result);return;
				var dataobj = $.parseJSON($.trim(result));
				if(dataobj.status=='s'){
					alert("請先新增場區及年齡組");
					return;
				}
				else if(dataobj.status=='g')
				{
					alert("請先新增年齡組");
					return;
				}
			}
		});
	}

	function raceStorage()
	{
		field_Chk();
		var race_name=$("#race_name").val();
		var sku=$("#sku").val();
		var alertMsg="";

		if(!race_name){alert("請填寫盃賽名稱"); $("#race_name").focus(); return;}
		if(!sku){alert("請填寫SKU料號"); $("#sku").focus();return;}
		if($('input[name="site_check[]"]:checked').length==0){alert('請先選擇場區');return;}
    <?php
    foreach ($siteSqlRows as $sK2 => $sV2) {
        ?>
      $('input[id="<?php echo !empty($sV2['place']) ? $sV2['place'] : ''; ?>"]:checked').each(function(i,v)
      {
        var siteName=$(this).attr('id');
        if($('textarea[id="race_date<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請填寫比賽日期\n";
          return;
        }

        if($('input[id="gid<?php echo $sK2; ?>"]').is(':checked')==false)
        {
          alertMsg+="【場區:"+siteName+"】請選擇年齡分組\n";
          return;
        }

        if($('input[id="begin_date<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請設定盃賽開始日期\n";
          return;
        }

        if($('input[id="begin_hour<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請設定盃賽開始時間(時)\n";
          return;
        }

        if($('input[id="begin_minutes<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請設定盃賽開始時間(分)\n";
          return;
        }

        if($('input[id="final_date<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請設定盃賽結束日期\n";
          return;
        }

        if($('input[id="final_hour<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請設定盃賽結束時間(時)\n";
          return;
        }

        if($('input[id="final_minutes<?php echo $sK2; ?>"]').val()=="")
        {
          alertMsg+="【場區:"+siteName+"】請設定盃賽結束時間(分)\n";
          return;
        }

        // if($('textarea[id="note<?php //echo $sK2;?>"]').val()=="")
        // {
        //   alertMsg+="【場區:"+siteName+"】請填寫注意事項\n";
        //   return;
        // }
      });

      /*2018/04/26隱藏,舊的
  		$('input[name="site_check[]"]:checked').each(function(i,v)
  		{
  			var siteName=$(this).attr('id');
  			if($('textarea[name="apply['+i+'][race_date]"]').val()=="")
  			{
  				alertMsg+="【場區:"+siteName+"】請填寫比賽日期\n";
  				return;
  			}

  			if($('input[name="apply['+i+'][gid][check][]"]').is(':checked')==false)
  			{
  				alertMsg+="【場區:"+siteName+"】請選擇年齡分組\n";
  				return;
  			}

  			if($('input[name="apply['+i+'][begin_date]"]').val()=="")
  			{
  				alertMsg+="【場區:"+siteName+"】請設定盃賽開始時間\n";
  				return;
  			}

  			if($('input[name="apply['+i+'][final_date]"]').val()=="")
  			{
  				alertMsg+="【場區:"+siteName+"】請設定盃賽結束時間\n";
  				return;
  			}
  		});
      */

    <?php
    }
    ?>

		if(alertMsg!=""){
			alert(alertMsg);
		}else{
			$("#addform").ajaxSubmit({
				datatype:"json",
				data:
				{
				  "r_nid":<?php echo $r_nid;?>,
				  "c_status":<?php echo $c_status;?>,
				},
				beforeSubmit: function(){},
				success: function(resp,st,xhr,$form){
					// console.log(resp);return;
					if($.trim(resp)){
						var dataobj = $.parseJSON($.trim(resp));
						if( dataobj.status == "success" && dataobj.status2=="success"){
							alert("填寫成功");
							$.cookie("race_name","");
							$.cookie("sku","");
							window.location='backstage_index.php?<?php echo 'c_status='.$c_status;?>';
						}
					}
				}
			});
		}
	}

	function setCookie(){
		$.cookie("race_name",$("#race_name").val(),{expires:1});
		$.cookie("sku",$("#sku").val(),{expires:1});
	}

	function getCookie(){
		var getData=new Array();
		getData["race_name"]=$.cookie("race_name");
		getData["sku"]=$.cookie("sku");

		return getData;
	}

  $("#cancel").click(function()
  {
    window.location='backstage_index.php?<?php echo 'c_status='.$c_status.'&kind='.$kind;?>';
  });
	</script>
</body>

</html>
