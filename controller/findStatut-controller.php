<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/split-model.php';

$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);

$state=$oSplit->findStatut();


//var_dump($state);


$statut='';

if ($state['nbInOut_A']<$state['nbMasterEp']) {
  $statut='awaiting specimen';
}

if ($state['nbLocal'] > 0) {
  $statut='awaiting InHouse';
}
elseif ($state['nbSubC'] > 0) {
  $statut='awaiting SubC';
}


elseif ($state['checked']==1 AND $state['nbConsLeftAndInOut_A']>1 AND $state['nbTestLeft']>0) {
    $statut='ready to test';
}
/*
if ($state['checked']==1 AND $state['nbConsLeft']>1) {
    $statut='ready to test SANS INOUT';
}*/



if ($state['running']>=1) {
  if ($state['nbTestLeft']==0) {
    $statut='running last spec';
  }
  elseif ($state['nbConsLeft']==0) {
    $statut='running last cond';
  }
  else {
    $statut='running';
  }
}
elseif ($state['nbConsLeft']==0 AND $state['nbTestLeft']>0) {
    $statut='Testing on Hold Condition';

}


if ($state['nbUnDChecked']==0 AND $state['nbTestLeft']==0) {
    $statut='Emission rapport';
}
elseif ($state['nbUnDChecked']>0 AND $state['nbTestLeft']==0) {
  $statut='Emission rapport mais check ep demandé';
}



$maReponse = array('statut' => $statut, 'state' => $state);
      echo json_encode($maReponse);


?>
