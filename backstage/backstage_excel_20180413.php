<?php

include_once dirname(dirname(__FILE__)).'/link.php';
include_once dirname(dirname(__FILE__)).'/function.php';
include_once dirname(__FILE__).'/PHPExcel/Classes/PHPExcel.php';
$r_nid = !empty($_GET['r_nid']) && trim($_GET['r_nid']) ? trim($_GET['r_nid']) : 0;
$c_status = !empty($_GET['c_status']) && trim($_GET['c_status']) ? trim($_GET['c_status']) : 0;

//#測試用
$r_nid = 1;
$c_status = 1;
//#

$objPHPExcel = new PHPExcel();

//#第一個資料表開始
$objPHPExcel->setActiveSheetIndex(0); //指定目前要編輯的工作表 ，預設0是指第一個工作表
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
  participate.pid
  from
  (
    select
    cid,tid,start_time,sid,gid,team_name,
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
    SELECT tid,GROUP_CONCAT(pid order by pid) as pid
    FROM participate
    where status=1 and r_nid=1
    group by tid
  )participate
  on connect.tid=participate.tid
  order by connect.tid
  ";
}
// elseif($c_status==2)
// {
//   $objPHPExcel->getActiveSheet()->setTitle('決賽');//设置工作表名称
// }
// pre($connectSql);exit;
$connectSqlResult = $link->prepare($connectSql);
$connectSqlResult->execute();
$connectSqlRows = $connectSqlResult->fetchall(PDO::FETCH_ASSOC);
$connectSqlRums = $connectSqlResult->rowcount();
// pre($connectSqlRows);exit;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30); //時間戳記
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); //預賽區域
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); //報名組別
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); //隊名
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); //領隊姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); //領隊聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30); //領隊電子郵件信箱
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20); //教練姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20); //教練聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30); //教練電子郵件信箱
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); //管理姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20); //管理聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30); //管理電子郵件信箱
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20); //球員#1姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20); //球員#1生日
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(40); //球員#1身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20); //球員#1聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20); //球員#1球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20); //球員#1球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20); //球員#2姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20); //球員#2生日
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(40); //球員#2身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20); //球員#2聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20); //球員#2球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20); //球員#2球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20); //球員#3姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20); //球員#3生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(40); //球員#3身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20); //球員#3聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20); //球員#3球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(20); //球員#3球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20); //球員#4姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20); //球員#4生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(40); //球員#4身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(20); //球員#4聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20); //球員#4球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(20); //球員#4球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20); //球員#5姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20); //球員#5生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(40); //球員#5身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(20); //球員#5聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(20); //球員#5球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(20); //球員#5球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(20); //球員#6姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20); //球員#6生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(40); //球員#6身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(20); //球員#6聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(20); //球員#6球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(20); //球員#6球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20); //球員#7姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(20); //球員#7生日
$objPHPExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(40); //球員#7身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BA')->setWidth(20); //球員#7聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BB')->setWidth(20); //球員#7球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BC')->setWidth(20); //球員#7球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(20); //球員#8姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20); //球員#8生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(40); //球員#8身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(20); //球員#8聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(20); //球員#8球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(20); //球員#8球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(20); //球員#9姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20); //球員#9生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(40); //球員#9身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(20); //球員#9聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(20); //球員#9球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(20); //球員#9球衣尺寸
$objPHPExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20); //球員#10姓名
$objPHPExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20); //球員#10生日
$objPHPExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(40); //球員#10身分證字號或護照號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(20); //球員#10聯絡電話
$objPHPExcel->getActiveSheet()->getColumnDimension('BT')->setWidth(20); //球員#10球衣號碼
$objPHPExcel->getActiveSheet()->getColumnDimension('BU')->setWidth(20); //球員#10球衣尺寸

