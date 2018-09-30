<?php

include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
include_once dirname(__FILE__).'/PHPExcel/Classes/PHPExcel.php';
$r_nid = !empty($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$c_status = !empty($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;
$kind = !empty($_GET['kind']) && trim($_GET['kind']) ? trim($_GET['kind']) : 0;
//#ip
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
} else {
    $ip = '無法取得IP位址！';
}

$start_time = date('Y-m-d H:i:s');
$end_time = date('Y-m-d H:i:s');

//#測試用
// $r_nid=1;
// $c_status=1;
//#
$excelIn = "INSERT INTO excel(r_nid,status,kind,ip,start_time,end_time) VALUES($r_nid,$c_status,$kind,'$ip','$start_time','$end_time')";
// pre($excelIn);exit
$excelInRe = $link->prepare($excelIn);
$excelInRe->execute();

$objPHPExcel = new PHPExcel();

//#第一個資料表開始
$objPHPExcel->setActiveSheetIndex(0); //指定目前要編輯的工作表 ，預設0是指第一個工作表

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //報名日期
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //最後更新時間
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10); //預賽區域
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15); //報名組別
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30); //隊名
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15); //領隊姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15); //領隊聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30); //領隊電子郵件信箱
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15); //教練姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15); //教練聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30); //教練電子郵件信箱
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15); //管理姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15); //管理聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30); //管理電子郵件信箱
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15); //球員#1姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15); //球員#1生日
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30); //球員#1身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15); //球員#1聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15); //球員#1球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15); //球員#1球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(15); //球員#2姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(15); //球員#2生日
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(30); //球員#2身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15); //球員#2聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15); //球員#2球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15); //球員#2球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15); //球員#3姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15); //球員#3生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(30); //球員#3身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15); //球員#3聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(15); //球員#3球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15); //球員#3球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15); //球員#4姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15); //球員#4生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(30); //球員#4身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15); //球員#4聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(15); //球員#4球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15); //球員#4球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(15); //球員#5姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(15); //球員#5生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(30); //球員#5身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(15); //球員#5聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(15); //球員#5球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(15); //球員#5球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(15); //球員#6姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(15); //球員#6生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(30); //球員#6身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(15); //球員#6聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(15); //球員#6球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(15); //球員#6球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(15); //球員#7姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(15); //球員#7生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setWidth(30); //球員#7身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setWidth(15); //球員#7聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setWidth(15); //球員#7球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(15); //球員#7球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(15); //球員#8姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(15); //球員#8生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(30); //球員#8身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(15); //球員#8聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(15); //球員#8球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(15); //球員#8球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(15); //球員#9姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(15); //球員#9生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(30); //球員#9身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(15); //球員#9聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(15); //球員#9球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(15); //球員#9球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(15); //球員#10姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(15); //球員#10生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(30); //球員#10身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BT')->setWidth(15); //球員#10聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BU')->setWidth(15); //球員#10球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BV')->setWidth(15); //球員#10球衣尺寸

