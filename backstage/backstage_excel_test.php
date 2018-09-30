<?php
include_once(dirname(dirname(__FILE__)).'/link.php');
include_once(dirname(dirname(__FILE__)).'/function.php');
include_once(dirname(__FILE__).'/PHPExcel/Classes/PHPExcel.php');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="dzg_card_info.xlsx"');
header('Cache-Control: max-age=0');

// $this->load->library('PHPExcel'); //加载类库,其他框架可以使用require_one
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); //设置第一个工作表为活动工作表
$objPHPExcel->getActiveSheet()->setTitle('card_info'); //设置工作表名称
//为单元格赋值
//方法①:直接设置单元格的值
/* $objPHPExcel->getActiveSheet()->setCellValue('A1', 'PHPExcel');
  $objPHPExcel->getActiveSheet()->setCellValue('A2', 12345.6789);
  $objPHPExcel->getActiveSheet()->setCellValue('A3', TRUE); */

//方法②:二维数组
$arrHeader = array(['id', '名字', '技能', '创建时间']);
$arrAllCardInfo = $this->admin_model->getAllCardInfo(); //二维数组
$arrExcelInfo = array_merge($arrHeader, $arrAllCardInfo);
$arrExcelInfo = eval('return ' . iconv('gbk', 'utf-8', var_export($arrExcelInfo, true)) . ';'); //将数组转换成utf-8
$objPHPExcel->getActiveSheet()->fromArray(
        $arrExcelInfo, // 赋值的数组
        NULL, // 忽略的值,不会在excel中显示
        'A1' // 赋值的起始位置
);

//创建第二个工作表
$msgWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'card_message'); //创建一个工作表
$objPHPExcel->addSheet($msgWorkSheet); //插入工作表
$objPHPExcel->setActiveSheetIndex(1); //切换到新创建的工作表
$arrHeader = array(['id', 'uid', '描述']);
$arrBody = $this->admin_model->getAllCardMsg();
$arrExcelInfo = array_merge($arrHeader, $arrBody);
$arrExcelInfo = eval('return ' . iconv('gbk', 'utf-8', var_export($arrExcelInfo, true)) . ';'); //将数组转换成utf-8
$objPHPExcel->getActiveSheet()->fromArray(
        $arrExcelInfo, // 赋值的数组
        NULL, // 忽略的值,不会在excel中显示
        'A1' // 赋值的起始位置
);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save('php://output');

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
?>
