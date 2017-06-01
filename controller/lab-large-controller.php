<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include_once 'models/lab-model.php';
// Création d'une instance
$oTest = new LabModel($db);
$test=$oTest->getTest();


//variable des etats des machines (run, stop, wip) pour la vue lab
$runStop=array();


foreach ($test as $value) {
  $poste[$value['poste']]=$value;

  //initialisation couleur
  $poste[$value['poste']]['background-color']='Yellow';
  $poste[$value['poste']]['color']='white';


  //recuperation des couleurs des blocs
  if ($value['currentBlock']=='Menu') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Parameters') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Adv.') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Check') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Amb.') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='ET') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Ramp') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Strain') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock']=='Switchable') {
    $poste[$value['poste']]['background-color']='yellow';
    $poste[$value['poste']]['color']='black';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock']=='Not') {
    $poste[$value['poste']]['background-color']='brown';
    $runStop[]="RUN";
  }
  elseif ($value['currentBlock']=='STL') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif ($value['currentBlock']=='Load') {
    $poste[$value['poste']]['background-color']='darkgreen';
    $runStop[]="RUN";
  }
  elseif($value['currentBlock']=='Stop') {
    $poste[$value['poste']]['background-color']='darkred';
    $runStop[]="STOP";
  }
  elseif($value['currentBlock']=='Report') {
    $poste[$value['poste']]['background-color']='gray';
    $runStop[]="STOP";
  }
  elseif($value['currentBlock']=='Send' OR $value['currentBlock']=='send') {
    $poste[$value['poste']]['background-color']='dimgray';
    $runStop[]="STOP";
  }
  elseif($value['currentBlock']=='Analysis') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  elseif($value['currentBlock']=='') {
    $poste[$value['poste']]['background-color']='Sienna';
    $runStop[]="WIP";
  }
  //$runStop[]=$poste[$value['poste']]['background-color'];



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
