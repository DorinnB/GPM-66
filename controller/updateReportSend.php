<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/split-model.php';

// Création d'une instance
$oSplit = new LstSplitModel($db,$_POST['id_tbljob']);

$id_reportSend = ($_POST['id_reportSend']<=0)?$_COOKIE['id_user']:-$_COOKIE['id_user'];
$oSplit->updateReportSend($id_reportSend);

?>
