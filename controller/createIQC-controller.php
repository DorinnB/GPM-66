<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_job']) OR $_GET['id_job']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/split-model.php';
$oSplit = new LstSplitModel($db,$_GET['id_job']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include '../models/eprouvettes-model.php';
$oEprouvettes = new LstEprouvettesModel($db,$_GET['id_job']);


//Appel du model
$ep=$oEprouvettes->getAllEprouvettes();


// Rendre votre modèle accessible
include '../models/annexe_IQC-model.php';

$oEp = new AnnexeIQCModel($db);
$epIQC=$oEp->getAllIQC($_GET['id_job']);

$IQC=$oEp->getGlobalIQC($split['id_dessin']);


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



$objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Dimensionnel.xlsx");

$val2Xls = array(

  'A5' => $split['id_tbljob'],

  'C5' => $split ['customer'].'-'.$split ['job']. '-'.$split ['split'],
  'C6' => $split['dessin'],
  'C7' => $split['ref_matiere'].' ('.$split['matiere'].')',

  'N5' =>  $split['comments'],
  'N6' => date("Y-m-d"),
  'N7' => $split['specification'],

  'B10' => $split['tbljob_instruction'],
  'F10' => $split['tbljob_commentaire'],
  'N10' => $split['tbljob_commentaire_qualite'],

  'R1' => $IQC['nominal_1'],
  'R2' => $IQC['tolerance_plus_1'],
  'R3' => $IQC['tolerance_moins_1'],
  'R4' => $IQC['nominal_2'],
  'R5' => $IQC['tolerance_plus_2'],
  'R6' => $IQC['tolerance_moins_2'],
  'R7' => $IQC['nominal_3'],
  'R8' => $IQC['tolerance_plus_3'],
  'R9' => $IQC['tolerance_moins_3']
);

$row = 15; // 1-based index
$col = 0;

$oldData=$objPHPExcel->getSheetByName('OldData');
$data=$objPHPExcel->getSheetByName('INSPECTION QUALITE DIM INSTRUM');

foreach ($epIQC as $key => $value) {
  $oldData->setCellValueByColumnAndRow(0, $row, $value['id_eprouvette']);
//  $data->setCellValueByColumnAndRow(0, $row, $value['id_eprouvette']);
  $oldData->setCellValueByColumnAndRow(1, $row, $value['prefixe']);
  //$data->setCellValueByColumnAndRow(1, $row, $value['prefixe']);
  $oldData->setCellValueByColumnAndRow(2, $row, $value['nom_eprouvette']);
  //$data->setCellValueByColumnAndRow(2, $row, $value['nom_eprouvette']);
  $oldData->setCellValueByColumnAndRow(3, $row, $value['dim1']);
  $oldData->setCellValueByColumnAndRow(4, $row, $value['dim2']);
  $oldData->setCellValueByColumnAndRow(5, $row, $value['dim3']);

  $oldData->setCellValueByColumnAndRow(7, $row, $value['marquage']);
  $oldData->setCellValueByColumnAndRow(8, $row, $value['surface']);
  $oldData->setCellValueByColumnAndRow(9, $row, $value['grenaillage']);
  $oldData->setCellValueByColumnAndRow(10, $row, $value['revetement']);
  $oldData->setCellValueByColumnAndRow(11, $row, $value['protection']);
  $oldData->setCellValueByColumnAndRow(12, $row, $value['autre']);

  $oldData->setCellValueByColumnAndRow(13, $row, $ep[$key]['d_commentaire']);
  $oldData->setCellValueByColumnAndRow(14, $row, $value['date_IQC']);

  $oldData->setCellValueByColumnAndRow(15, $row, $value['technicien']);

  $row++;
}




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
  $data->setCellValue($key, $value);
}



//exit;


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../lib/PHPExcel/files/Dimensionnel-'.$_GET['id_job'].'.xlsx');

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Dimensionnel-'.$_GET['id_job'].'.xlsx"');
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
