<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';

$c_status = isset($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$r_nid = isset($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$rid = isset($_GET['rid']) && trim($_GET['rid']) ? trim($_GET['rid']) : 0;
$siteSqlPage = isset($_GET['siteSqlPage']) && trim($_GET['siteSqlPage']) ? trim($_GET['siteSqlPage']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;

$race_nameSql = "SELECT * from race_name where r_nid=$r_nid";
$race_nameSqlResult = $link->prepare($race_nameSql);
$race_nameSqlResult->execute();
$race_nameSqlRows = $race_nameSqlResult->fetchall();
$race_name_name = !empty($race_nameSqlRows[0]['name']) ? $race_nameSqlRows[0]['name'] : '';

if (empty($c_status)) {
    if (empty($kind)) {
        $groupingSql = 'SELECT * from grouping where status=3 order by gid';
    } elseif (!empty($kind)) {
        $groupingSql = "SELECT * from grouping where status=1 and r_nid=$r_nid order by gid";
    }
} elseif (!empty($c_status)) {
    $groupingSql = "SELECT * from grouping where status=1 and r_nid=$r_nid order by gid";
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
  //   where appear=1 and status=$c_status and r_nid=$r_nid
  // )race
  // inner JOIN grouping
  // on race.gid=grouping.gid
  // order by rid,gid";
}
// pre($groupingSql);
$groupingResult = $link->prepare($groupingSql);
$groupingResult->execute();
$groupingRows = $groupingResult->fetchall();
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <title>年齡分組管理</title>
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
                                    echo empty($kind) ? '<h1>年齡分組管理/'.$race_name_name.'</h1>' : '<h1>封存盃賽查詢/年齡分組管理/'.$race_name_name.'</h1>';
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
                            echo empty($kind) ? '<h2>年齡分組管理/'.$race_name_name.'</h2>' : '<h2>封存盃賽查詢/年齡分組管理/'.$race_name_name.'</h2>';
                          ?>
                      </div>
                        <!-- END Partial Responsive Title -->
                        <!-- Partial Responsive Content -->
                        <div class="row">
                            <div class="col-lg-12">
                            <?php
                              echo empty($kind) ? '<button type="button" id="grouping_add"name="grouping_add" class="btn btn-warning pull-right" style="margin: 10px 5px;">新增</button>' : '';
                            ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class=" col-lg-12 clearfix" style="    margin-bottom: 20px;">
                          <form class="" action="" method="post" id="addform" name="addform">
                            <table id="example-datatable" class="table table-striped table-bordered table-vcenter dataTable no-footer" role="grid" aria-describedby="example-datatable_info">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center ">分組名稱</th>
                                        <th class="text-center ">年齡限制</th>
                                        <th class="text-center "></th>
                                        <th class="text-center ">備註</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                   foreach ($groupingRows as $g => $gr1) {
                                       $kindDisabled = !empty($kind) ? 'disabled' : ''; ?>
                                    <tr role="row" class="odd" id="add_div">
                                        <td class="text-center">
                                          <input type="text" id="age" name="apply[<?php echo $g; ?>][age]" value="<?php echo $gr1['age']; ?>" class="form-control" placeholder="請輸入分組名稱" <?php echo $kindDisabled; ?>>
                                        </td>
                                        <td class="text-center">
                                          <input type="text" id="lowest_birth" name="apply[<?php echo $g; ?>][lowest_birth]" value="<?php echo $gr1['lowest_birth']; ?>" class="form-control input-datepicker" data-date-format="yyyy/mm/dd" placeholder="請輸入最高年齡限制 例:2000/09/01" <?php echo $kindDisabled; ?>>
                                        </td>
                                        <td class="text-center">
                                          後出生
                                          <!-- <input type="text" id="highest_birth" name="highest_birth" value="<?php //echo $gr1['highest_birth'];?>" class="form-control input-datepicker" data-date-format="yyyy/mm/dd" placeholder="請輸入最低年齡限制 例:2003/08/31"> -->
                                        </td>
                                        <td class="text-center">
                                          <?php
                                            echo empty($kind) ? '<button class="btn btn-xs btn-danger" id="'.$gr1['gid'].'" name="" onclick="groupingDel('.$gr1['gid'].');"><i class="fa fa-times"></i></button>' : ''; ?>

                                        </td>
                                    </tr>
                                    <input type="hidden" name="apply[<?php echo $g; ?>][gid]" value="<?php echo $gr1['gid']; ?>">
                                  <?php
                                   }?>
                                  <input type="hidden" class="add" id="new_div">
                                </tbody>
                            </table>
                          </form>
                            <div class="col-lg-12" style="margin:10px 0; ">
                            <?php
                              if (empty($kind)) {
                                  echo '<button type="reset" id="grouping_cancel" name="grouping_cancel" onclick="groupingCancel();"class="btn btn-effect-ripple btn-danger ui-wizard-content ui-formwizard-button pull-right" id="back1">取消</button>';
                                  echo '<button type="submit" id="gruoping_storage" name="gruoping_storage" onclick="groupingStorage();"class="btn btn-effect-ripple btn-primary ui-wizard-content ui-formwizard-button pull-right" id="next1" style="margin: 0 10px; ">儲存</button>';
                              } elseif (!empty($kind)) {
                                  echo '<button type="reset" id="grouping_cancel" name="grouping_cancel" onclick="groupingCancel();"class="btn btn-effect-ripple btn-danger ui-wizard-content ui-formwizard-button pull-right" id="back1">返回上一頁</button>';
                              }
                            ?>
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script src="../jquery-validation-1.17.0/dist/jquery.validate.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
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
           dateFormat:"yyyy/mm/dd"
        };
	  $("#lowest_birth1").datepicker(opt);

      $(document).ready(function()
      {
        var txtId = 1;
        $("#grouping_add").click(function()
        {
          $(".add").before(
            '<tr role="row" class="odd" id="add_div'+txtId+'">'+
                '<td class="text-center">'+
                  '<input type="text" id="age'+txtId+'" name="age[]" value="" class="form-control" placeholder="請輸入分組名稱">'+
                '</td>'+
                '<td class="text-center">'+
                  '<input type="text" id="lowest_birth'+txtId+'" name="lowest_birth[]" value="" class="form-control input-datepicker" data-date-format="yyyy/mm/dd" placeholder="請輸入年齡限制 例:2000/09/01">'+
                '</td>'+
                '<td class="text-center">'+
                '後出生'+
                '</td>'+
                // '<td class="text-center">'+
                //   '<input type="text" id="highest_birth'+txtId+'" name="highest_birth" value="" class="form-control input-datepicker" data-date-format="mm-dd-yyyy" placeholder="請輸入最低年齡限制 例:2003/08/31">'+
                // '</td>'+
                '<td class="text-center">'+
                  '<button class="btn btn-xs btn-danger" id="" name="" onclick="Del('+txtId+');"><i class="fa fa-times"></i></button>'+
                '</td>'+
            '</tr>'+
            '</br>'
           );
          txtId++;

		   $( 'input[name="lowest_birth[]"]' ).datepicker("refresh");

        });
      });

	//remove div
	function Del(id)
	{
	  $("#add_div"+id).remove();
	}
//////以上這段是連在一起的//////

      function groupingDel(gid)
      {
        $.ajax(
        {
          url: 'backstage_groupingmanage_colorbox__groupingdel_ajax.php',//backstage_groupingmanage_colorbox_ajax應該要轉這頁,可這頁會造成移除後多出空白列,backstage_sitemanage_colorbox同樣方式反而沒事
          type:"post",
          cache: true,
          async:false,
          datatype:"json",
          data:
          {
            "gid":gid
          },
          error:function(data)
          {
            alert("填寫失敗");
          },
          success:function(data)
          {
            // console.log(data);return;
            var dataobj2 = $.parseJSON($.trim(data));
            if( dataobj2.status2 == "success" )
            {
              alert("移除　"+dataobj2.age2+"　成功");
              window.location='backstage_race_edit.php?<?php echo 'c_status='.$c_status.'&r_nid='.$r_nid.'&rid='.$rid; ?>';
            }
            else if(dataobj2.status3 == "have")
            {
              alert("此分組有人報名,請勿刪除");
            }
            else if(dataobj2.status2 == "error")
            {
              window.location='backstage_sitemanage_colorbox.php';
            }
          }
        });
      }

      function groupingStorage(id)
      {
        var validform=$("#addform").validate(
        {
          rules:
          {
            'age[]':"required",
            'lowest_birth[]':"required",
          },
          messages:
          {
            'age[]':"<span style='color:#AA0000;'>請輸入分組名稱</span>",
            'lowest_birth[]':"<span style='color:#AA0000;'>請輸入年齡限制</span>",
          }
        });

		var chkResult=validform.form();

		if(chkResult==true){
			$("#addform").ajaxSubmit(
			{
			  url:'backstage_groupingmanage_colorbox_ajax.php',
			  datatype:"json",
			  data:
			  {
				r_nid:<?php echo $r_nid; ?>,
				rid:<?php echo $rid; ?>,
				c_status:<?php echo $c_status; ?>,
			  },
			  beforeSubmit: function(){},
			  success: function(resp,st,xhr,$form)
			  {
				// console.log(resp);return;
				const dataobj = $.parseJSON($.trim(resp));
				if(dataobj.status == "success")
				{
				  alert("填寫成功");
				  window.location='backstage_race_edit.php?<?php echo 'c_status='.$c_status.'&r_nid='.$r_nid.'&rid='.$rid; ?>';
				}
				else if(dataobj.status == "error")
				{
				  window.location='backstage_race_edit.php?<?php echo 'c_status='.$c_status.'&r_nid='.$r_nid.'&rid='.$rid; ?>';
				}
				//var dataobj = jQuery.parseJSON($.trim(resp));
			  }
			});
		}
      }
      //日期選擇器
	  /*
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
           dateFormat:"yyyy/mm/dd"
        };
        $("#lowest_birth").datepicker(opt);

      });
	  */

      function groupingCancel()
      {
        window.location='backstage_race_edit.php?<?php echo 'c_status='.$c_status.'&r_nid='.$r_nid.'&rid='.$rid.'&kind='.$kind;?>';
      }
    </script>
</body>

</html>
