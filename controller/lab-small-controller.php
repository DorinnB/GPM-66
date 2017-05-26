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
  $poste[$value['poste']]['background-color']='#536E94';
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
  elseif($value['currentBlock']=='Send') {
    $poste[$value['poste']]['background-color']='dimgray';
    $runStop[]="STOP";
  }

}





// Affichage du résultat
include 'views/lab-small-view.php';
?>