$objPHPExcel->getActiveSheet()->setCellValue('A1', '報名日期');
$objPHPExcel->getActiveSheet()->setCellValue('B1', '最後更新時間');
$objPHPExcel->getActiveSheet()->setCellValue('C1', '預賽區域');
$objPHPExcel->getActiveSheet()->setCellValue('D1', '報名組別');
$objPHPExcel->getActiveSheet()->setCellValue('E1', '隊名');
$objPHPExcel->getActiveSheet()->setCellValue('F1', '領隊姓名');
$objPHPExcel->getActiveSheet()->setCellValue('G1', '領隊聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('H1', '領隊電子郵件信箱');
$objPHPExcel->getActiveSheet()->setCellValue('I1', '教練姓名');
$objPHPExcel->getActiveSheet()->setCellValue('J1', '教練聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('K1', '教練電子郵件信箱');
$objPHPExcel->getActiveSheet()->setCellValue('L1', '管理姓名');
$objPHPExcel->getActiveSheet()->setCellValue('M1', '管理聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('N1', '管理電子郵件信箱');
$objPHPExcel->getActiveSheet()->setCellValue('O1', '球員#1姓名');
$objPHPExcel->getActiveSheet()->setCellValue('P1', '球員#1生日');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', '球員#1身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('R1', '球員#1聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('S1', '球員#1球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('T1', '球員#1球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('U1', '球員#2姓名');
$objPHPExcel->getActiveSheet()->setCellValue('V1', '球員#2生日');
$objPHPExcel->getActiveSheet()->setCellValue('W1', '球員#2身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('X1', '球員#2聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', '球員#2球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', '球員#2球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AA1', '球員#3姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AB1', '球員#3生日');
$objPHPExcel->getActiveSheet()->setCellValue('AC1', '球員#3身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AD1', '球員#3聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AE1', '球員#3球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AF1', '球員#3球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AG1', '球員#4姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AH1', '球員#4生日');
$objPHPExcel->getActiveSheet()->setCellValue('AI1', '球員#4身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '球員#4聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AK1', '球員#4球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AL1', '球員#4球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AM1', '球員#5姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AN1', '球員#5生日');
$objPHPExcel->getActiveSheet()->setCellValue('AO1', '球員#5身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AP1', '球員#5聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AQ1', '球員#5球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AR1', '球員#5球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AS1', '球員#6姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AT1', '球員#6生日');
$objPHPExcel->getActiveSheet()->setCellValue('AU1', '球員#6身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AV1', '球員#6聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AW1', '球員#6球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AX1', '球員#6球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AY1', '球員#7姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AZ1', '球員#7生日');
$objPHPExcel->getActiveSheet()->setCellValue('BA1', '球員#7身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BB1', '球員#7聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BC1', '球員#7球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BD1', '球員#7球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('BE1', '球員#8姓名');
$objPHPExcel->getActiveSheet()->setCellValue('BF1', '球員#8生日');
$objPHPExcel->getActiveSheet()->setCellValue('BG1', '球員#8身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BH1', '球員#8聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BI1', '球員#8球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BJ1', '球員#8球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('BK1', '球員#9姓名');
$objPHPExcel->getActiveSheet()->setCellValue('BL1', '球員#9生日');
$objPHPExcel->getActiveSheet()->setCellValue('BM1', '球員#9身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BN1', '球員#9聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BO1', '球員#9球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BP1', '球員#9球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('BQ1', '球員#10姓名');
$objPHPExcel->getActiveSheet()->setCellValue('BR1', '球員#10生日');
$objPHPExcel->getActiveSheet()->setCellValue('BS1', '球員#10身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BT1', '球員#10聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BU1', '球員#10球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BV1', '球員#10球衣尺寸');

if (!empty($c_status)) {
    if ($c_status == 1) {
        $objPHPExcel->getActiveSheet()->setTitle('預賽'); //设置工作表名称

        $connectSql = "SELECT
    connect.cid,
    connect.tid,
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
    connect.supervise_email,
    participate.pid,
    player.name_player,
    player.birth,
    player.id_card,
    player.mobile,
    player.clothes_back_num,
    player.clothes_size
    from
    (
      select
      cid,tid,start_time,end_time,sid,gid,team_name,
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
    left join
    (
      SELECT tid,pid
      FROM participate
      where status=1 and r_nid=$r_nid
      group by tid
    )participate
    on connect.tid=participate.tid
    left join
    (
      select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
      from player
      where status=1
    )player
    on participate.pid=player.pid
    order by connect.start_time desc
    ";
    } elseif ($c_status == 2) {
        $objPHPExcel->getActiveSheet()->setTitle('決賽'); //设置工作表名称

        $connectSql = "SELECT
    connect.cid,
    connect.tid,
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
    connect.supervise_email,
    participate_finals.pid,
    player.name_player,
    player.birth,
    player.id_card,
    player.mobile,
    player.clothes_back_num,
    player.clothes_size
    from
    (
      select
      cid,tid,start_time,end_time,sid,gid,team_name,
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
    left join
    (
      SELECT tid,pid
      FROM participate_finals
      where status=1 and r_nid=$r_nid
      group by tid
    )participate_finals
    on connect.tid=participate_finals.tid
    left join
    (
      select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
      from player
      where status=1
    )player
    on participate_finals.pid=player.pid
    order by connect.start_time desc
    ";
    }
} elseif (empty($c_status)) {
    if ($kind == 1) {
        $objPHPExcel->getActiveSheet()->setTitle('(封存)預賽'); //设置工作表名称

        $connectSql = "SELECT
    connect.cid,
    connect.tid,
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
    connect.supervise_email,
    participate.pid,
    player.name_player,
    player.birth,
    player.id_card,
    player.mobile,
    player.clothes_back_num,
    player.clothes_size
    from
    (
      select
      cid,tid,start_time,end_time,sid,gid,team_name,
      leader_name,leader_mobile,leader_email,
      coach_name,coach_mobile,coach_email,
      supervise_name,supervise_mobile,supervise_email
      from connect
      where r_nid=$r_nid and kind=$kind
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
    left join
    (
      SELECT tid,pid
      FROM participate
      where status=1 and r_nid=$r_nid
      group by tid
    )participate
    on connect.tid=participate.tid
    left join
    (
      select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
      from player
      where status=1
    )player
    on participate.pid=player.pid
    order by connect.start_time desc
    ";
    } elseif ($kind == 2) {
        $objPHPExcel->getActiveSheet()->setTitle('(封存)決賽'); //设置工作表名称

        $connectSql = "SELECT
    connect.cid,
    connect.tid,
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
    connect.supervise_email,
    participate_finals.pid,
    player.name_player,
    player.birth,
    player.id_card,
    player.mobile,
    player.clothes_back_num,
    player.clothes_size
    from
    (
      select
      cid,tid,start_time,end_time,sid,gid,team_name,
      leader_name,leader_mobile,leader_email,
      coach_name,coach_mobile,coach_email,
      supervise_name,supervise_mobile,supervise_email
      from connect
      where r_nid=$r_nid and kind=$kind
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
    left join
    (
      SELECT tid,pid
      FROM participate_finals
      where status=1 and r_nid=$r_nid
      group by tid
    )participate_finals
    on connect.tid=participate_finals.tid
    left join
    (
      select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
      from player
      where status=1
    )player
    on participate_finals.pid=player.pid
    order by connect.start_time desc
    ";
    }
}
// pre($connectSql);exit;
$connectSqlResult = $link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRums = $connectSqlResult->rowcount();
$connectSqlRows = $connectSqlResult->fetchall(PDO::FETCH_ASSOC);
// pre($connectSqlRows);exit;

foreach ($connectSqlRows as $cK1 => $cV1) {
    $tid = !empty($cV1['tid']) ? $cV1['tid'] : 0;
    $cK1 = $cK1 + 2; //一定要加ˋ2,才會從A2,B2,C2...開始

    $objPHPExcel->getActiveSheet()->setCellValue('A'.$cK1, $cV1['start_time']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$cK1, $cV1['end_time']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$cK1, $cV1['place']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$cK1, $cV1['age']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$cK1, $cV1['team_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$cK1, $cV1['leader_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$cK1, $cV1['leader_mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$cK1, $cV1['leader_email']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$cK1, $cV1['coach_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$cK1, $cV1['coach_mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$cK1, $cV1['coach_email']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$cK1, $cV1['supervise_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$cK1, $cV1['supervise_mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$cK1, $cV1['supervise_email']);

    //#連接球員資料表(用tid)
    $participateSql = "SELECT
  player.pid,
  player.name_player,
  player.birth,
  player.id_card,
  player.mobile,
  player.clothes_back_num,
  player.clothes_size
  from
  (
    select partid,mid,tid,r_nid,pid,status,start_time,end_time
    from participate
    where tid=$tid and status=1
    order by pid desc
  )participate
  inner join
  (
    select pid,mid,status,name_player,birth,id_card,mobile,clothes_back_num,clothes_size,start_time,end_time
    from player
    where status=1
  )player
  on participate.pid=player.pid
  ";
    // pre($participateSql);
    $participateSqlResult = $link->prepare($participateSql);
    $participateSqlResult->execute();
    $participateSqlRums = $participateSqlResult->rowcount();
    $participateSqlRows = $participateSqlResult->fetchall(PDO::FETCH_ASSOC);
    // pre($participateSqlRows);

    //#連接球員名單直接在欄位塞10個名單
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$cK1, !empty($participateSqlRows[0]['name_player']) ? $participateSqlRows[0]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$cK1, !empty($participateSqlRows[0]['birth']) ? $participateSqlRows[0]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$cK1, !empty($participateSqlRows[0]['id_card']) ? $participateSqlRows[0]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$cK1, !empty($participateSqlRows[0]['mobile']) ? $participateSqlRows[0]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$cK1, !empty($participateSqlRows[0]['clothes_back_num']) ? $participateSqlRows[0]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$cK1, !empty($participateSqlRows[0]['clothes_size']) ? $participateSqlRows[0]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('U'.$cK1, !empty($participateSqlRows[1]['name_player']) ? $participateSqlRows[1]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$cK1, !empty($participateSqlRows[1]['birth']) ? $participateSqlRows[1]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$cK1, !empty($participateSqlRows[1]['id_card']) ? $participateSqlRows[1]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$cK1, !empty($participateSqlRows[1]['mobile']) ? $participateSqlRows[1]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$cK1, !empty($participateSqlRows[1]['clothes_back_num']) ? $participateSqlRows[1]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$cK1, !empty($participateSqlRows[1]['clothes_size']) ? $participateSqlRows[1]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$cK1, !empty($participateSqlRows[2]['name_player']) ? $participateSqlRows[2]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$cK1, !empty($participateSqlRows[2]['birth']) ? $participateSqlRows[2]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$cK1, !empty($participateSqlRows[2]['id_card']) ? $participateSqlRows[2]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$cK1, !empty($participateSqlRows[2]['mobile']) ? $participateSqlRows[2]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AE'.$cK1, !empty($participateSqlRows[2]['clothes_back_num']) ? $participateSqlRows[2]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AF'.$cK1, !empty($participateSqlRows[2]['clothes_size']) ? $participateSqlRows[2]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('AG'.$cK1, !empty($participateSqlRows[3]['name_player']) ? $participateSqlRows[3]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AH'.$cK1, !empty($participateSqlRows[3]['birth']) ? $participateSqlRows[3]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AI'.$cK1, !empty($participateSqlRows[3]['id_card']) ? $participateSqlRows[3]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$cK1, !empty($participateSqlRows[3]['mobile']) ? $participateSqlRows[3]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AK'.$cK1, !empty($participateSqlRows[3]['clothes_back_num']) ? $participateSqlRows[3]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AL'.$cK1, !empty($participateSqlRows[3]['clothes_size']) ? $participateSqlRows[3]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('AM'.$cK1, !empty($participateSqlRows[4]['name_player']) ? $participateSqlRows[4]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AN'.$cK1, !empty($participateSqlRows[4]['birth']) ? $participateSqlRows[4]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AO'.$cK1, !empty($participateSqlRows[4]['id_card']) ? $participateSqlRows[4]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AP'.$cK1, !empty($participateSqlRows[4]['mobile']) ? $participateSqlRows[4]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AQ'.$cK1, !empty($participateSqlRows[4]['clothes_back_num']) ? $participateSqlRows[4]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AR'.$cK1, !empty($participateSqlRows[4]['clothes_size']) ? $participateSqlRows[4]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('AS'.$cK1, !empty($participateSqlRows[5]['name_player']) ? $participateSqlRows[5]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AT'.$cK1, !empty($participateSqlRows[5]['birth']) ? $participateSqlRows[5]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AU'.$cK1, !empty($participateSqlRows[5]['id_card']) ? $participateSqlRows[5]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AV'.$cK1, !empty($participateSqlRows[5]['mobile']) ? $participateSqlRows[5]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AW'.$cK1, !empty($participateSqlRows[5]['clothes_back_num']) ? $participateSqlRows[5]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AX'.$cK1, !empty($participateSqlRows[5]['clothes_size']) ? $participateSqlRows[5]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('AY'.$cK1, !empty($participateSqlRows[6]['name_player']) ? $participateSqlRows[6]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('AZ'.$cK1, !empty($participateSqlRows[6]['birth']) ? $participateSqlRows[6]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BA'.$cK1, !empty($participateSqlRows[6]['id_card']) ? $participateSqlRows[6]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BB'.$cK1, !empty($participateSqlRows[6]['mobile']) ? $participateSqlRows[6]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BC'.$cK1, !empty($participateSqlRows[6]['clothes_back_num']) ? $participateSqlRows[6]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BD'.$cK1, !empty($participateSqlRows[6]['clothes_size']) ? $participateSqlRows[6]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('BE'.$cK1, !empty($participateSqlRows[7]['name_player']) ? $participateSqlRows[7]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BF'.$cK1, !empty($participateSqlRows[7]['birth']) ? $participateSqlRows[7]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BG'.$cK1, !empty($participateSqlRows[7]['id_card']) ? $participateSqlRows[7]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BH'.$cK1, !empty($participateSqlRows[7]['mobile']) ? $participateSqlRows[7]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BI'.$cK1, !empty($participateSqlRows[7]['clothes_back_num']) ? $participateSqlRows[7]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BJ'.$cK1, !empty($participateSqlRows[7]['clothes_size']) ? $participateSqlRows[7]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('BK'.$cK1, !empty($participateSqlRows[8]['name_player']) ? $participateSqlRows[8]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BL'.$cK1, !empty($participateSqlRows[8]['birth']) ? $participateSqlRows[8]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BM'.$cK1, !empty($participateSqlRows[8]['id_card']) ? $participateSqlRows[8]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BN'.$cK1, !empty($participateSqlRows[8]['mobile']) ? $participateSqlRows[8]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BO'.$cK1, !empty($participateSqlRows[8]['clothes_back_num']) ? $participateSqlRows[8]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BP'.$cK1, !empty($participateSqlRows[8]['clothes_size']) ? $participateSqlRows[8]['clothes_size'] : '');

    $objPHPExcel->getActiveSheet()->setCellValue('BQ'.$cK1, !empty($participateSqlRows[9]['name_player']) ? $participateSqlRows[9]['name_player'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BR'.$cK1, !empty($participateSqlRows[9]['birth']) ? $participateSqlRows[9]['birth'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BS'.$cK1, !empty($participateSqlRows[9]['id_card']) ? $participateSqlRows[9]['id_card'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BT'.$cK1, !empty($participateSqlRows[9]['mobile']) ? $participateSqlRows[9]['mobile'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BU'.$cK1, !empty($participateSqlRows[9]['clothes_back_num']) ? $participateSqlRows[9]['clothes_back_num'] : '');
    $objPHPExcel->getActiveSheet()->setCellValue('BV'.$cK1, !empty($participateSqlRows[9]['clothes_size']) ? $participateSqlRows[9]['clothes_size'] : '');
}
// exit;
//#第一個資料表結束
$race_nameSql = "SELECT * from race_name where r_nid=$r_nid";
$race_nameSqlResult = $link->prepare($race_nameSql);
$race_nameSqlResult->execute();
$race_nameSqlRows = $race_nameSqlResult->fetchall();
$race_nameTitle = $race_nameSqlRows[0]['name'];

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="excel_file.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save('excel_file.xlsx'); //excel2007,直接儲存在此頁同一個資料夾
$objWriter->save('excel_file.xls'); //excel2007之前,直接儲存在此頁同一個資料夾

$objWriter->save('php://output'); //輸出於瀏覽器


?>