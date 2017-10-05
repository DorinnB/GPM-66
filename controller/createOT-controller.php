<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_tbljob']) OR $_GET['id_tbljob']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);

$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include '../models/eprouvettes-model.php';
include '../models/eprouvette-model.php';


$oEprouvettes = new LstEprouvettesModel($db,$_GET['id_tbljob']);
$ep=$oEprouvettes->getAllEprouvettes();

for($k=0;$k < count($ep);$k++)	{
  $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $ep[$k]=$oEprouvette->getTest();

  $oEprouvette->dimension($ep[$k]['type'],$ep[$k]['dim1'],$ep[$k]['dim2'],$ep[$k]['dim3']);



  $ep[$k]['denomination'] = $oEprouvette->denomination($ep[$k]['id_dessin_type'], $ep[$k]['dim1'], $ep[$k]['dim2'], $ep[$k]['dim3']);



  //groupement du nom du job avec ou sans indice
  if (isset($ep[$k]['split']))
  $jobcomplet= $ep[$k]['customer'].'-'.$ep[$k]['job'].'-'.$ep[$k]['split'];
  else
  $jobcomplet= $ep[$k]['customer'].'-'.$ep[$k]['job'];


  //recherche si le split a été fait avec un coil ou un four
  if (isset($ep[$k]['type_chauffage']) AND $ep[$k]['type_chauffage']=="Coil")
  $coil="x";
  if (isset($ep[$k]['type_chauffage']) AND $ep[$k]['type_chauffage']=="Four")
  $four="x";

}



