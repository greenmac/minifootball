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
$pid=!empty($_GET['pid'])&&trim($_GET['pid'])?trim($_GET['pid']):0;
$gid=isset($_GET['gid'])&&trim($_GET['tid'])?trim($_GET['gid']):0;

$groupingSql="SELECT * from grouping where gid=$gid";
$groupingSqlResult=$link->prepare($groupingSql);
$groupingSqlResult->execute();
$groupingSqlRows=$groupingSqlResult->fetchall();
$lowest_birth=strtotime($groupingSqlRows[0]['lowest_birth']);
// pre($groupingSql);
if($c_status==1)
{
  ##$participateSql:在子查詢要先SUM跟group by(為了要命名加總的status),is null極度重要,tid要在left join的table一開始就設)
  ##預賽未上場名單
  $participateSql="SELECT
    participate.partid,
    participate.mid,
    participate.tid,
    participate.r_nid,
    participate.pid as participate_pid,
    participate.status as participate_status,participate.participate_sum_status,
    player.pid,
    player.mid,
    player.status,
    player.name_player,
    player.birth,
    player.id_card
    from
    (
      select *
      FROM player
      where status=1
    )player
    left JOIN
    (
     select partid,mid,tid,r_nid,pid,status,sum(status) as participate_sum_status
     from participate
     where tid=$tid
     group by pid
    )participate
    on player.pid=participate.pid
    where player.mid=$mid and participate.status is NULL or participate.participate_sum_status=0
    group by pid
    order by pid desc";

  $participateSqlResult=$link->prepare($participateSql);
  $participateSqlResult->execute();
  $participateSqlRows=$participateSqlResult->fetchall();

}
elseif($c_status==2)
{
  ##$participate_finalsSql:在子查詢要先SUM跟group by(為了要命名加總的status),is null極度重要,tid要在left join的table一開始就設)
  ##決賽未上場名單
  $participate_finalsSql="SELECT
    participate_finals.part_fid,
    participate_finals.mid,
    participate_finals.tid,
    participate_finals.r_nid,
    participate_finals.pid as participate_finals_pid,
    participate_finals.status as participate_finals_status,participate_finals.participate_finals_sum_status,
    player.pid,
    player.mid,
    player.status,
    player.name_player,
    player.birth,
    player.id_card
    from
    (
      select *
      FROM player
      where status=1
    )player
    left JOIN
    (
     select part_fid,mid,tid,r_nid,pid,status,sum(status) as participate_finals_sum_status
     from participate_finals
     where tid=$tid
     group by pid
    )participate_finals
    on player.pid=participate_finals.pid
    where player.mid=$mid and participate_finals.status is NULL or participate_finals.participate_finals_sum_status=0
    group by pid
    order by pid desc";
  $participate_finalsSqlResult=$link->prepare($participate_finalsSql);
  $participate_finalsSqlResult->execute();
  $participate_finalsSqlRows=$participate_finalsSqlResult->fetchall();
}
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
    <link rel="stylesheet" type="text/css" href="dist/css/bootstrap-datepicker3.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <body>
        <!--主體 -->
        <section class="main main_bottom">
    <div class="assign_row">
      <?php

        switch($c_status)
        {
          case 1:
            foreach($participateSqlRows as $k => $par)
            {
              $birth=strtotime($par['birth']);
              if($birth>=$lowest_birth)
              {
                $participatePid=$par['pid'];
                //$chk=$participateSqlRows['status']==1 ? 'checked':'';##在input最後面加上$chk
                echo '
                <input type="checkbox" onclick="chioceClick('.$participatePid.','.$k.');" id="status'.$k.'" class="'.$par["pid"].'" name="status" value="'.$par['status'].'">
                <label for="status'.$k.'" >'.$par['name_player'].'<i  class="fas fa-check-circle pull-right"></i></label>';
              }
            };
            break;
          case 2:
            foreach($participate_finalsSqlRows as $t => $par_f)
            {
              $birth_f=strtotime($par_f['birth']);
              if($birth_f>=$lowest_birth)
              {
				  $participatePid=$par_f['pid'];
                //$chk=$participateSqlRows['status']==1 ? 'checked':'';##在input最後面加上$chk
                echo'
                <input type="checkbox" onclick="chioceClick('.$participatePid.','.$t.');" id="status'.$t.'" class="'.$par_f["pid"].'" name="status" value="'.$par_f['status'].'">
                <label for="status'.$t.'">'.$par_f['name_player'].'<i class="fas fa-check-circle pull-right"></i></label>';
              }
            };
            break;
          default:
            echo 'error';
        }
      ?>

        <!-- <input type="checkbox" id="c1"  checked="">
        <label for="c1">葉舒眠<i class="fas fa-check-circle pull-right"></i></label>

        <input type="checkbox" id="c2" >
        <label for="c2">林瑞君<i class="fas fa-check-circle pull-right"></i></label>

        <input type="checkbox" id="c3" >
        <label for="c3">莊凱俊<i class="fas fa-check-circle pull-right"></i></label>

        <input type="checkbox" id="c4" >
        <label for="c4">李保玲<i class="fas fa-check-circle pull-right"></i></label> -->

    </div>
        </section>
        <?php
        switch($c_status)
        {
          case 1:
            echo '<input class="green_btn" type="button" id="addsend" name="addsend" value="指派勾選球員">';
            break;
          case 2:
            echo '<input class="green_btn" type="button" id="addsend" name="addsend" value="指派勾選球員">';
            break;
        }
        ?>
        <!-- <input class="green_btn" data-toggle="modal" data-target=".bs-example-modal-lg" type="button" id="addsend" name="addsend" value="指派勾選球員"> -->
        <!--主體 end-->
    </body>
     <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                  <div class="modal_msg">
        <div class="modal_icon">
            <i class="fas fa-exclamation-circle "></i>
        </div>
        <p class="modal_msg_text">更替次數已超過</p>
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

    function chioceClick(partidpid,t)
    {
		var sel = $("input:checkbox:checked").map(function(){
			return $(this).attr('class');
		}).get();

		if(partidpid)
		{
			$.ajax(
			{
				type: "POST",
				url: 'appoint_list_ajax.php',
				cache: false,
				data:
				{
				  'pid':sel.join(','),
				  'partidpid':partidpid,
				  'r_nid':<?php echo $r_nid;?>,
				  'c_status':<?php echo $c_status;?>,
				  'chioce':1,
				},
				error: function()
				{
				  alert('Ajax request 發生錯誤');
				},
				success: function(ddd)
				{
					// console.log(ddd);return;
				  var data2obj=$.parseJSON($.trim(ddd));
				  if(data2obj.chioceMessage=="have")
				  {
					$('#status'+t).attr('checked',false);
					alert('此球員已重複報名，請與此球員聯繫確認');
					return;
				  }
				}
			})
		}
    }

	function Chk_status(){
		alert('身分證確認');
	}

    $().ready(function()
    {
      $('#addsend').click(function()
      {
        let sel='';
        const status=$('input[name="status"]:checked').each(function(i,v)
        {
          sel+=$(this).attr("class")+',';
          // console.log(i);return;
          //console.log(v);return;
          //console.log($(this).attr("class"));
        });
        // console.log(sel);

        $.ajax(
        {
          url: "appoint_list_ajax.php",
          type:"post",
          datatype:"json",
          data:
          {
            "mid":<?php echo $mid;?>,
            "tid":<?php echo $tid;?>,
            "r_nid":<?php echo $r_nid;?>,
            "c_status":<?php echo $c_status;?>,
            "pid":sel,
            'chioce':2,
          },
          error:function(xhr, status, error)
          {
            const err = eval("(" + xhr.responseText + ")");
            alert(err.Message);
            alert(data);return;
            //alert("指派失敗");
          },
          success: function(data)
          {
            // console.log(data);return;
            var dataobj=$.parseJSON($.trim(data));
            switch(dataobj.factor)
            {
              case 1:
                alert("指派球員不滿7人");
                return;
                break;
              case 2:
                alert("指派球員超過10人");
                return;
                break;
              case 3:
                alert("指派球員成功");
                window.location='appoint_index.php?<?php echo 'm_id='.$m_id.'&mid='.$mid.'&r_nid='.$r_nid.'&rid='.$rid.'&tid='.$tid.'&c_status='.$c_status.'&connect_tid='.$connect_tid.'&gid='.$gid;?>';
                break;
              case 4:
                alert("新指派球員超出決賽替換球員數\n請保留預賽球員最低7人\n");
                return;
              default:
                return;
            }
          }
        });
      })
    })
    </script>
</html>
