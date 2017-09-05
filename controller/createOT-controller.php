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
  //$denomination=$oEprouvette->dimDenomination();
  //$nb_dim=count($denomination);


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

        'D42'=> $split['tbljob_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }

      //titre des lignes PV
      $page->setCellValueByColumnAndRow(2, 22, $split['c_type_1']);
      $page->setCellValueByColumnAndRow(1, 22, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $page->setCellValueByColumnAndRow(2, 23, $split['c_type_2']);
      $page->setCellValueByColumnAndRow(1, 23, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



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
          $page->setCellValueByColumnAndRow(1, 19, $value['denomination']['denomination_1']);
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

      $colImprimable=(ceil(count($ep)/10)*13-3);
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

        'D42'=> $split['tbljob_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }

      //titre des lignes PV
      $page->setCellValueByColumnAndRow(2, 22, $split['c_type_1']);
      $page->setCellValueByColumnAndRow(1, 22, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $page->setCellValueByColumnAndRow(2, 23, $split['c_type_2']);
      $page->setCellValueByColumnAndRow(1, 23, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



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
          $page->setCellValueByColumnAndRow(1, 19, $value['denomination']['denomination_1']);
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

      $colImprimable=(ceil(count($ep)/10)*13-3);
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

        'D42'=> $split['tbljob_instruction']
      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $page->setCellValue($key, $value);
      }

      //titre des lignes PV
      $page->setCellValueByColumnAndRow(2, 22, $split['c_type_1']);
      $page->setCellValueByColumnAndRow(1, 22, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $page->setCellValueByColumnAndRow(2, 23, $split['c_type_2']);
      $page->setCellValueByColumnAndRow(1, 23, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



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
          $page->setCellValueByColumnAndRow(1, 19, $value['denomination']['denomination_1']);
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

      $colImprimable=(ceil(count($ep)/10)*13-3);
      //zone d'impression
      $colString = PHPExcel_Cell::stringFromColumnIndex($colImprimable-1);
      $page->getPageSetup()->setPrintArea('A1:'.$colString.'51');





    }
    ElseIf ($split['test_type_abbr']=="PssssssS")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT_PS.xlsx");


      $val2Xls = array(
        'B7' => $identification,
        'B8'=> $ep[$k]['dessin'],
        'B9' => $ep[$k]['ref_matiere'],
        'B14' => $ep[$k]['machine'],
        'B15' => '40001',
        'B16' => $ep[$k]['enregistreur'],
        'F14' => $compresseur,
        'F17' => $ind_temp,
        'B17' => $ep[$k]['extensometre'],
        'F15' => $coil,
        'F16' => $four,
        'B24' => $ep[$k]['cell_load_gamme'],
        'B23' => $ep[$k]['cell_displacement_gamme'],
        'B25' => '5',
        'B28' => '3',
        'B29' => '',
        'B30' => $oEprouvette->MAX()+0.15,
        'D28' => '-3',
        'D29' => '',
        'D30' => $oEprouvette->MIN()-0.15,
        'I7' => $jobcomplet,
        'I8' => $ep[$k]['n_essai'],
        'I9' => $ep[$k]['n_fichier'],
        'I10' => $ep[$k]['date'],
        'I11' => $ep[$k]['operateur'],
        'I12' => $ep[$k]['controleur'],
        'J16' => $ep[$k]['operateur'],
        'K18' => $ep[$k]['c_temperature'],
        'K21' => $oEprouvette->R(),
        'K22' => $ep[$k]['c_frequence'],
        'I21' => $oEprouvette->A(),
        'I22' => $true.$ep[$k]['c_waveform'].$tapered,
        'J24' => $ep[$k]['dim1'],
        'I24' => $ep[$k]['dim2'],
        'I23' => $ep[$k]['dim3'],
        'J25' => $area,
        'J26' => $ep[$k]['Lo'],
        'J29' => $oEprouvette->MAX()-$oEprouvette->MIN(),
        'J30' => $oEprouvette->MAX(),
        'J31' => $oEprouvette->MIN(),
        'B45' => $STL,
        'B46' => $F_STL,
        'J56' => $ep[$k]['Cycle_min'],
        'J59' => $runout
      );

      //affichage du checkeur temperature uniquement si temperature
      if ($ep[$k]['c_temperature']>=50) {
        $val2Xls['J17'] = $ep[$k]['controleur'];
      }
      else {
        $objPHPExcel->getActiveSheet()->getStyle('F15:F17')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('B37')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('K20')->applyFromArray( $style_gray );

        $objPHPExcel->getActiveSheet()->getStyle('D35:K38')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('H34:K34')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('D51:F51')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('H52:I52')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('J51:K51')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('J47:J50')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('G47:H47')->applyFromArray( $style_gray );



        $objPHPExcel->getActiveSheet()->getStyle('K24:K26')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('J27')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('J17:K17')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('I18')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('K29:K31')->applyFromArray( $style_gray );
        $objPHPExcel->getActiveSheet()->getStyle('H42:I42')->applyFromArray( $style_gray );

        $objPHPExcel->getActiveSheet()->setCellValue('B38', '');
      }



      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $objPHPExcel->getActiveSheet()->setCellValue($key, $value);
        //->getStyle($key)->applyFromArray( $style_white )
      }



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
        'H4'=> $split['matiere'],
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

    else {
      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/OT INCONNU.xlsx");
    }




    //exit;


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$objWriter->setIncludeCharts(TRUE);
    $objWriter->save('../lib/PHPExcel/files/OT-'.$_GET['id_tbljob'].'.xlsx');

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Report-'.$_GET['id_tbljob'].'.xlsx"');
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