$objPHPExcel->getActiveSheet()->setCellValue('A1', '時間戳記');
$objPHPExcel->getActiveSheet()->setCellValue('B1', '預賽區域');
$objPHPExcel->getActiveSheet()->setCellValue('C1', '報名組別');
$objPHPExcel->getActiveSheet()->setCellValue('D1', '隊名');
$objPHPExcel->getActiveSheet()->setCellValue('E1', '領隊姓名');
$objPHPExcel->getActiveSheet()->setCellValue('F1', '領隊聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('G1', '領隊電子郵件信箱');
$objPHPExcel->getActiveSheet()->setCellValue('H1', '教練姓名');
$objPHPExcel->getActiveSheet()->setCellValue('I1', '教練聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('J1', '教練電子郵件信箱');
$objPHPExcel->getActiveSheet()->setCellValue('K1', '管理姓名');
$objPHPExcel->getActiveSheet()->setCellValue('L1', '管理聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('M1', '管理電子郵件信箱');
$objPHPExcel->getActiveSheet()->setCellValue('N1', '球員#1姓名');
$objPHPExcel->getActiveSheet()->setCellValue('O1', '球員#1生日');
$objPHPExcel->getActiveSheet()->setCellValue('P1', '球員#1身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', '球員#1聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('R1', '球員#1球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('S1', '球員#1球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('T1', '球員#2姓名');
$objPHPExcel->getActiveSheet()->setCellValue('U1', '球員#2生日');
$objPHPExcel->getActiveSheet()->setCellValue('V1', '球員#2身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('W1', '球員#2聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('X1', '球員#2球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', '球員#2球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', '球員#3姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AA1', '球員#3生日');
$objPHPExcel->getActiveSheet()->setCellValue('AB1', '球員#3身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AC1', '球員#3聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AD1', '球員#3球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AE1', '球員#3球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AF1', '球員#4姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AG1', '球員#4生日');
$objPHPExcel->getActiveSheet()->setCellValue('AH1', '球員#4身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AI1', '球員#4聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '球員#4球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AK1', '球員#4球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AL1', '球員#5姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AM1', '球員#5生日');
$objPHPExcel->getActiveSheet()->setCellValue('AN1', '球員#5身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AO1', '球員#5聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AP1', '球員#5球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AQ1', '球員#5球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AR1', '球員#6姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AS1', '球員#6生日');
$objPHPExcel->getActiveSheet()->setCellValue('AT1', '球員#6身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AU1', '球員#6聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('AV1', '球員#6球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('AW1', '球員#6球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('AX1', '球員#7姓名');
$objPHPExcel->getActiveSheet()->setCellValue('AY1', '球員#7生日');
$objPHPExcel->getActiveSheet()->setCellValue('AZ1', '球員#7身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BA1', '球員#7聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BB1', '球員#7球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BC1', '球員#7球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('BD1', '球員#8姓名');
$objPHPExcel->getActiveSheet()->setCellValue('BE1', '球員#8生日');
$objPHPExcel->getActiveSheet()->setCellValue('BF1', '球員#8身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BG1', '球員#8聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BH1', '球員#8球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BI1', '球員#8球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('BJ1', '球員#9姓名');
$objPHPExcel->getActiveSheet()->setCellValue('BK1', '球員#9生日');
$objPHPExcel->getActiveSheet()->setCellValue('BL1', '球員#9身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BM1', '球員#9聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BN1', '球員#9球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BO1', '球員#9球衣尺寸');
$objPHPExcel->getActiveSheet()->setCellValue('BP1', '球員#10姓名');
$objPHPExcel->getActiveSheet()->setCellValue('BQ1', '球員#10生日');
$objPHPExcel->getActiveSheet()->setCellValue('BR1', '球員#10身分證字號或護照號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BS1', '球員#10聯絡電話');
$objPHPExcel->getActiveSheet()->setCellValue('BT1', '球員#10球衣號碼');
$objPHPExcel->getActiveSheet()->setCellValue('BU1', '球員#10球衣尺寸');

$beginStart = 2;

foreach ($connectSqlRows as $cK1 => $cV1) {
    $tid = !empty($cV1['tid']) ? $cV1['tid'] : 0;
    $pid = !empty($cV1['pid']) ? $cV1['pid'] : 0;
    // pre($tid);
  $cK1 = $cK1 + 2; //一定要加ˋ2,才會從A2,B2,C2...開始
  $objPHPExcel->getActiveSheet()->setCellValue('A'.$cK1, $cV1['start_time']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$cK1, $cV1['place']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$cK1, $cV1['age']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$cK1, $cV1['team_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$cK1, $cV1['leader_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$cK1, $cV1['leader_mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$cK1, $cV1['leader_email']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$cK1, $cV1['coach_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$cK1, $cV1['coach_mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$cK1, $cV1['coach_email']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$cK1, $cV1['supervise_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$cK1, $cV1['supervise_mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$cK1, $cV1['supervise_email']);

    $participateSql = "
  SELECT *
  from
  (
    select partid,pid
    from participate
    where status=1 and r_nid=r_nid and tid=$tid and pid in ($pid)
  )participate
  inner join
  (
    select pid,name_player,birth,id_card,mobile,clothes_back_num,clothes_size
    from player
    where status=1
  )player
  on participate.pid=player.pid
  group by partid
  ";
    $participateSqlResult = $link->prepare($participateSql);
    $participateSqlResult->execute();
    $participateSqlRums = $participateSqlResult->rowcount();
    $participateSqlRows = $participateSqlResult->fetchall(PDO::FETCH_ASSOC);
    // pre($participateSqlRows);
    // foreach($participateSqlRows as $pK1=>$pV1)
    // {

    $playerCloumn = array(
        0 => array(
            0 => 'N',
            1 => 'O',
            2 => 'P',
            3 => 'Q',
            4 => 'R',
            5 => 'S',
        ),
        1 => array(
            0 => 'T',
            1 => 'U',
            2 => 'V',
            3 => 'W',
            4 => 'X',
            5 => 'Y',
        ),
        2 => array(
            0 => 'Z',
            1 => 'AA',
            2 => 'AB',
            3 => 'AC',
            4 => 'AD',
            5 => 'AE',
        ),

        3 => array(
            0 => 'AF',
            1 => 'AG',
            2 => 'AH',
            3 => 'AI',
            4 => 'AJ',
            5 => 'AK',
        ),

        4 => array(
            0 => 'AL',
            1 => 'AM',
            2 => 'AN',
            3 => 'AO',
            4 => 'AP',
            5 => 'AQ',
        ),

        5 => array(
            0 => 'AR',
            1 => 'AS',
            2 => 'AT',
            3 => 'AU',
            4 => 'AV',
            5 => 'AW',
        ),

        6 => array(
            0 => 'AX',
            1 => 'AY',
            2 => 'AZ',
            3 => 'BA',
            4 => 'BB',
            5 => 'BC',
        ),

        7 => array(
            0 => 'BD',
            1 => 'BE',
            2 => 'BF',
            3 => 'BG',
            4 => 'BH',
            5 => 'BI',
        ),

        8 => array(
            0 => 'BJ',
            1 => 'BK',
            2 => 'BL',
            3 => 'BM',
            4 => 'BN',
            5 => 'BO',
        ),

        9 => array(
            0 => 'BP',
            1 => 'BQ',
            2 => 'BR',
            3 => 'BS',
            4 => 'BT',
            5 => 'BU',
        ),
   );

    //pre($participateSqlRows);exit;

    foreach ($participateSqlRows as $k => $v) {
        $column = '';

        $temp = array();
        for ($i = 0; $i < 6; ++$i) {
            if (!in_array($v['id_card'], $temp) || 1 == 1) {
                $column = $playerCloumn[$k][$i].$beginStart;
                /*
                $column1=$playerCloumn[$k][1];
                $column2=$playerCloumn[$k][2];
                $column3=$playerCloumn[$k][3];
                $column4=$playerCloumn[$k][4];
                $column5=$playerCloumn[$k][5];
                */

                $objPHPExcel->getActiveSheet()->setCellValue($column, $v['name_player']);
                $objPHPExcel->getActiveSheet()->setCellValue($column, $v['birth']);
                $objPHPExcel->getActiveSheet()->setCellValue($column, $v['id_card']);
                $objPHPExcel->getActiveSheet()->setCellValue($column, $v['mobile']);
                $objPHPExcel->getActiveSheet()->setCellValue($column, $v['clothes_back_num']);
                $objPHPExcel->getActiveSheet()->setCellValue($column, $v['clothes_size']);

                $temp[] = $v['id_card'];

                echo $column.'</br>';
            }
        }

        /*
        echo $column0.'</br>';
        echo $column1.'</br>';
        echo $column2.'</br>';
        echo $column3.'</br>';
        echo $column4.'</br>';
        echo $column5.'</br>';

        $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['name_player']);
        $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['birth']);
        $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['id_card']);
        $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['mobile']);
        $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['clothes_back_num']);
        $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['clothes_size']);
        */
    }

    /*
    foreach($participateSqlRows as $k=>$v)
    {



    $column1=$playerCloumn[$k][$i];
    $column2=$playerCloumn[$k][$i];
    $column3=$playerCloumn[$k][$i];
    $column4=$playerCloumn[$k][$i];
    $column5=$playerCloumn[$k][$i];



    $objPHPExcel->getActiveSheet()->setCellValue($column,$v['name_player'].''.$v["birth"]);
    }
    */

    /*
    $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['name_player']);
    $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['birth']);
    $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['id_card']);
    $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['clothes_back_num']);
    $objPHPExcel->getActiveSheet()->setCellValue($playerCloumn[$i][$noodleKey].$beginStart,$participateSqlRows[$i]['clothes_size']);
    */

    /*
    $objPHPExcel->getActiveSheet()->setCellValue('T2',$participateSqlRows[1]['name_player']);
    $objPHPExcel->getActiveSheet()->setCellValue('U2',$participateSqlRows[1]['birth']);
    $objPHPExcel->getActiveSheet()->setCellValue('V2',$participateSqlRows[1]['id_card']);
    $objPHPExcel->getActiveSheet()->setCellValue('W2',$participateSqlRows[1]['mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('X2',$participateSqlRows[1]['clothes_back_num']);
    $objPHPExcel->getActiveSheet()->setCellValue('Y2',$participateSqlRows[1]['clothes_size']);
    */

    // $objPHPExcel->getActiveSheet()->setCellValue('Z'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AA'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AB'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AC'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AD'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AE'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('AF'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AG'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AH'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AI'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AK'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('AL'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AM'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AN'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AO'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AP'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AQ'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('AR'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AS'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AT'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AU'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AV'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AW'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('AX'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AY'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('AZ'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BA'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BB'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BC'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('BD'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BE'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BF'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BG'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BH'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BI'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('BJ'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BK'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BL'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BM'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BN'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BO'.$pK1,$pV1['clothes_size']);
    //
    // $objPHPExcel->getActiveSheet()->setCellValue('BP'.$pK1,$pV1['name_player']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BQ'.$pK1,$pV1['birth']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BR'.$pK1,$pV1['id_card']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BS'.$pK1,$pV1['mobile']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BT'.$pK1,$pV1['clothes_back_num']);
    // $objPHPExcel->getActiveSheet()->setCellValue('BU'.$pK1,$pV1['clothes_size']);

    ++$beginStart;
}
exit;
//#第一個資料表結束
$race_nameSql = "SELECT * from race_name where r_nid=$r_nid";
$race_nameSqlResult = $link->prepare($race_nameSql);
$race_nameSqlResult->execute();
$race_nameSqlRows = $race_nameSqlResult->fetchall();
$race_nameTitle = $race_nameSqlRows[0]['name'];
// exit;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('excel_file.xlsx');//直接儲存在此頁同一個資料夾

header('Pragma: public');
header('Expires: 0');
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
header('Content-Type:application/force-download');
header('Content-Type:application/vnd.ms-execl');
header('Content-Type:application/octet-stream');
header('Content-Type:application/download');
header("Content-Disposition:attachment;filename='race.xlsx'");
header('Content-Transfer-Encoding:binary');
$objWriter->save('php://output');//輸出於瀏覽器
