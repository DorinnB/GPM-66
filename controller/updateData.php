<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);

// Retour de l'update si erreur

$oSplit->refSubC=isset($_POST['refSubC'])?$_POST['refSubC']:"";

$oSplit->DyT_Cust=isset($_POST['DyT_Cust'])?$_POST['DyT_Cust']:"NULL";
$oSplit->DyT_expected=(isset($_POST['DyT_expected']) AND $_POST['DyT_expected']!="")?$_POST['DyT_expected']:(isset($_POST['DyT_Cust'])?$_POST['DyT_Cust']:"NULL");


$oSplit->DyT_expected();
?>