//var_dump($split);
//var_dump($ep[1]);

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








    If ($split['test_type_abbr']=="Loa")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_Loa.xlsx");

      $page=$objPHPExcel->getActiveSheet();


      $val2Xls = array(

        'L2' => $jobcomplet,
        'C6'=> $split['tbljob_frequence'],
        'G4'=> $split['dessin'],
        'G5'=> $split['ref_matiere'],
        'G6'=> $split['waveform'],
        'K4'=> date("Y-m-d"),
        'K5'=> $split['nomCreateur'],
        'K6'=> $split['comCheckeur'],
        'C24'=> $split['c_unite'],
        'C25'=> $split['c_unite'],

        'D42'=> $split['tbljob_instruction'],
        'D47'=> $split['info_jobs_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }

      //titre des lignes PV
      $page->setCellValueByColumnAndRow(1, 22, $split['c_type_1']);
      $page->setCellValueByColumnAndRow(2, 22, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $page->setCellValueByColumnAndRow(1, 23, $split['c_type_2']);
      $page->setCellValueByColumnAndRow(2, 23, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



      $row = 0; // 1-based index
      $col = 3;
      foreach ($ep as $key => $value) {

        $page->setCellValueByColumnAndRow($col, 8, $value['prefixe']);
        $page->setCellValueByColumnAndRow($col, 9, $value['nom_eprouvette']);

        $page->setCellValueByColumnAndRow($col, 10, $value['n_essai']);
        $page->setCellValueByColumnAndRow($col, 11, $value['n_fichier']);
        $page->setCellValueByColumnAndRow($col, 12, $value['operateur']);
        $page->setCellValueByColumnAndRow($col, 13, $value['machine']);
        $page->setCellValueByColumnAndRow($col, 14, $value['date']);
        $page->setCellValueByColumnAndRow($col, 15, $value['c_temperature']);
        $page->setCellValueByColumnAndRow($col, 16, $value['c_frequence']);
        $page->setCellValueByColumnAndRow($col, 17, $value['c_cycle_STL']);
        $page->setCellValueByColumnAndRow($col, 18, $value['c_frequence_STL']);

        if (isset($value['denomination']['denomination_1'])) {
          $page->setCellValueByColumnAndRow($col, 19, $value['dim1']);
          $page->setCellValueByColumnAndRow(0, 19, $value['denomination']['denomination_1']);
        }
        else {
          $page->getRowDimension(19)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_2'])) {
          $page->setCellValueByColumnAndRow($col, 20, $value['dim2']);
          $page->setCellValueByColumnAndRow(0, 20, $value['denomination']['denomination_2']);
        }
        else {
          $page->getRowDimension(20)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_3'])) {
          $page->setCellValueByColumnAndRow($col, 21, $value['dim3']);
          $page->setCellValueByColumnAndRow(0, 21, $value['denomination']['denomination_3']);
        }
        else {
          $page->getRowDimension(21)->setVisible(FALSE);
        }

        $page->setCellValueByColumnAndRow($col, 22, $value['c_type_1_val']);
        $page->setCellValueByColumnAndRow($col, 23, $value['c_type_2_val']);

        $oEprouvette->niveaumaxmin($split['c_type_1'], $split['c_type_2'], $value['c_type_1_val'], $value['c_type_2_val']);
        $page->setCellValueByColumnAndRow($col, 24, $oEprouvette->MAX());
        $page->setCellValueByColumnAndRow($col, 25, $oEprouvette->MIN());


        $page->setCellValueByColumnAndRow($col, 26, $value['Cycle_min']);
        $page->setCellValueByColumnAndRow($col, 27, $value['runout']);

        $page->setCellValueByColumnAndRow($col, 35, $value['cycle_estime']);


        $col++;
      }

      $colImprimable=ceil(count($ep)/10)*10+3;
      //zone d'impression
      $colString = PHPExcel_Cell::stringFromColumnIndex($colImprimable-1);
      $page->getPageSetup()->setPrintArea('A1:'.$colString.'51');




    }
    ElseIf ($split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwl")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_LoS.xlsx");

      $page=$objPHPExcel->getActiveSheet();


      $val2Xls = array(

        'L2' => $jobcomplet,
        'C6'=> $split['tbljob_frequence'],
        'G4'=> $split['dessin'],
        'G5'=> $split['ref_matiere'],
        'G6'=> $split['waveform'],
        'K4'=> date("Y-m-d"),
        'K5'=> $split['nomCreateur'],
        'K6'=> $split['comCheckeur'],
        'C24'=> $split['c_unite'],
        'C25'=> $split['c_unite'],

        'D42'=> $split['tbljob_instruction'],
        'D47'=> $split['info_jobs_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }

      //titre des lignes PV
      $page->setCellValueByColumnAndRow(1, 22, $split['c_type_1']);
      $page->setCellValueByColumnAndRow(2, 22, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $page->setCellValueByColumnAndRow(1, 23, $split['c_type_2']);
      $page->setCellValueByColumnAndRow(2, 23, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



      $row = 0; // 1-based index
      $col = 3;
      foreach ($ep as $key => $value) {

        $page->setCellValueByColumnAndRow($col, 8, $value['prefixe']);
        $page->setCellValueByColumnAndRow($col, 9, $value['nom_eprouvette']);

        $page->setCellValueByColumnAndRow($col, 10, $value['n_essai']);
        $page->setCellValueByColumnAndRow($col, 11, $value['n_fichier']);
        $page->setCellValueByColumnAndRow($col, 12, $value['operateur']);
        $page->setCellValueByColumnAndRow($col, 13, $value['machine']);
        $page->setCellValueByColumnAndRow($col, 14, $value['date']);
        $page->setCellValueByColumnAndRow($col, 15, $value['c_temperature']);
        $page->setCellValueByColumnAndRow($col, 16, $value['c_frequence']);
        $page->setCellValueByColumnAndRow($col, 17, $value['c_cycle_STL']);
        $page->setCellValueByColumnAndRow($col, 18, $value['c_frequence_STL']);

        if (isset($value['denomination']['denomination_1'])) {
          $page->setCellValueByColumnAndRow($col, 19, $value['dim1']);
          $page->setCellValueByColumnAndRow(0, 19, $value['denomination']['denomination_1']);
        }
        else {
          $page->getRowDimension(19)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_2'])) {
          $page->setCellValueByColumnAndRow($col, 20, $value['dim2']);
          $page->setCellValueByColumnAndRow(0, 20, $value['denomination']['denomination_2']);
        }
        else {
          $page->getRowDimension(20)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_3'])) {
          $page->setCellValueByColumnAndRow($col, 21, $value['dim3']);
          $page->setCellValueByColumnAndRow(0, 21, $value['denomination']['denomination_3']);
        }
        else {
          $page->getRowDimension(21)->setVisible(FALSE);
        }

        $page->setCellValueByColumnAndRow($col, 22, $value['c_type_1_val']);
        $page->setCellValueByColumnAndRow($col, 23, $value['c_type_2_val']);

        $oEprouvette->niveaumaxmin($split['c_type_1'], $split['c_type_2'], $value['c_type_1_val'], $value['c_type_2_val']);
        $page->setCellValueByColumnAndRow($col, 24, $oEprouvette->MAX());
        $page->setCellValueByColumnAndRow($col, 25, $oEprouvette->MIN());


        $page->setCellValueByColumnAndRow($col, 26, $value['Cycle_min']);
        $page->setCellValueByColumnAndRow($col, 27, $value['runout']);

        $page->setCellValueByColumnAndRow($col, 35, $value['cycle_estime']);


        $col++;
      }

      $colImprimable=ceil(count($ep)/10)*10+3;
      //zone d'impression
      $colString = PHPExcel_Cell::stringFromColumnIndex($colImprimable-1);
      $page->getPageSetup()->setPrintArea('A1:'.$colString.'51');






    }
    ElseIf ($split['test_type_abbr']=="Str")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_Str.xlsx");

      $page=$objPHPExcel->getActiveSheet();


      $val2Xls = array(

        'L2' => $jobcomplet,
        'C6'=> $split['tbljob_frequence'],
        'G4'=> $split['dessin'],
        'G5'=> $split['ref_matiere'],
        'G6'=> $split['waveform'],
        'K4'=> date("Y-m-d"),
        'K5'=> $split['nomCreateur'],
        'K6'=> $split['comCheckeur'],
        'C24'=> $split['c_unite'],
        'C25'=> $split['c_unite'],

        'D42'=> $split['tbljob_instruction'],
        'D47'=> $split['info_jobs_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }

      //titre des lignes PV
      $page->setCellValueByColumnAndRow(1, 22, $split['c_type_1']);
      $page->setCellValueByColumnAndRow(2, 22, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $page->setCellValueByColumnAndRow(1, 23, $split['c_type_2']);
      $page->setCellValueByColumnAndRow(2, 23, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



      $row = 0; // 1-based index
      $col = 3;
      foreach ($ep as $key => $value) {
        //copy des styles des colonnes
        $page->setCellValueByColumnAndRow($col, 8, $value['prefixe']);
        $page->setCellValueByColumnAndRow($col, 9, $value['nom_eprouvette']);

        $page->setCellValueByColumnAndRow($col, 10, $value['n_essai']);
        $page->setCellValueByColumnAndRow($col, 11, $value['n_fichier']);
        $page->setCellValueByColumnAndRow($col, 12, $value['operateur']);
        $page->setCellValueByColumnAndRow($col, 13, $value['machine']);
        $page->setCellValueByColumnAndRow($col, 14, $value['date']);
        $page->setCellValueByColumnAndRow($col, 15, $value['c_temperature']);
        $page->setCellValueByColumnAndRow($col, 16, $value['c_frequence']);
        $page->setCellValueByColumnAndRow($col, 17, $value['c_cycle_STL']);
        $page->setCellValueByColumnAndRow($col, 18, $value['c_frequence_STL']);

        if (isset($value['denomination']['denomination_1'])) {
          $page->setCellValueByColumnAndRow($col, 19, $value['dim1']);
          $page->setCellValueByColumnAndRow(0, 19, $value['denomination']['denomination_1']);
        }
        else {
          $page->getRowDimension(19)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_2'])) {
          $page->setCellValueByColumnAndRow($col, 20, $value['dim2']);
          $page->setCellValueByColumnAndRow(0, 20, $value['denomination']['denomination_2']);
        }
        else {
          $page->getRowDimension(20)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_3'])) {
          $page->setCellValueByColumnAndRow($col, 21, $value['dim3']);
          $page->setCellValueByColumnAndRow(0, 21, $value['denomination']['denomination_3']);
        }
        else {
          $page->getRowDimension(21)->setVisible(FALSE);
        }

        $page->setCellValueByColumnAndRow($col, 22, $value['c_type_1_val']);
        $page->setCellValueByColumnAndRow($col, 23, $value['c_type_2_val']);

        $oEprouvette->niveaumaxmin($split['c_type_1'], $split['c_type_2'], $value['c_type_1_val'], $value['c_type_2_val']);
        $page->setCellValueByColumnAndRow($col, 24, $oEprouvette->MAX());
        $page->setCellValueByColumnAndRow($col, 25, $oEprouvette->MIN());


        $page->setCellValueByColumnAndRow($col, 26, $value['Cycle_min']);
        $page->setCellValueByColumnAndRow($col, 27, $value['runout']);

        $page->setCellValueByColumnAndRow($col, 35, $value['cycle_estime']);


        $col++;
      }

      $colImprimable=ceil(count($ep)/10)*10+3;
      //zone d'impression
      $colString = PHPExcel_Cell::stringFromColumnIndex($colImprimable-1);
      $page->getPageSetup()->setPrintArea('A1:'.$colString.'51');





    }
    ElseIf ($split['test_type_abbr']==".Res")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_.Res.xlsx");

      $page=$objPHPExcel->getSheetByName('Res Stress Req');

      $val2Xls = array(

        'C1'=> $split['job'],
        'D3' => $jobcomplet,
        'D4'=> $split['compagnie'],
        'D5'=> $split['po_number'],
        'D6'=> $split['DyT_expected'],
        'A9'=> $split['tbljob_commentaire'],
        'G3'=> $split['matiere'],
        'G4'=> $split['dessin'],
        'A12'=> $split['tbljob_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }


      $row = 16; // 1-based index
      $col = 0;

      foreach ($ep as $key => $value) {
        //copy des styles des colonnes
        for ($col = 1; $col <= 7; $col++) {
          $style = $page->getStyleByColumnAndRow($col, $row);
          $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
          $page->duplicateStyle($style, $dstCell);
        }

        $page->setCellValueByColumnAndRow(0, $row, (isset($value['prefixe']))?$identification= $value['prefixe'].'-'.$value['nom_eprouvette']:$identification= $value['nom_eprouvette']);
        $page->setCellValueByColumnAndRow(3, $row, $value['c_commentaire']);

        $row++;
      }

      //zone d'impression
      $colString = PHPExcel_Cell::stringFromColumnIndex($col-1);
      //  $page->getPageSetup()->setPrintArea('A1:'.$colString.($max_row_q+50));


    }
    ElseIf ($split['test_type_abbr']==".Ma")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_.Ma.xlsx");

      $page=$objPHPExcel->getSheetByName('MRI Req');

      $val2Xls = array(

        'C1'=> $split['job'],
        'D3' => $jobcomplet,
        'D4'=> $split['compagnie'],
        'D5'=> $split['po_number'],
        'D6'=> $split['DyT_expected'],
        'A9'=> $split['tbljob_commentaire'],
        'H4'=> $split['ref_matiere'],
        'A14'=> $split['tbljob_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }


      $row = 18; // 1-based index
      $col = 0;

      foreach ($ep as $key => $value) {
        //copy des styles des colonnes
        for ($col = 0; $col <= 9; $col++) {
          $style = $page->getStyleByColumnAndRow($col, 18);
          $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
          $page->duplicateStyle($style, $dstCell);
          $page->mergeCells("A".($row).":C".($row));
          $page->mergeCells("F".($row).":H".($row));
        }

        $page->setCellValueByColumnAndRow(0, $row, (isset($value['prefixe']))?$identification= $value['prefixe'].'-'.$value['nom_eprouvette']:$identification= $value['nom_eprouvette']);
        $page->setCellValueByColumnAndRow(3, $row, $value['dessin']);
        $page->setCellValueByColumnAndRow(4, $row, $split['specification']);
        $page->setCellValueByColumnAndRow(5, $row, $value['c_commentaire']);

        $row++;
      }

      //zone d'impression
      //$colString = PHPExcel_Cell::stringFromColumnIndex($col-1);
      $page->getPageSetup()->setPrintArea('A1:I'.($row-1));


    }
    ElseIf ($split['test_type_abbr']=="IQC")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_IQC.xlsx");

      $page=$objPHPExcel->getSheetByName('Res Stress Req');

      // Rendre votre modèle accessible
      include '../models/annexe_IQC-model.php';

      $oEp = new AnnexeIQCModel($db);
      $epIQC=$oEp->getAllIQC($_GET['id_tbljob']);

      $IQC=$oEp->getGlobalIQC($split['id_dessin']);

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

        $oldData->setCellValueByColumnAndRow(1, $row, '*'.$value['prefixe']);
        $oldData->setCellValueByColumnAndRow(2, $row, '*'.$value['nom_eprouvette']);


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

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $data->setCellValue($key, $value);
      }

    }

    else {
      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT INCONNU.xlsx");
    }




    //exit;


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$objWriter->setIncludeCharts(TRUE);
    $objWriter->save('../lib/PHPExcel/files/OT-'.$_GET['id_tbljob'].'.xlsx');

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="OT-'.$_GET['id_tbljob'].'.xlsx"');
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

    ?>
