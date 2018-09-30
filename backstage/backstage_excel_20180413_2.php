<?php
include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
include_once dirname(__FILE__).'/PHPExcel/Classes/PHPExcel.php';
header('Content-Type:text/html; charset=utf-8');

$r_nid = !empty($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$c_status = !empty($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;

// $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
// include($phpbb_root_path.'common/common.php');
// $system->check_session();

//#測試用
// $r_nid=1;//本機
// $c_status=1;//本機
//
// $c_status=1;//測試機
// $r_nid=57;//測試機
//#
$arraySql = "
SELECT
connect.cid,
connect.tid,
connect.r_nid,
connect.start_time,
connect.end_time,
site.place,
grouping.age,
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
  select
  cid,tid,r_nid,start_time,sid,gid,team_name,
  leader_name,leader_mobile,leader_email,
  coach_name,coach_mobile,coach_email,
  supervise_name,supervise_mobile,supervise_email
  from connect
  where r_nid=$r_nid and status=$c_status
)connect
inner join
(
  select sid,place
  from site
)site
on connect.sid=site.sid
inner join
(
  select gid,age
  from grouping
)grouping
on connect.gid=grouping.gid
order by connect.tid
";
$arraySqlResult = $link->prepare($arraySql);
$arraySqlResult->execute();
$array = $arraySqlResult->fetchall();
// pre($array);exit;

// $array =$system->all_card_data_limit();

if (isset($array)) {
    $_content = '';
    $_handle = null;
    $_file = 'save_member.xls';

    if (file_exists($_file)) {
        unlink($_file);
    }
    $_handle = fopen($_file, 'w+');
    $_content .=
  iconv('UTF-8', 'BIG5', '報名時間')."\t".
  iconv('UTF-8', 'BIG5', '最後更新時間')."\t".
  iconv('UTF-8', 'BIG5', '預賽區域')."\t".
  iconv('UTF-8', 'BIG5', '報名組別')."\t".
  iconv('UTF-8', 'BIG5', '隊名')."\t".
  iconv('UTF-8', 'BIG5', '領隊姓名')."\t".
  iconv('UTF-8', 'BIG5', '領隊聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '領隊電子郵件信箱')."\t".
  iconv('UTF-8', 'BIG5', '教練姓名')."\t".
  iconv('UTF-8', 'BIG5', '教練聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '教練電子郵件信箱')."\t".
  iconv('UTF-8', 'BIG5', '管理姓名')."\t".
  iconv('UTF-8', 'BIG5', '管理聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '管理電子郵件信箱')."\t".
  iconv('UTF-8', 'BIG5', '球員#1姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#1生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#1身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#1聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#1球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#1球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#2姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#2生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#2身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#2聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#2球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#2球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#3姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#3生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#3身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#3聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#3球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#3球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#4姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#4生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#4身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#4聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#4球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#4球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#5姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#5生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#5身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#5聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#5球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#5球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#6姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#6生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#6身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#6聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#6球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#6球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#7姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#7生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#7身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#7聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#7球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#7球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#8姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#8生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#8身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#8聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#8球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#8球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#9姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#9生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#9身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#9聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#9球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#9球衣尺寸')."\t".
  iconv('UTF-8', 'BIG5', '球員#10姓名')."\t".
  iconv('UTF-8', 'BIG5', '球員#10生日')."\t".
  iconv('UTF-8', 'BIG5', '球員#10身分證字號或護照號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#10聯絡電話')."\t".
  iconv('UTF-8', 'BIG5', '球員#10球衣號碼')."\t".
  iconv('UTF-8', 'BIG5', '球員#10球衣尺寸')."\n";

    foreach ($array as $value) {
        $tid = $value['tid'];

        if ($c_status == 1) {
            $player_cloump = "
     SELECT
     player.name_player,
     player.birth,
     player.id_card,
     player.mobile,
     player.clothes_back_num,
     player.clothes_size
     from
     (
       SELECT tid,pid
       FROM participate
       where status=$c_status and r_nid=$r_nid and tid=$tid
     )participate
     inner join
     (
       select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
       from player
       where status=1
     )player
     on participate.pid=player.pid
     ";
        } elseif ($c_status == 2) {
            $player_cloump = "
     SELECT
     player.name_player,
     player.birth,
     player.id_card,
     player.mobile,
     player.clothes_back_num,
     player.clothes_size
     from
     (
       SELECT tid,pid
       FROM participate_finals
       where status=$c_status and r_nid=$r_nid and tid=$tid
     )participate_finals
     inner join
     (
       select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
       from player
       where status=1
     )player
     on participate_finals.pid=player.pid
     ";
        }
        $player_cloump_result = $link->prepare($player_cloump);
        $player_cloump_result->execute();
        $player_cloump_rums = $player_cloump_result->rowcount();
        $player_cloump_rows = $player_cloump_result->fetchall();
        // pre($value);
        // $start_time=$value['start_time'];
        // $leader_mobile=$value['leader_mobile'];
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', '\''.$value['start_time'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['place'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['age'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['team_name'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['leader_name'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', '\''.$value['leader_mobile'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['leader_email'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['coach_name'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', '\''.$value['coach_mobile'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['coach_email'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['supervise_name'])."\t";
        $_content .= iconv('UTF-8', 'BIG5//IGNORE', '\''.$value['supervise_mobile'])."\t";
        empty($player_cloump_rows) ? $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['supervise_email'])."\n" : $_content .= iconv('UTF-8', 'BIG5//IGNORE', $value['supervise_email'])."\t";

        $count = ($player_cloump_rums - 1) <= 0 ? 0 : $player_cloump_rums - 1;
        for ($i = 0; $i < $player_cloump_rums; ++$i) {
            $_content .= iconv('UTF-8', 'BIG5//IGNORE', $player_cloump_rows[$i]['name_player'])."\t";
            $_content .= iconv('UTF-8', 'BIG5//IGNORE', $player_cloump_rows[$i]['birth'])."\t";
            $_content .= iconv('UTF-8', 'BIG5//IGNORE', $player_cloump_rows[$i]['id_card'])."\t";
            $_content .= iconv('UTF-8', 'BIG5//IGNORE', '\''.$player_cloump_rows[$i]['mobile'])."\t";
            $_content .= iconv('UTF-8', 'BIG5//IGNORE', $player_cloump_rows[$i]['clothes_back_num'])."\t";
            $i == $count ? $_content .= iconv('UTF-8', 'BIG5//IGNORE', $player_cloump_rows[$i]['clothes_size'])."\n" : $_content .= iconv('UTF-8', 'BIG5//IGNORE', $player_cloump_rows[$i]['clothes_size'])."\t";
        }

        // $_content.=iconv("UTF-8", "BIG5//IGNORE", $date) ."\t";//範例
  // $_content.=iconv("UTF-8", "BIG5", '\''.$card_num) ."\t";//範例
  // $_content.=iconv("UTF-8", "BIG5//IGNORE", $use_staus) ."\n";//範例
    }
    // exit;
    fwrite($_handle, $_content);
    fclose($_handle);
    unset($array,$_content);
    header('location:'.$_file);
    exit;
} else {
    ?>
 <script>
    alert("目前尚無任何記錄可供匯出…");
    </script>
<?php
 exit;
}
?>
