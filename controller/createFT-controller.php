<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()



if (!isset($_GET['id_ep']) OR $_GET['id_ep']=="")	{
  exit();

}



// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

$oEprouvette = new EprouvetteModel($db,$_GET['id_ep']);

$essai=$oEprouvette->getTest();

//recuperation des commentaires des splits precedents
$workflow=$oEprouvette->getWorkflow();
$essai['comm']=(isset($workflow['comm']))?$workflow['comm']:"";



$oEprouvette->dimension($essai['type'],$essai['dim1'],$essai['dim2'],$essai['dim3']);
$nb_dim=count($oEprouvette->dimDenomination());
$area = $oEprouvette->area();

$oEprouvette->niveaumaxmin($essai['c_1_type'], $essai['c_2_type'], $essai['c_type_1_val'], $essai['c_type_2_val']);

$tempCorrected=$oEprouvette->getTempCorrected();









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
$wizard = new PHPExcel_Helper_HTML;



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






    if (isset($essai['split']))		//groupement du nom du job avec ou sans indice
    $jobcomplet= $essai['customer'].'-'.$essai['job'].'-'.$essai['split'];
    else
    $jobcomplet= $essai['customer'].'-'.$essai['job'];





    $essai['nom_eprouvette']=($essai['retest']!=1)?$essai['nom_eprouvette'].'<sup>'.$essai['retest'].'</sup>':$essai['nom_eprouvette'];

    if (isset($essai['prefixe']))		//groupement du nom d eprouvette avec ou sans préfixe
    $identification2= $essai['prefixe'].'-'.$essai['nom_eprouvette'];
    else
    $identification2= $essai['nom_eprouvette'];

    $identification = $wizard->toRichTextObject('<b>'.$identification2.'</b>');





    if (isset($essai['compresseur']) AND $essai['compresseur']==1)
    $compresseur="n";
    else
    $compresseur="o";

    $essai['ind_temp_top'] = (isset($essai['ind_temp_top']))? $essai['ind_temp_top'] : "";
    $essai['ind_temp_strap'] = (isset($essai['ind_temp_strap']))? $essai['ind_temp_strap'] : "";
    $essai['ind_temp_bot'] = (isset($essai['ind_temp_bot']))? $essai['ind_temp_bot'] : "";
    if ($essai['ind_temp_top'] == $essai['ind_temp_bot'] )	{		//groupement des ind.temp.
      if ($essai['ind_temp_top'] == $essai['ind_temp_strap'])
      $ind_temp = $essai['ind_temp_top'];
      else
      $ind_temp = $essai['ind_temp_top'].'/'.$essai['ind_temp_strap'];
    }
    else
    $ind_temp = $essai['ind_temp_top'].'/'.$essai['ind_temp_strap'].'/'.$essai['ind_temp_bot'];

    if (isset($essai['type_chauffage']) AND $essai['type_chauffage']=="Coil")	//chauffage coil
    $coil=$essai['chauffage'];
    else
    $coil="";

    if (isset($essai['type_chauffage']) AND $essai['type_chauffage']=="Four")	//chauffage coil
    $four=$essai['chauffage'];
    else
    $four="";

    if (isset($essai['c_cycle_STL']) AND $essai['c_cycle_STL']!="0")	//STL
    $STL=$essai['c_cycle_STL'];
    else
    $STL="";

    if (isset($essai['c_frequence_STL']) AND $essai['c_frequence_STL']!="0")	//STL
    $F_STL=$essai['c_frequence_STL'];
    else
    $F_STL="";

    if (isset($essai['runout']) AND $essai['runout']!="0")	//Runout
    $runout=$essai['runout'];
    else
    $runout="RTF";



    if ($essai['signal_true']=="1")	//chauffage coil
    $true='T-';
    else
    $true='';
    if ($essai['signal_tapered']=="1")	//chauffage coil
    $tapered='-T';
    else
    $tapered='';






    //cas particulier de la dim2
    if (!isset($essai['dim2']) OR $essai['dim2']=='')	//Runout
    $essai['dim2']=' ';
    //cas particulier de la dim3
    if (!isset($essai['dim3']) OR $essai['dim3']=='')	//Runout
    $essai['dim3']=' ';



    //	if (isset($essai['Cycle_min']) AND $essai['Cycle_min']!="0")	//STL
    //		$essai['Cycle_min']=$essai['Cycle_min'];
    //	else
    //		$essai['Cycle_min']="-";

























    If ($essai['test_type_abbr']=="Loa"  OR $essai['test_type_abbr']=="Flx")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT Loa.xlsx");


      $val2Xls = array(
        'B7' => $identification,
        'B8' => $essai['dessin'],
        'B9' => $essai['ref_matiere'],
        'B13' => $essai['machine'],
        'B15' => '40001',
        'B16' => $essai['enregistreur'],
        'F13' => $compresseur,
        'F17' => $ind_temp,
        'F15' => $coil,
        'F16' => $four,
        'B24' => $essai['cell_load_gamme'],
        'B23' => $essai['cell_displacement_gamme'],
        'B28' => "6",
        'D28' => "-6",

        'I7' => $jobcomplet,
        'I8' => $essai['n_essai'],
        'I9' => $essai['n_fichier'],
        'I10' => $essai['date'],
        'I11' => $essai['operateur'],
        'I12' => $essai['controleur'],

        'J17' => $essai['operateur'],
        'K20' => $essai['c_temperature'],
        'K21' => $tempCorrected,
        'K22' => $oEprouvette->R(),
        'K23' => $essai['c_frequence'],
        'I22' => $oEprouvette->A(),
        'I23' => $true.$essai['c_waveform'].$tapered,

        'I24' => $essai['dim1'],
        'K24' => $essai['dim2'],
        'K25' => $essai['dim3'],
        'I25' => $area,

        'K52' => $STL,
        'I52' => $F_STL,
        'J46' => $essai['Cycle_min'],
        'J49' => $runout,

        'A53' => $essai['comm'].' / '.$essai['c_commentaire'],
        'J53' => (($essai['stepcase_val']!='')?'Stepcase : '.$essai['steptype'].' / '.$essai['stepcase_val']:'')

      );


      //calcul niveau + limits
      if ($essai['c_unite']=="MPa")	{
        $arrayUnits = array(
          'I28' => $oEprouvette->MAX(),
          'I29' => ($oEprouvette->MAX()+$oEprouvette->MIN())/2,
          'I30' => ($oEprouvette->MAX()-$oEprouvette->MIN())/2,
          'I31' => $oEprouvette->MIN(),

          'J28' => $oEprouvette->MAX()*$area/1000,
          'J29' => ($oEprouvette->MAX()+$oEprouvette->MIN())/2*$area/1000,
          'J30' => ($oEprouvette->MAX()-$oEprouvette->MIN())/2*$area/1000,
          'J31' => $oEprouvette->MIN()*$area/1000,

          'B29' => $oEprouvette->MAX()*$area/1000+max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*$area/1000*5/100),0.5),
          'D29' => $oEprouvette->MIN()*$area/1000-max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*$area/1000*5/100),0.5)
        );
      }
      Elseif ($essai['c_unite']=="kN")	{
        $arrayUnits = array(
          'J28' => $oEprouvette->MAX(),
          'J29' => ($oEprouvette->MAX()+$oEprouvette->MIN())/2,
          'J30' => ($oEprouvette->MAX()-$oEprouvette->MIN())/2,
          'J31' => $oEprouvette->MIN(),

          'B29' => $oEprouvette->MAX()+max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5),
          'D29' => $oEprouvette->MIN()-max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5)
        );
      }
      Else	{
        $arrayUnits = array(
          'J28' => "ERREUR d'unité"
        );
      }






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



      $val2Xls = array_merge($val2Xls, $arrayUnits);
      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $objPHPExcel->getActiveSheet()->setCellValue($key, $value);
      }

      //tableau pour le stepcase
      if ($essai['stepcase_val']!='') {
        $objPHPExcel->getActiveSheet()->setCellValue('H54', 'Stepcase n°');
        $objPHPExcel->getActiveSheet()->setCellValue('I54', 'Max ('.$essai['c_unite'].')');
        $objPHPExcel->getActiveSheet()->setCellValue('J54', 'Min ('.$essai['c_unite'].')');
        $objPHPExcel->getActiveSheet()->setCellValue('K54', 'Runout');
        for ($i=0; $i <5 ; $i++) {
          $oEprouvette->niveaumaxmin(
            $essai['c_1_type'],
            $essai['c_2_type'],
            $essai['c_type_1_val']+(($essai['c_1_type']==$essai['steptype'])?$i*$essai['stepcase_val']:0),
            $essai['c_type_2_val']+(($essai['c_2_type']==$essai['steptype'])?$i*$essai['stepcase_val']:0)
          );
          $objPHPExcel->getActiveSheet()->setCellValue('H'.(55+$i), 'Stepcase '.($i+1));
          $objPHPExcel->getActiveSheet()->setCellValue('I'.(55+$i), $oEprouvette->MAX());
          $objPHPExcel->getActiveSheet()->setCellValue('J'.(55+$i), $oEprouvette->MIN());
          $objPHPExcel->getActiveSheet()->setCellValue('K'.(55+$i), $runout*($i+1));


          //calcul des limites avec le niveau le plus extreme des 5 stepcases
          if ($essai['c_unite']=="MPa")	{
            $objPHPExcel->getActiveSheet()->setCellValue('B29', number_format(max($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 29)->getValue(),$oEprouvette->MAX()*$area/1000+max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*$area/1000*5/100),0.5)), 1, '.', ','));
            $objPHPExcel->getActiveSheet()->setCellValue('D29', number_format(min($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 29)->getValue(),$oEprouvette->MIN()*$area/1000-max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*$area/1000*5/100),0.5)), 1, '.', ','));
          }
          Elseif ($essai['c_unite']=="kN")	{
            $objPHPExcel->getActiveSheet()->setCellValue('B29', number_format(max($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 29)->getValue(),$oEprouvette->MAX()+max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5)), 1, '.', ','));
            $objPHPExcel->getActiveSheet()->setCellValue('D29', number_format(min($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 29)->getValue(),$oEprouvette->MIN()-max(abs(max(abs($oEprouvette->MAX()), abs($oEprouvette->MIN()))*5/100),0.5)), 1, '.', ','));
          }
        }
        //on ajoute * apres les limites pour signifier l'incertitude des limites
        $objPHPExcel->getActiveSheet()->setCellValue('B29', number_format($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 29)->getValue(), 1, '.', ',').'*');
        $objPHPExcel->getActiveSheet()->setCellValue('D29', number_format($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 29)->getValue(), 1, '.', ',').'*');

      }

    }
    ElseIf ($essai['test_type_abbr']=="LoS" OR $essai['test_type_abbr']=="Dwl")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT LoS.xlsx");

      $val2Xls = array(
        'B7' => $identification,
        'B8' => $essai['dessin'],
        'B9' => $essai['ref_matiere'],
        'B13' => $essai['machine'],
        'B15' => '40001',
        'B16' => $essai['enregistreur'],
        'B17' => $essai['extensometre'],
        'F13' => $compresseur,
        'F17' => $ind_temp,
        'F15' => $coil,
        'F16' => $four,
        'B24' => $essai['cell_load_gamme'],
        'B23' => $essai['cell_displacement_gamme'],
        'B28' => "6",
        'D28' => "-6",

        'I7' => $jobcomplet,
        'I8' => $essai['n_essai'],
        'I9' => $essai['n_fichier'],
        'I10' => $essai['date'],
        'I11' => $essai['operateur'],
        'I12' => $essai['controleur'],

        'J17' => $essai['operateur'],
        'K20' => $essai['c_temperature'],
        'K21' => $tempCorrected,
        'K22' => $oEprouvette->R(),
        'K23' => $essai['c_frequence'],
        'I22' => $oEprouvette->A(),
        'I23' => $true.$essai['c_waveform'].$tapered,

        'I24' => $essai['dim1'],
        'K24' => $essai['dim2'],
        'K25' => $essai['dim3'],
        'I25' => $area,

        'K52' => $STL,
        'I52' => $F_STL,
        'J46' => $essai['Cycle_min'],
        'J49' => $runout,

        'A53' => $essai['comm'].' / '.$essai['c_commentaire']
      );



      if ($essai['c_unite']=="MPa")	{
        $arrayUnits = array(
          'I28' => $oEprouvette->MAX(),
          'I29' => ($oEprouvette->MAX()+$oEprouvette->MIN())/2,
          'I30' => ($oEprouvette->MAX()-$oEprouvette->MIN())/2,
          'I31' => $oEprouvette->MIN(),

          'J28' => $oEprouvette->MAX()*$area/1000,
          'J29' => ($oEprouvette->MAX()+$oEprouvette->MIN())/2*$area/1000,
          'J30' => ($oEprouvette->MAX()-$oEprouvette->MIN())/2*$area/1000,
          'J31' => $oEprouvette->MIN()*$area/1000,

          'B29' => $oEprouvette->MAX()*$area/1000+(($oEprouvette->MAX()*$area/1000<10)?0.5:$oEprouvette->MAX()*$area/1000*5/100),
          'D29' => $oEprouvette->MIN()*$area/1000-(($oEprouvette->MAX()*$area/1000<10)?0.5:$oEprouvette->MAX()*$area/1000*5/100)
        );
      }
      Elseif ($essai['c_unite']=="kN")	{
        $arrayUnits = array(
          'J28' => $oEprouvette->MAX(),
          'J29' => ($oEprouvette->MAX()+$oEprouvette->MIN())/2,
          'J30' => ($oEprouvette->MAX()-$oEprouvette->MIN())/2,
          'J31' => $oEprouvette->MIN(),

          'B29' => $oEprouvette->MAX()+$oEprouvette->MAX()*5/100,
          'D29' => $oEprouvette->MIN()-$oEprouvette->MAX()*5/100
        );
      }
      Else	{
        $arrayUnits = array(
          'J28' => "ERREUR d'unité"
        );
      }



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



      $val2Xls = array_merge($val2Xls, $arrayUnits);
      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $objPHPExcel->getActiveSheet()->setCellValue($key, $value);
      }



    }
    ElseIf ($essai['test_type_abbr']=="Crp" OR $essai['test_type_abbr']=="ICr")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT Crp.xlsx");

      $val2Xls = array(
        'B7' => $identification,
        'B8' => $essai['dessin'],
        'B9' => $essai['ref_matiere'],
        'B13' => $essai['machine'],
        'B15' => '40001',
        'B16' => $essai['enregistreur'],
        'B17' => $essai['extensometre'],
        'F13' => $compresseur,
        'F17' => $ind_temp,
        'F15' => $coil,
        'F16' => $four,
        'B24' => $essai['cell_load_gamme'],
        'B23' => $essai['cell_displacement_gamme'],
        'B28' => "6",
        'D28' => "-6",

        'I7' => $jobcomplet,
        'I8' => $essai['n_essai'],
        'I9' => $essai['n_fichier'],
        'I10' => $essai['date'],
        'I11' => $essai['operateur'],
        'I12' => $essai['controleur'],

        'J17' => $essai['operateur'],
        'K20' => $essai['c_temperature'],
        'K21' => $tempCorrected,
        'K22' => "N/A",
        'K23' => "N/A",
        'I22' => "N/A",
        'I23' => "Ramp",

        'I24' => $essai['dim1'],
        'K24' => $essai['dim2'],
        'K25' => $essai['dim3'],
        'I25' => $area,

        'K52' => $STL,
        'I52' => $F_STL,
        'J46' => $essai['Cycle_min'],
        'J49' => $runout,

        'A53' => $essai['comm'].' / '.$essai['c_commentaire']
      );



      if ($essai['c_unite']=="MPa")	{
        $arrayUnits = array(
          'I28' => $essai['c_type_1_val'],
          'I29' => $essai['c_type_2_val'],
          'I30' => $essai['c_type_3_val'],
          'I31' => $essai['c_type_4_val'],

          'J28' => isset($essai['c_type_1_val'])?$essai['c_type_1_val']*$area/1000:'',

          'J30' => $essai['c_type_3_val']*$area/1000,


          'B29' =>number_format((max($essai['c_type_1_val'],$essai['c_type_3_val'])*$area/1000*1.05), 1, '.', ',').'*',
          'D29' => "-1(*)"
        );
      }
      Elseif ($essai['c_unite']=="kN")	{
        $arrayUnits = array(
          'J28' => $essai['c_type_1_val'],
          'I29' => $essai['c_type_2_val'].' s',
          'J30' => $essai['c_type_3_val'],
          'I31' => $essai['c_type_4_val'].' s',

          'B29' => number_format((max($essai['c_type_1_val'],$essai['c_type_3_val'])*1.05), 1, '.', ',').'*',
          'D29' => "-1(*)"
        );
      }
      Else	{
        $arrayUnits = array(
          'J28' => "ERREUR d'unité"
        );
      }



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



      $val2Xls = array_merge($val2Xls, $arrayUnits);
      //Pour chaque element du tableau associatif, on update les cellules Excel
      foreach ($val2Xls as $key => $value) {
        $objPHPExcel->getActiveSheet()->setCellValue($key, $value);
      }



    }
    ElseIf ($essai['test_type_abbr']=="Str"  OR $essai['test_type_abbr']=="IF" OR $essai['test_type_abbr']=="IRlx")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT Str.xlsx");


      $val2Xls = array(
        'B7' => $identification,
        'B8'=> $essai['dessin'],
        'B9' => $essai['ref_matiere'],
        'B14' => $essai['machine'],
        'B15' => '40001',
        'B16' => $essai['enregistreur'],
        'F14' => $compresseur,
        'F17' => $ind_temp,
        'B17' => $essai['extensometre'],
        'F15' => $coil,
        'F16' => $four,
        'B24' => $essai['cell_load_gamme'],
        'B23' => $essai['cell_displacement_gamme'],
        'B25' => '5',
        'B28' => '3',
        'B29' => '',
        'B30' => $oEprouvette->MAX()+0.15,
        'D28' => '-3',
        'D29' => '',
        'D30' => $oEprouvette->MIN()-0.15,
        'I7' => $jobcomplet,
        'I8' => $essai['n_essai'],
        'I9' => $essai['n_fichier'],
        'I10' => $essai['date'],
        'I11' => $essai['operateur'],
        'I12' => $essai['controleur'],
        'J16' => $essai['operateur'],
        'K18' => $essai['c_temperature'],
        'K20' => $tempCorrected,
        'J47' => $tempCorrected,
        'K21' => $oEprouvette->R(),
        'K22' => $essai['c_frequence'],
        'I21' => $oEprouvette->A(),
        'I22' => $true.$essai['c_waveform'].$tapered,
        'J24' => $essai['dim1'],
        'I24' => $essai['dim2'],
        'I23' => $essai['dim3'],
        'J25' => $area,
        'J26' => $essai['Lo'],
        'J29' => $oEprouvette->MAX()-$oEprouvette->MIN(),
        'J30' => $oEprouvette->MAX(),
        'J31' => $oEprouvette->MIN(),
        'B45' => $STL,
        'B46' => $F_STL,
        'J56' => $essai['Cycle_min'],
        'J59' => $runout,

        'A63' => $essai['comm'].' / '.$essai['c_commentaire']
      );

      //affichage du checkeur temperature uniquement si temperature
      if ($essai['c_temperature']>=50) {
        $val2Xls['J17'] = $essai['controleur'];
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
    ElseIf ($essai['test_type_abbr']=="PS")	{

      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT PS.xlsx");


      $val2Xls = array(
        'B7' => $identification,
        'B8'=> $essai['dessin'],
        'B9' => $essai['ref_matiere'],
        'B14' => $essai['machine'],
        'B15' => '40001',
        'B16' => $essai['enregistreur'],
        'F14' => $compresseur,
        'F17' => $ind_temp,
        'B17' => $essai['extensometre'],
        'F15' => $coil,
        'F16' => $four,
        'B24' => $essai['cell_load_gamme'],
        'B23' => $essai['cell_displacement_gamme'],
        'B25' => '5',
        'B28' => '3',
        'B29' => '',
        'B30' => $oEprouvette->MAX()+0.15,
        'D28' => '-3',
        'D29' => '',
        'D30' => $oEprouvette->MIN()-0.15,
        'I7' => $jobcomplet,
        'I8' => $essai['n_essai'],
        'I9' => $essai['n_fichier'],
        'I10' => $essai['date'],
        'I11' => $essai['operateur'],
        'I12' => $essai['controleur'],
        'J16' => $essai['operateur'],
        'K18' => $essai['c_temperature'],
        'K21' => $oEprouvette->R(),
        'K22' => $essai['c_frequence'],
        'I21' => $oEprouvette->A(),
        'I22' => $true.$essai['c_waveform'].$tapered,
        'J24' => $essai['dim1'],
        'I24' => $essai['dim2'],
        'I23' => $essai['dim3'],
        'J25' => $area,
        'J26' => $essai['Lo'],
        'J29' => $oEprouvette->MAX()-$oEprouvette->MIN(),
        'J30' => $oEprouvette->MAX(),
        'J31' => $oEprouvette->MIN(),
        'B45' => $STL,
        'B46' => $F_STL,
        'J56' => $essai['Cycle_min'],
        'J59' => $runout
      );



      //affichage du checkeur temperature uniquement si temperature
      if ($essai['c_temperature']>=50) {
        $val2Xls['J17'] = $essai['controleur'];
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
    else {
      $objPHPExcel = $objReader->load("../lib/PHPExcel/templates/FT INCONNU.xlsx");
    }




    //exit;

    $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
    $objPHPExcel->getActiveSheet()->getProtection()->setPassword("metcut44");


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('../lib/PHPExcel/files/FT-'.$essai['n_fichier'].'.xlsx');

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="FT-'.$essai['n_fichier'].'.xlsx"');
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
