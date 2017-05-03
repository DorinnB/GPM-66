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
//return $oSplit->updateSplit($_POST['id_statut']);
$oSplit->specification=isset($_POST['specification'])?$_POST['specification']:"";
$oSplit->c_type_1=isset($_POST['c_type_1'])?$_POST['c_type_1']:"";
$oSplit->c_type_2=isset($_POST['c_type_2'])?$_POST['c_type_2']:"";
$oSplit->c_unite=isset($_POST['c_unite'])?$_POST['c_unite']:"";
$oSplit->waveform=isset($_POST['waveform'])?$_POST['waveform']:"";
$oSplit->test_leadtime=isset($_POST['test_leadtime'])?$_POST['test_leadtime']:"";
$oSplit->tbljob_instruction=isset($_POST['tbljob_instruction'])?$_POST['tbljob_instruction']:"";
$oSplit->comments=isset($_POST['comments'])?$_POST['comments']:"";

$oSplit->updateData();

?>
