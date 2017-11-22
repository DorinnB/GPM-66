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


If (isset($_GET['Cust']) AND $_GET['Cust']=="SAE" AND $split['test_type_abbr']=="Str")	{

  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/SAE_Str3.xlsx");

  $enTete=$objPHPExcel->getSheetByName('En-tête');
  $pvEssais=$objPHPExcel->getSheetByName('PV essai');
  //$piecesJointes=$objPHPExcel->getSheetByName('Pièces Jointes');



  $val2Xls = array(

    'F6' => $jobcomplet,
    'F7'=> date("Y-m-d"),
    'F43'=> $split['tbljob_commentaire_qualite']

  );

  //Pour chaque element du tableau associatif, on update les cellules Excel
  foreach ($val2Xls as $key => $value) {
    $enTete->setCellValue($key, $value);
  }



  $row = 0; // 1-based index
  $col = 4;

  $row_q=0;
  $col_q=0;
  $nb_q=0;
  $max_row_q=0;
  $nbPage=10;
  $maxheight=0;

  foreach ($ep as $key => $value) {
    //copy des styles des colonnes
    for ($row = 1; $row <= 125; $row++) {
      $style = $pvEssais->getStyleByColumnAndRow(5, $row);
      $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($row);
      $pvEssais->duplicateStyle($style, $dstCell);
    }

    $pvEssais->setCellValueByColumnAndRow($col, 6, (isset($value['prefixe'])?$value['prefixe'].'-'.$value['nom_eprouvette']:$value['nom_eprouvette']));
    $pvEssais->setCellValueByColumnAndRow($col, 7, $value['n_fichier']);

    $pvEssais->setCellValueByColumnAndRow($col, 10, $value['date']);

    $pvEssais->setCellValueByColumnAndRow($col, 12, $split['ref_matiere']);

    $pvEssais->setCellValueByColumnAndRow($col, 14, $value['machine']);


    $pvEssais->setCellValueByColumnAndRow($col, 18, '12mm manuel');

    $pvEssais->setCellValueByColumnAndRow($col, 20, $value['type_chauffage']);
    $pvEssais->setCellValueByColumnAndRow($col, 21, $value['gage']);




    if (isset($value['denomination']['denomination_2'])) {
      $pvEssais->setCellValueByColumnAndRow($col, 24, $value['dim1']);
      $pvEssais->setCellValueByColumnAndRow($col, 25, $value['dim2']);
    }
    elseif (isset($value['denomination']['denomination_1'])) {
      $pvEssais->setCellValueByColumnAndRow($col, 22, $value['dim1']);
    }

    $pvEssais->setCellValueByColumnAndRow($col, 30, $value['c_temperature']);


    $pvEssais->setCellValueByColumnAndRow($col, 33, (isset($value['max'])?$value['max']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 34, (isset($value['max'])?$value['min']/$value['max']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 35, $value['c_frequence']);
    $pvEssais->setCellValueByColumnAndRow($col, 36, $value['c_frequence_STL']);
    $pvEssais->setCellValueByColumnAndRow($col, 37, str_replace(array("True","Tapered"), "", $value['waveform']));


    $pvEssais->setCellValueByColumnAndRow($col, 40, $value['c1_E_montant']);

    $pvEssais->setCellValueByColumnAndRow($col, 47, $value['c1_max_stress']);
    $pvEssais->setCellValueByColumnAndRow($col, 48, $value['c1_min_stress']);

    $pvEssais->setCellValueByColumnAndRow($col, 50, $value['c1_max_strain']);
    $pvEssais->setCellValueByColumnAndRow($col, 51, $value['c1_min_strain']);


    $pvEssais->setCellValueByColumnAndRow($col, 70, $value['c2_cycle']);
    $pvEssais->setCellValueByColumnAndRow($col, 71, $value['c2_E_montant']);

    $pvEssais->setCellValueByColumnAndRow($col, 73, $value['c2_max_stress']);
    $pvEssais->setCellValueByColumnAndRow($col, 74, $value['c2_min_stress']);

  //PROBLEME VBA qui reecrivait pseudo stress sur l'emplacement min strain
  $value['c2_min_strain']=($value['c2_min_strain']>$value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_delta_strain']:$value['c2_min_strain'];
    $pvEssais->setCellValueByColumnAndRow($col, 76, $value['c2_max_strain']);
    $pvEssais->setCellValueByColumnAndRow($col, 77, $value['c2_min_strain']);



    $pvEssais->setCellValueByColumnAndRow($col, 83, ($value['Cycle_STL']==0)?"":$value['Cycle_STL']);

    $pvEssais->setCellValueByColumnAndRow($col, 86, $value['Cycle_final']);

    $pvEssais->setCellValueByColumnAndRow($col, 87, (($value['Ni']=="")?"":$value['Ni'].'(Ni)'));
    $pvEssais->setCellValueByColumnAndRow($col, 88, (($value['Nf75']=="")?"":$value['Nf75']));

    $pvEssais->setCellValueByColumnAndRow($col, 119, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow($col, 120, $value['Fracture']);

    $pvEssais->setCellValueByColumnAndRow($col, 91, (($value['valid']=0)?'Non Valide':'Valide'));
    $pvEssais->setCellValueByColumnAndRow($col, 95, $value['Cycle_min']);
    $pvEssais->setCellValueByColumnAndRow($col, 96, (($value['Cycle_final']<$value['Cycle_min'])?'Non conforme':'Conforme'));


    if (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
      $pvEssais->setCellValueByColumnAndRow($col, 123, "Unchecked");
    }
    if ($value['Cycle_final_valid']==0 AND isset($value['Cycle_final'])) {
      $pvEssais->setCellValueByColumnAndRow($col, 123, "RUNNING");
    }

    $pvEssais->setCellValueByColumnAndRow($col, 117, $value['q_commentaire']);



    $col++;
  }



}

elseIf ($split['test_type_abbr']=="Loa")	{

  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/Report Loa.xlsx");


    $enTete=$objPHPExcel->getSheetByName('En-tête');
    $pvEssais=$objPHPExcel->getSheetByName('PV');
    $courbes=$objPHPExcel->getSheetByName('Courbes');

    $specifEssais = ($enTete->getCellByColumnAndRow(10, 19)->getValue()).$split['specification'].($enTete->getCellByColumnAndRow(11, 19)->getValue());
    $val2Xls = array(

      'J5' => $jobcomplet,
      'J9'=> $split['po_number'],
      'C5'=> $split['lastname'].' '.$split['surname'],
      'C6'=> $split['compagnie']."\n".$split['adresse'],
      'J7'=> date("Y-m-d"),

      'E16'=> $split['ref_matiere'],

      'E17'=> $split['info_jobs_instruction'],

      'E19'=> $specifEssais,
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
    $pvEssais->setCellValueByColumnAndRow($col, 16, str_replace(array("True","Tapered"), "", strtoupper($value['c_waveform'])));

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
      $pvEssais->setCellValueByColumnAndRow($col, 47, (($value['temps_essais']>0)?$value['temps_essais']:$value['temps_essais_calcule']));

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
      $pvEssais->setCellValueByColumnAndRow($c-1, 1, $jobcomplet);
      $pvEssais->setCellValueByColumnAndRow($c-3, 1, "No. DE TRAVAIL :");
      $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($c-3).(1))->getFont()->setBold(true);
    }






}
ElseIf ($split['test_type_abbr']=="LoS" OR $split['test_type_abbr']=="Dwl")	{

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

  $specifEssais = ($enTete->getCellByColumnAndRow(10, 19)->getValue()).$split['specification'].($enTete->getCellByColumnAndRow(11, 19)->getValue());

  $val2Xls = array(

    'J5' => $jobcomplet,
    'J9'=> $split['po_number'],
    'C5'=> $split['lastname'].' '.strtoupper($split['surname']),
    'C6'=> $split['compagnie']."\n".$split['adresse'],
    'J7'=> date("Y-m-d"),
    'E16'=> $split['ref_matiere'],

    'E17'=> $split['info_jobs_instruction'],
    'E19'=> $specifEssais,

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

    $pvEssais->setCellValueByColumnAndRow($col, 16, str_replace(array("TRUE","TAPERED"), "", strtoupper($value['waveform'])));

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

    //PROBLEME VBA qui reecrivait pseudo stress sur l'emplacement min strain
  $value['c2_min_strain']=($value['c2_min_strain']>$value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_delta_strain']:$value['c2_min_strain'];


    $pvEssais->setCellValueByColumnAndRow($col, 36, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 37, (isset($value['c2_max_strain'])?$value['c2_max_strain']-$value['c2_min_strain']-$value['c2_calc_inelastic_strain']:''));
    $pvEssais->setCellValueByColumnAndRow($col, 38, $value['c2_calc_inelastic_strain']);
    $pvEssais->setCellValueByColumnAndRow($col, 39, $value['c2_meas_inelastic_strain']);

    $pvEssais->setCellValueByColumnAndRow($col, 40,(isset($value['c2_max_strain'])?(($value['name']=="GE")?$value['c1_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10:$value['c2_E_montant']*($value['c2_max_strain']-$value['c2_min_strain'])/2*10):''));

    $pvEssais->setCellValueByColumnAndRow($col, 41, ($value['Cycle_STL']==0)?"":$value['Cycle_STL']);
    $pvEssais->setCellValueByColumnAndRow($col, 42, (($value['Ni']=="")?"NA":$value['Ni']));
    $pvEssais->setCellValueByColumnAndRow($col, 43, (($value['Nf75']=="")?"NA":$value['Nf75']));
    $pvEssais->setCellValueByColumnAndRow($col, 44, $value['Cycle_final']);
    $pvEssais->setCellValueByColumnAndRow($col, 45, $value['Rupture']);
    $pvEssais->setCellValueByColumnAndRow($col, 46, $value['Fracture']);

    $pvEssais->setCellValueByColumnAndRow($col, 47, $value['temps_essais']);

    if (($value['d_checked']<=0 AND $value['n_fichier']>0) OR $value['flag_qualite']>0) {
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
  for ($c=$nbPage+3; $c < ($col-1)+$nbPage ; $c+=$nbPage) {
    $pvEssais->setBreak( PHPExcel_Cell::stringFromColumnIndex($c).(1) , PHPExcel_Worksheet::BREAK_COLUMN );
        $pvEssais->setCellValueByColumnAndRow($c-1, 1, $jobcomplet);
        $pvEssais->setCellValueByColumnAndRow($c-3, 1, "No. DE TRAVAIL :");
        $pvEssais->getStyle(PHPExcel_Cell::stringFromColumnIndex($c-3).(1))->getFont()->setBold(true);
  }







}
ElseIf ($split['test_type_abbr']=="PS")	{

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
else {
  $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT INCONNU.xlsx");
}







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
