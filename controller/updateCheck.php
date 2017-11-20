<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);



$oSplit->updateCheck();

//Update du statut des splits
include '../models/statut-model.php';
$oStatut = new StatutModel($db);
$oStatut->id_tbljob=$_POST['id_tbljob'];
$state=$oStatut->findStatut();

?>
