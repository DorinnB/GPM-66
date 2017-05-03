<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);
$test=$oTest->getTest();




foreach ($test as $value) {
  $poste[$value['poste']]=$value;


  if ($value['d_frequence']>0) {
    $frequence=$value['d_frequence'];
    $frequenceSTL=$value['d_frequence_STL'];
  }
  else {
    $frequence=$value['c_frequence'];
    $frequenceSTL=$value['c_frequence_STL'];
  }



  if ($value['c_cycle_STL']>0) {
    if ($value['Cycle_final']<$value['c_cycle_STL']) {
      $poste[$value['poste']]['tempsRestant']=round(($value['c_cycle_STL']-$value['Cycle_final'])/$frequence/3600, 1);
      //$poste[$value['poste']]['tempsRestant']='STL a faire bientot';
      //$poste[$value['poste']]['tempsRestant']=$value['c_cycle_STL']-$value['Cycle_final'];
    }
    else {
      if($value['runout']>0)  {
        $poste[$value['poste']]['tempsRestant']=round(($value['runout']-$value['Cycle_final'])/$frequenceSTL/3600, 1);
        //$poste[$value['poste']]['tempsRestant']='STL deja fait';
      }
      else {
        $poste[$value['poste']]['tempsRestant']='&infin;';
      }
    }
  }
  else {
    if($value['runout']>0)  {
      $poste[$value['poste']]['tempsRestant']=round(($value['runout']-$value['Cycle_final'])/$frequence/3600, 1);
        //$poste[$value['poste']]['tempsRestant']='pas de STL prevu';
    }
    else {
      $poste[$value['poste']]['tempsRestant']='&infin;';
        //$poste[$value['poste']]['tempsRestant']='pas de STL prevu';
    }
  }



}



?>
