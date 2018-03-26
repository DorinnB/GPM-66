<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include '../models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include '../models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();


// Rendre votre modèle accessible
include '../models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);



//adresse
$i=0;
if (isset($split['entreprise'])) {
  $adresse[$i]='entreprise';
  $i++;
}
if (isset($split['billing_rue1'])) {
  $adresse[$i]='billing_rue1';
  $i++;
}
if (isset($split['billing_rue2'])) {
  $adresse[$i]='billing_rue2';
  $i++;
}
if (isset($split['billing_ville'])) {
  $adresse[$i]='billing_ville';
  $i++;
}
if (isset($split['billing_pays'])) {
  $adresse[$i]='billing_pays';
  $i++;
}



$date=date("Y-m-d H-i-s");






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
$objReader->setIncludeCharts(TRUE);

//nom du fichier excel d'UBR
$objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Invoice.xlsx");

$page=$objPHPExcel->getSheetByName('Invoice');




$row = 14; // 1-based index
$nCode=1; //code "Others"


$val2Xls = array(

  'D7' => '',
  'D8' =>  $split['po_number'],
  'D9' => $split['VAT'],
  'D10' => $split['customer'].'-'.$split['job'],
  'G9'=> date("Y-m-d"),

  'F1'=> (isset($adresse[0])?$split[$adresse[0]]:''),
  'F2'=> (isset($adresse[1])?$split[$adresse[1]]:''),
  'F3'=> (isset($adresse[2])?$split[$adresse[2]]:''),
  'F4'=> (isset($adresse[3])?$split[$adresse[3]]:''),
  'F5'=> (isset($adresse[4])?$split[$adresse[4]]:'')

);

//Pour chaque element du tableau associatif, on update les cellules Excel
foreach ($val2Xls as $key => $value) {
  $page->setCellValue($key, $value);
}


//style monetaire
$style = $page->getStyleByColumnAndRow(8, $split['invoice_currency']+1);
$dstCell = PHPExcel_Cell::stringFromColumnIndex(5) . (string)(14);
$page->duplicateStyle($style, $dstCell);
$dstCell = PHPExcel_Cell::stringFromColumnIndex(6) . (string)(14);
$page->duplicateStyle($style, $dstCell);



//pour chaque split
foreach ($splits as $key => $value) {
  $nbLines=0; //comptage s'il y a des invoicelines dans ce split

  //pour chaque invoiceLine du split
  foreach ($oInvoices->getInvoiceListSplit($value['id_tbljob']) as $invoicelines) {
    //s'ily a une une quantité
    if ($invoicelines['qteUser']>0 OR $invoicelines['qteGPM']>0) {
      //on copy le style
      for ($colStyle = 0; $colStyle <= 6; $colStyle++) {
        $style = $page->getStyleByColumnAndRow($colStyle, 14);
        $dstCell = PHPExcel_Cell::stringFromColumnIndex($colStyle) . (string)($row);
        $page->duplicateStyle($style, $dstCell);
      }

      if ($invoicelines['prodCode']=="") {
        $code="O-".$nCode;
        $nCode++;
      }
      else {
        $code=$invoicelines['prodCode']."-".$invoicelines['OpnCode'];
      }

      $page->setCellValueByColumnAndRow(0, $row, $code);
      $page->setCellValueByColumnAndRow(1, $row, $invoicelines['pricingList']);
      $page->setCellValueByColumnAndRow(4, $row, ($invoicelines['qteUser']=="")?$invoicelines['qteGPM']:$invoicelines['qteUser']);
      $page->setCellValueByColumnAndRow(5, $row, $invoicelines['priceUnit']);
      $page->setCellValueByColumnAndRow(6, $row, (($invoicelines['qteUser']=="")?$invoicelines['qteGPM']:$invoicelines['qteUser'])*$invoicelines['priceUnit']);

      $row++;
      $nbLines++;
    }

  }


  if ($nbLines>0) { //1 ligne de separation avec le split suivant
    $row++;
  }

}

//pour le job
//pour chaque invoiceLine du split
foreach ($oInvoices->getInvoiceListJob($_GET['id_tbljob']) as $invoicelines) {
  //s'ily a une une quantité
  if ($invoicelines['qteUser']>0) {
    //on copy le style
    for ($colStyle = 0; $colStyle <= 6; $colStyle++) {
      $style = $page->getStyleByColumnAndRow($colStyle, 14);
      $dstCell = PHPExcel_Cell::stringFromColumnIndex($colStyle) . (string)($row);
      $page->duplicateStyle($style, $dstCell);
    }

    if ($invoicelines['prodCode']=="") {
      $code="O-".$nCode;
      $nCode++;
    }
    else {
      $code=$invoicelines['prodCode']."-".$invoicelines['OpnCode'];
    }

    $page->setCellValueByColumnAndRow(0, $row, $code);
    $page->setCellValueByColumnAndRow(1, $row, $invoicelines['pricingList']);
    $page->setCellValueByColumnAndRow(4, $row, $invoicelines['qteUser']);
    $page->setCellValueByColumnAndRow(5, $row, $invoicelines['priceUnit']);
    $page->setCellValueByColumnAndRow(6, $row, $invoicelines['qteUser']*$invoicelines['priceUnit']);

    $row++;
  }
}





$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->setIncludeCharts(TRUE);
$objWriter->save('../lib/PHPExcel/files/Invoice-'.$split['job'].'-'.$date.'.xlsx');


//type de sortie en fonction d'un affichage browser ou copy ubr
if (isset($_GET['UBR'])) {
  //Copy du fichier vers //SRVDC/DONNEES/ADMINISTRATION/UBR/
  $srcfile='../lib/PHPExcel/files/Invoice-'.$split['job'].'-'.$date.'.xlsx';
  $dstfile = '//SRVDC/DONNEES/ADMINISTRATION/UBR/Invoice-'.$split['job'].'-'.$date.'.xlsx';
  copy($srcfile, $dstfile);
  exit;
}
else {
  // Redirect output to a client’s web browser (Excel2007)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Invoice-'.$split['job'].'-'.$date.'.xlsx"');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');

  // If you're serving to IE over SSL, then the following may be needed
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
  header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header ('Pragma: public'); // HTTP/1.0

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  //$objWriter->setIncludeCharts(TRUE);
  $objWriter->save('php://output');
  exit;
}
