<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['idtbljob']);

if ($_POST['role']=="Q") {
  $oSplit->updateCheckQ();
}
elseif ($_POST['role']=="TM") {
  $oSplit->updateCheckTM();
}
elseif ($_POST['role']=="RawData") {
  $oSplit->updateRawData();
}

//Update du statut du job
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
$oStatut->id_tbljob=$_POST['idtbljob'];
$state=$oStatut->findStatut();
?>
