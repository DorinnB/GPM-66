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
  $oEprouvette->niveaumaxmin($ep[$k]['c_1_type'], $ep[$k]['c_2_type'], $ep[$k]['c_type_1_val'], $ep[$k]['c_type_2_val']);
$ep[$k]['max']=$oEprouvette->MAX();
$ep[$k]['min']=$oEprouvette->MIN();

  $ep[$k]['denomination'] = $oEprouvette->denomination($ep[$k]['id_dessin_type'], $ep[$k]['dim1'], $ep[$k]['dim2'], $ep[$k]['dim3']);






  if (isset($ep[$k]['split']))		//groupement du nom du job avec ou sans indice
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
    'color' => array('rgb'=>'C0C0C0')
  )
);

$style_running = array(
  'font'  => array(
    'italic'  => true,
    'color' => array('rgb' => '0000CC'),
    'size'  => 8
  )
);
$style_checked = array(
  'font'  => array(
    'italic'  => false,
    'color' => array('rgb' => '000000')
  )
);
$style_unchecked = array(
  'font'  => array(
    'italic'  => true,
    'color' => array('rgb' => '888888'),
    'size'  => 8
  )
);



If ($split['test_type_abbr']=="Loa")	{

  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Report Loa.xlsx");


    $enTete=$objPHPExcel->getSheetByName('En-tête');
    $pvEssais=$objPHPExcel->getSheetByName('PV');
    $courbes=$objPHPExcel->getSheetByName('Courbes');

    $val2Xls = array(

      'J5' => $jobcomplet,
      'J9'=> $split['po_number'],
      'C5'=> $split['genre'].' '.$split['lastname'].' '.$split['surname'],
      'C6'=> $split['adresse'],

      'E16'=> $split['ref_matiere'],

      'E17'=> $split['info_jobs_instruction'],

      'E23'=> $split['specification'],
      'E26'=> $split['dessin'],

      'E34'=> $split['temperature'].' °C',

      'E38'=> $split['c_frequence'].' Hz',

      'E41'=> $split['waveform']

    );

    //Pour chaque element du tableau associatif, on update les cellules Excel
    foreach ($val2Xls as $key => $value) {
      $enTete->setCellValue($key, $value);
    }

    //titre des lignes PV
    $pvEssais->setCellValueByColumnAndRow(0, 14, $split['c_type_1']);
    $pvEssais->setCellValueByColumnAndRow(2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
    $pvEssais->setCellValueByColumnAndRow(0, 15, $split['c_type_2']);
    $pvEssais->setCellValueByColumnAndRow(2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

    $pvEssais->setCellValueByColumnAndRow(2, 27, $split['c_unite']);
    $pvEssais->setCellValueByColumnAndRow(2, 28, $split['c_unite']);
    $pvEssais->setCellValueByColumnAndRow(2, 29, $split['c_unite']);
    $pvEssais->setCellValueByColumnAndRow(2, 30, $split['c_unite']);


    $row = 0; // 1-based index
    $col = 3;

    $row_q=0;
    $col_q=0;
    $nb_q=0;
    $max_row_q=0;
    $nbPage=15;
    $maxheight=0;

    foreach ($ep as $key => $value) {
      //copy des styles des colonnes
      for ($row = 5; $row <= 48; $row++) {
        $style = $pvEssais->getStyleByColumnAndRow(3, $row);
        $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
        $pvEssais->duplicateStyle($style, $dstCell);
      }

      $pvEssais->setCellValueByColumnAndRow($col, 5, $value['prefixe']);
      $pvEssais->setCellValueByColumnAndRow($col, 6, $value['nom_eprouvette']);

      $pvEssais->setCellValueByColumnAndRow($col, 7, $value['n_essai']);
      $pvEssais->setCellValueByColumnAndRow($col, 8, $value['n_fichier']);
      $pvEssais->setCellValueByColumnAndRow($col, 9, $value['machine']);
      $pvEssais->setCellValueByColumnAndRow($col, 10, $value['date']);
      $pvEssais->setCellValueByColumnAndRow($col, 11, $value['c_temperature']);
      $pvEssais->setCellValueByColumnAndRow($col, 12, $value['c_frequence']);
      $pvEssais->setCellValueByColumnAndRow($col, 13, $value['c_frequence_STL']);
      $pvEssais->setCellValueByColumnAndRow($col, 14, $value['c_type_1_val']);
      $pvEssais->setCellValueByColumnAndRow($col, 15, $value['c_type_2_val']);
      $pvEssais->setCellValueByColumnAndRow($col, 16, $value['c_waveform']);

      if (isset($value['denomination']['denomination_1'])) {
        $pvEssais->setCellValueByColumnAndRow($col, 17, $value['dim1']);
        $pvEssais->setCellValueByColumnAndRow(1, 17, $value['denomination']['denomination_1']);
        if ($value['dilatation']>1) {
          $pvEssais->setCellValueByColumnAndRow($col, 21, $value['dim1']*$value['dilatation']);
          $pvEssais->setCellValueByColumnAndRow(1, 21, $value['denomination']['denomination_1']);
        }
        else {
          $pvEssais->getRowDimension(21)->setVisible(FALSE);
        }
      }
      else {
        $pvEssais->getRowDimension(17)->setVisible(FALSE);
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
      if (isset($value['denomination']['denomination_2'])) {
        $pvEssais->setCellValueByColumnAndRow($col, 18, $value['dim2']);
        $pvEssais->setCellValueByColumnAndRow(1, 18, $value['denomination']['denomination_2']);
        if ($value['dilatation']>1) {
          $pvEssais->setCellValueByColumnAndRow($col, 22, $value['dim2']*$value['dilatation']);
          $pvEssais->setCellValueByColumnAndRow(1, 22, $value['denomination']['denomination_2']);
        }
        else {
          $pvEssais->getRowDimension(22)->setVisible(FALSE);
        }

      }
      else {
        $pvEssais->getRowDimension(18)->setVisible(FALSE);
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }
      if (isset($value['denomination']['denomination_3'])) {
        $pvEssais->setCellValueByColumnAndRow($col, 19, $value['dim3']);
        $pvEssais->setCellValueByColumnAndRow(1, 19, $value['denomination']['denomination_3']);
        if ($value['dilatation']>1) {
          $pvEssais->setCellValueByColumnAndRow($col, 23, $value['dim3']*$value['dilatation']);
          $pvEssais->setCellValueByColumnAndRow(1, 23, $value['denomination']['denomination_3']);
        }
        else {
          $pvEssais->getRowDimension(23)->setVisible(FALSE);
        }
      }
      else {
        $pvEssais->getRowDimension(19)->setVisible(FALSE);
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }


      $pvEssais->setCellValueByColumnAndRow($col, 27, $value['max']);
      $pvEssais->setCellValueByColumnAndRow($col, 28, ($value['max']+$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow($col, 29, ($value['max']-$value['min'])/2);
      $pvEssais->setCellValueByColumnAndRow($col, 30, $value['min']);


      $pvEssais->setCellValueByColumnAndRow($col, 44, $value['Cycle_final']);
      $pvEssais->setCellValueByColumnAndRow($col, 45, $value['Rupture']);
      $pvEssais->setCellValueByColumnAndRow($col, 46, $value['Fracture']);
      $pvEssais->setCellValueByColumnAndRow($col, 47, $value['temps_essais']);

      if ($value['d_checked']<=0 AND $value['n_fichier']>0) {
        $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'47')->applyFromArray( $style_unchecked );
        $pvEssais->setCellValueByColumnAndRow($col, 4, "Unchecked");
      }
      else {
        $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'47')->applyFromArray( $style_checked );

      }
      if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
        $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'44:'.PHPExcel_Cell::stringFromColumnIndex($col).'44')->applyFromArray( $style_running );
        $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'4')->applyFromArray( $style_running );
        $pvEssais->setCellValueByColumnAndRow($col, 4, "RUNNING");
      }



      if ($value['q_commentaire']!="") {

        $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
        $nb_q+=1; //on incremente le nombre de commentaire

        //recup du commentaire precedent
        $prev_value = $pvEssais->getCellByColumnAndRow($col_q, 50)->getValue();

        $pvEssais->setCellValueByColumnAndRow($col, 48, '('.($nb_q).')');
        $pvEssais->setCellValueByColumnAndRow($col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
        $pvEssais->mergeCells(PHPExcel_Cell::stringFromColumnIndex($col_q).'50:'.PHPExcel_Cell::stringFromColumnIndex($col_q+($nbPage-1)).'50');
        $pvEssais->getRowDimension(50)->setRowHeight(-1);




        //calcul de la hauteur max de la cellule de commentaire Qualité
        $rc = 0;
        $width=80;  //valeur empirique lié à la largeur des colonnes
        $line = explode("\n", $prev_value);
        foreach($line as $source) {
          $rc += intval((strlen($source) / $width) +1);
        }
        $maxheight=max($maxheight,$rc);
        $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


      }

      $col++;
    }

    //zone d'impression
    //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
    $colString = PHPExcel_Cell::stringFromColumnIndex((ceil(($col-3)/$nbPage)*$nbPage+3)-1);
    $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

    //separation impression par $nbPage eprouvettes
    for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
      $pvEssais->setBreak( PHPExcel_Cell::stringFromColumnIndex($c).(1) , PHPExcel_Worksheet::BREAK_COLUMN );
    }






}
ElseIf ($split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwlsssss")	{

  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT LoS.xlsx");


      $enTete=$objPHPExcel->getSheetByName('En-tête');
      $pvEssais=$objPHPExcel->getSheetByName('PV');
      $courbes=$objPHPExcel->getSheetByName('Courbes');

      $val2Xls = array(

        'J5' => $jobcomplet,
        'J9'=> $split['po_number'],
        'C5'=> $split['genre'].' '.$split['lastname'].' '.$split['surname'],
        'C6'=> $split['adresse'],

        'E16'=> $split['ref_matiere'],

        'E17'=> $split['info_jobs_instruction'],

        'E23'=> $split['specification'],
        'E26'=> $split['dessin'],

        'E34'=> $split['temperature'].' °C',

        'E38'=> $split['c_frequence'].' Hz',

        'E41'=> $split['waveform']

      );

      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $enTete->setCellValue($key, $value);
      }

      //titre des lignes PV
      $pvEssais->setCellValueByColumnAndRow(0, 14, $split['c_type_1']);
      $pvEssais->setCellValueByColumnAndRow(2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
      $pvEssais->setCellValueByColumnAndRow(0, 15, $split['c_type_2']);
      $pvEssais->setCellValueByColumnAndRow(2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");

      $pvEssais->setCellValueByColumnAndRow(2, 27, $split['c_unite']);
      $pvEssais->setCellValueByColumnAndRow(2, 28, $split['c_unite']);
      $pvEssais->setCellValueByColumnAndRow(2, 29, $split['c_unite']);
      $pvEssais->setCellValueByColumnAndRow(2, 30, $split['c_unite']);


      $row = 0; // 1-based index
      $col = 3;

      $row_q=0;
      $col_q=0;
      $nb_q=0;
      $max_row_q=0;
      $nbPage=15;
      $maxheight=0;

      foreach ($ep as $key => $value) {
        //copy des styles des colonnes
        for ($row = 5; $row <= 48; $row++) {
          $style = $pvEssais->getStyleByColumnAndRow(3, $row);
          $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
          $pvEssais->duplicateStyle($style, $dstCell);
        }

        $pvEssais->setCellValueByColumnAndRow($col, 5, $value['prefixe']);
        $pvEssais->setCellValueByColumnAndRow($col, 6, $value['nom_eprouvette']);

        $pvEssais->setCellValueByColumnAndRow($col, 7, $value['n_essai']);
        $pvEssais->setCellValueByColumnAndRow($col, 8, $value['n_fichier']);
        $pvEssais->setCellValueByColumnAndRow($col, 9, $value['machine']);
        $pvEssais->setCellValueByColumnAndRow($col, 10, $value['date']);
        $pvEssais->setCellValueByColumnAndRow($col, 11, $value['c_temperature']);
        $pvEssais->setCellValueByColumnAndRow($col, 12, $value['c_frequence']);
        $pvEssais->setCellValueByColumnAndRow($col, 13, $value['c_frequence_STL']);
        $pvEssais->setCellValueByColumnAndRow($col, 14, $value['c_type_1_val']);
        $pvEssais->setCellValueByColumnAndRow($col, 15, $value['c_type_2_val']);
        $pvEssais->setCellValueByColumnAndRow($col, 16, $value['c_waveform']);

        if (isset($value['denomination']['denomination_1'])) {
          $pvEssais->setCellValueByColumnAndRow($col, 17, $value['dim1']);
          $pvEssais->setCellValueByColumnAndRow(1, 17, $value['denomination']['denomination_1']);
          if ($value['dilatation']>1) {
            $pvEssais->setCellValueByColumnAndRow($col, 21, $value['dim1']*$value['dilatation']);
            $pvEssais->setCellValueByColumnAndRow(1, 21, $value['denomination']['denomination_1']);
          }
          else {
            $pvEssais->getRowDimension(21)->setVisible(FALSE);
          }
        }
        else {
          $pvEssais->getRowDimension(17)->setVisible(FALSE);
          $pvEssais->getRowDimension(21)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_2'])) {
          $pvEssais->setCellValueByColumnAndRow($col, 18, $value['dim2']);
          $pvEssais->setCellValueByColumnAndRow(1, 18, $value['denomination']['denomination_2']);
          if ($value['dilatation']>1) {
            $pvEssais->setCellValueByColumnAndRow($col, 22, $value['dim2']*$value['dilatation']);
            $pvEssais->setCellValueByColumnAndRow(1, 22, $value['denomination']['denomination_2']);
          }
          else {
            $pvEssais->getRowDimension(22)->setVisible(FALSE);
          }

        }
        else {
          $pvEssais->getRowDimension(18)->setVisible(FALSE);
          $pvEssais->getRowDimension(22)->setVisible(FALSE);
        }
        if (isset($value['denomination']['denomination_3'])) {
          $pvEssais->setCellValueByColumnAndRow($col, 19, $value['dim3']);
          $pvEssais->setCellValueByColumnAndRow(1, 19, $value['denomination']['denomination_3']);
          if ($value['dilatation']>1) {
            $pvEssais->setCellValueByColumnAndRow($col, 23, $value['dim3']*$value['dilatation']);
            $pvEssais->setCellValueByColumnAndRow(1, 23, $value['denomination']['denomination_3']);
          }
          else {
            $pvEssais->getRowDimension(23)->setVisible(FALSE);
          }
        }
        else {
          $pvEssais->getRowDimension(19)->setVisible(FALSE);
          $pvEssais->getRowDimension(23)->setVisible(FALSE);
        }


        $pvEssais->setCellValueByColumnAndRow($col, 27, $value['max']);
        $pvEssais->setCellValueByColumnAndRow($col, 28, ($value['max']+$value['min'])/2);
        $pvEssais->setCellValueByColumnAndRow($col, 29, ($value['max']-$value['min'])/2);
        $pvEssais->setCellValueByColumnAndRow($col, 30, $value['min']);


        $pvEssais->setCellValueByColumnAndRow($col, 44, $value['Cycle_final']);
        $pvEssais->setCellValueByColumnAndRow($col, 45, $value['Rupture']);
        $pvEssais->setCellValueByColumnAndRow($col, 46, $value['Fracture']);
        $pvEssais->setCellValueByColumnAndRow($col, 47, $value['temps_essais']);

        if ($value['d_checked']<=0 AND $value['n_fichier']>0) {
          $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'47')->applyFromArray( $style_unchecked );
          $pvEssais->setCellValueByColumnAndRow($col, 4, "Unchecked");
        }
        else {
          $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'47')->applyFromArray( $style_checked );

        }
        if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
          $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'44:'.PHPExcel_Cell::stringFromColumnIndex($col).'44')->applyFromArray( $style_running );
          $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'4')->applyFromArray( $style_running );
          $pvEssais->setCellValueByColumnAndRow($col, 4, "RUNNING");
        }



        if ($value['q_commentaire']!="") {

          $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
          $nb_q+=1; //on incremente le nombre de commentaire

          //recup du commentaire precedent
          $prev_value = $pvEssais->getCellByColumnAndRow($col_q, 50)->getValue();

          $pvEssais->setCellValueByColumnAndRow($col, 48, '('.($nb_q).')');
          $pvEssais->setCellValueByColumnAndRow($col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
          $pvEssais->mergeCells(PHPExcel_Cell::stringFromColumnIndex($col_q).'50:'.PHPExcel_Cell::stringFromColumnIndex($col_q+($nbPage-1)).'50');
          $pvEssais->getRowDimension(50)->setRowHeight(-1);




          //calcul de la hauteur max de la cellule de commentaire Qualité
          $rc = 0;
          $width=80;  //valeur empirique lié à la largeur des colonnes
          $line = explode("\n", $prev_value);
          foreach($line as $source) {
            $rc += intval((strlen($source) / $width) +1);
          }
          $maxheight=max($maxheight,$rc);
          $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


        }

        $col++;
      }

      //zone d'impression
      //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
      $colString = PHPExcel_Cell::stringFromColumnIndex((ceil(($col-3)/$nbPage)*$nbPage+3)-1);
      $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

      //separation impression par $nbPage eprouvettes
      for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
        $pvEssais->setBreak( PHPExcel_Cell::stringFromColumnIndex($c).(1) , PHPExcel_Worksheet::BREAK_COLUMN );
      }







}
ElseIf ($split['test_type_abbr']=="Str")	{

  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Report Str.xlsx");

  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV');
  $courbes=$objPHPExcel->getSheetByName('Courbes');

  $val2Xls = array(

    'J5' => $jobcomplet,
    'J9'=> $split['po_number'],
    'C5'=> $split['genre'].' '.$split['lastname'].' '.$split['surname'],
    'C6'=> $split['adresse'],

    'E16'=> $split['ref_matiere'],

    'E17'=> $split['info_jobs_instruction'],

    'E23'=> $split['specification'],
    'E26'=> $split['dessin'],

    'E34'=> $split['temperature'].' °C',

    'H38'=> $split['c_frequence'].' Hz',
    'H39'=> $split['c_frequence_STL'].' Hz',

    'E41'=> $split['waveform']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }

  //titre des lignes PV
  $pvEssais->setCellValueByColumnAndRow(0, 14, $split['c_type_1']);
  $pvEssais->setCellValueByColumnAndRow(2, 14, ($split['c_type_1']!='R' & $split['c_type_1']!='A')?$split['c_unite']:"");
  $pvEssais->setCellValueByColumnAndRow(0, 15, $split['c_type_2']);
  $pvEssais->setCellValueByColumnAndRow(2, 15, ($split['c_type_2']!='R' & $split['c_type_2']!='A')?$split['c_unite']:"");



  $row = 0; // 1-based index
  $col = 3;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 5; $row <= 48; $row++) {
      $style = $pvEssais->getStyleByColumnAndRow(3, $row);
      $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow($col, 5, $value['prefixe']);
    $pvEssais->setCellValueByColumnAndRow($col, 6, $value['nom_eprouvette']);

    $pvEssais->setCellValueByColumnAndRow($col, 7, $value['n_essai']);
    $pvEssais->setCellValueByColumnAndRow($col, 8, $value['n_fichier']);
    $pvEssais->setCellValueByColumnAndRow($col, 9, $value['machine']);
    $pvEssais->setCellValueByColumnAndRow($col, 10, $value['date']);
    $pvEssais->setCellValueByColumnAndRow($col, 11, $value['c_temperature']);
    $pvEssais->setCellValueByColumnAndRow($col, 12, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow($col, 13, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow($col, 14, $value['c_type_1_val']);

    $pvEssais->setCellValueByColumnAndRow($col, 15, $value['c_type_2_val']);

    $pvEssais->setCellValueByColumnAndRow($col, 16, str_replace(array("True","Tapered"), "", $value['waveform']));

    if (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow($col, 17, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow(1, 17, $value['denomination']['denomination_1']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow($col, 21, $value['dim1']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1, 21, $value['denomination']['denomination_1']);
      }
      else {
        $pvEssais->getRowDimension(21)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(17)->setVisible(FALSE);
      $pvEssais->getRowDimension(21)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow($col, 18, $value['dim2']);
      $pvEssais->setCellValueByColumnAndRow(1, 18, $value['denomination']['denomination_2']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow($col, 22, $value['dim2']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1, 22, $value['denomination']['denomination_2']);
      }
      else {
        $pvEssais->getRowDimension(22)->setVisible(FALSE);
      }

    }
    else {
      $pvEssais->getRowDimension(18)->setVisible(FALSE);
      $pvEssais->getRowDimension(22)->setVisible(FALSE);
    }
    if (isset($value['denomination']['denomination_3'])) {
      $pvEssais->setCellValueByColumnAndRow($col, 19, $value['dim3']);
      $pvEssais->setCellValueByColumnAndRow(1, 19, $value['denomination']['denomination_3']);
      if ($value['dilatation']>1) {
        $pvEssais->setCellValueByColumnAndRow($col, 23, $value['dim3']*$value['dilatation']);
        $pvEssais->setCellValueByColumnAndRow(1, 23, $value['denomination']['denomination_3']);
      }
      else {
        $pvEssais->getRowDimension(23)->setVisible(FALSE);
      }
    }
    else {
      $pvEssais->getRowDimension(19)->setVisible(FALSE);
      $pvEssais->getRowDimension(23)->setVisible(FALSE);
    }

    $pvEssais->setCellValueByColumnAndRow($col, 20, $value['E_RT']);
    $pvEssais->setCellValueByColumnAndRow($col, 24, (isset($value['dilatation'])?$value['denomination']['area']*$value['dilatation']*$value['dilatation']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 25, (isset($value['dilatation'])?$value['Lo']*$value['dilatation']:''));

    $pvEssais->setCellValueByColumnAndRow($col, 26, $value['c1_E_montant']);
    $pvEssais->setCellValueByColumnAndRow($col, 27, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow($col, 28, $value['c1_min_strain']);
    $pvEssais->setCellValueByColumnAndRow($col, 29, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow($col, 30, $value['c1_min_stress']);
    $pvEssais->setCellValueByColumnAndRow($col, 31, $value['c2_cycle']);

    $pvEssais->setCellValueByColumnAndRow($col, 32, (isset($value['c2_max_stress'])?$value['c2_max_stress']-$value['c2_min_stress']:''));

    $pvEssais->setCellValueByColumnAndRow($col, 33, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow($col, 34, $value['c2_min_stress']);
    $pvEssais->setCellValueByColumnAndRow($col, 35, $value['c2_E_montant']);
    $pvEssais->setCellValueByColumnAndRow($col, 36, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 37, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']-$value['c2_calc_inelastic_strain']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 38, $value['c2_calc_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow($col, 39, $value['c2_meas_inelastic_strain']);

    $pvEssais->setCellValueByColumnAndRow($col, 40,(isset($value['c2_max_strain'])?(($value['name']=="GE")?$value['c1_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10:$value['c2_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10):''));

    $pvEssais->setCellValueByColumnAndRow($col, 41, ($value['Cycle_STL']==0)?"":$value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow($col, 42, $value['Ni']);
    $pvEssais->setCellValueByColumnAndRow($col, 43, $value['Nf75']);
    $pvEssais->setCellValueByColumnAndRow($col, 44, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow($col, 45, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow($col, 46, $value['Fracture']);

    $pvEssais->setCellValueByColumnAndRow($col, 47, $value['temps_essais']);

    if ($value['d_checked']<=0 AND $value['n_fichier']>0) {
      $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'47')->applyFromArray( $style_unchecked );
      $pvEssais->setCellValueByColumnAndRow($col, 4, "Unchecked");
    }
    else {
      $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'47')->applyFromArray( $style_checked );

    }
    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'44:'.PHPExcel_Cell::stringFromColumnIndex($col).'44')->applyFromArray( $style_running );
      $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($col).'4:'.PHPExcel_Cell::stringFromColumnIndex($col).'4')->applyFromArray( $style_running );
      $pvEssais->setCellValueByColumnAndRow($col, 4, "RUNNING");
    }





    if ($value['q_commentaire']!="") {

      $col_q=floor(($col-3)/$nbPage)*$nbPage+3;
      $nb_q+=1; //on incremente le nombre de commentaire

      //recup du commentaire precedent
      $prev_value = $pvEssais->getCellByColumnAndRow($col_q, 50)->getValue();

      $pvEssais->setCellValueByColumnAndRow($col, 48, '('.($nb_q).')');
      $pvEssais->setCellValueByColumnAndRow($col_q, 50, $prev_value.' ('.($nb_q).') Test '.$value['n_fichier'].': '.$value['q_commentaire']."\n");
      $pvEssais->mergeCells(PHPExcel_Cell::stringFromColumnIndex($col_q).'50:'.PHPExcel_Cell::stringFromColumnIndex($col_q+($nbPage-1)).'50');
      $pvEssais->getRowDimension(50)->setRowHeight(-1);




      //calcul de la hauteur max de la cellule de commentaire Qualité
      $rc = 0;
      $width=80;  //valeur empirique lié à la largeur des colonnes
      $line = explode("\n", $prev_value);
      foreach($line as $source) {
        $rc += intval((strlen($source) / $width) +1);
      }
      $maxheight=max($maxheight,$rc);
      $pvEssais->getRowDimension(50)->setRowHeight($maxheight * 12.75 + 13.25);


    }

    $col++;
  }

  //zone d'impression
  //colstring = on augmente la zone d'impression, non pas a la derniere eprouvette mais a la serie de $nbpage d'apres.
  $colString = PHPExcel_Cell::stringFromColumnIndex((ceil(($col-3)/$nbPage)*$nbPage+3)-1);
  $pvEssais->getPageSetup()->setPrintArea('A1:'.$colString.(50));

  //separation impression par $nbPage eprouvettes
  for ($c=$nbPage+3; $c < ($col-1)*$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( PHPExcel_Cell::stringFromColumnIndex($c).(1) , PHPExcel_Worksheet::BREAK_COLUMN );
  }







}

ElseIf ($split['test_type_abbr']=="PssssssS")	{

  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT PS.xlsx");


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
  }



}
else {
  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT INCONNU.xlsx");
}




//exit;


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('../lib/PHPExcel/files/Report-'.$_GET['id_tbljob'].'.xlsx');

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
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');
exit;

?>
