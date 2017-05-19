<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_prestart']) OR $_GET['id_prestart']=="")	{
  exit();

}




// Rendre votre modèle accessible
include '../models/lstPrestart-model.php';

$oLstPrestart = new PrestartModel($db);

$prestart=$oLstPrestart->getPrestart($_GET['id_prestart']);


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';
$oEprouvette = new EprouvetteModel($db, $_GET['id_ep']);
$eprouvette=$oEprouvette->getEprouvette();








/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Paris');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../lib/PHPExcel/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');


$style_gray = array(
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb'=>'C0C0C0'))
  );
  $style_white = array(
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('rgb'=>'000000'))
    );





    $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Prestart.xlsx");

    $val2Xls = array(
      'D6' => $prestart['machine'],
      'M6' => $prestart ['customer'].'-'.$prestart ['job']. '-'.$prestart ['split'],
      'K8' => $prestart['shunt_cal'],
      'L12' => (($prestart['valid_alignement']==1)?'x':'o'),
      'L11' => (($prestart['valid_extenso']==1)?'x':'o'),
      'O11' => (($prestart['valid_temperature']==1)?'x':'o'),
      'O12' => (($prestart['valid_temperature_line']==1)?'x':'o'),
      'K15' => (($prestart['signal_true']==1)?'x':'o'),
      'M15' => $eprouvette['c_waveform'],
      'O15' => (($prestart['signal_tapered']==1)?'x':'o'),
      'L13' => (($prestart['tune']==1)?"l":"¡"),
      'O13' => (($prestart['tune']==2)?"l":"¡"),

      'G8' => $prestart['cell_load_gamme'],
      'C8' => $prestart['date'],

      'C11' => $prestart['Disp_P'],
      'E11' => $prestart['Disp_i'],
      'F11' => $prestart['Disp_D'],
      'G11' => $prestart['Disp_Conv'],
      'H11' => $prestart['Disp_Sens'],
      'C12' => $prestart['Load_P'],
      'E12' => $prestart['Load_i'],
      'F12' => $prestart['Load_D'],
      'G12' => $prestart['Load_Conv'],
      'H12' => $prestart['Load_Sens'],
      'C13' => $prestart['Strain_P'],
      'E13' => $prestart['Strain_i'],
      'F13' => $prestart['Strain_D'],
      'G13' => $prestart['Strain_Conv'],
      'H13' => $prestart['Strain_Sens']
    );




    /*
    //affichage du checkeur temperature uniquement si temperature
    if ($essai['c_temperature']>=50) {
    $val2Xls['J18'] = $essai['controleur'];
  }
  else {
  $objPHPExcel->getActiveSheet()->getStyle('F15:F17')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('B37')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('K20')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('D35:K38')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('H34:K34')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('H42:I42')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('J18:K18')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('I20')->applyFromArray( $style_gray );
  $objPHPExcel->getActiveSheet()->getStyle('K21')->applyFromArray( $style_gray );

  $objPHPExcel->getActiveSheet()->setCellValue('B38', '');
}

*/

//Pour chaque element du tableau associatif, on update les cellules Excel
foreach ($val2Xls as $key => $value) {
  $objPHPExcel->getActiveSheet()->setCellValue($key, $value);
}



//exit;

$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getActiveSheet()->getProtection()->setPassword("");


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../lib/PHPExcel/files/Prestart-'.$_GET['id_prestart'].'.xlsx');

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Prestart-'.$_GET['id_prestart'].'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>
