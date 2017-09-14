<?php




$DataInput=($_GET['modif']=="dataInput")?"Input":"";


include '../models/lstConsigne-model.php';
$lstConsigne = new ConsigneModel($db);
$Consigne = $lstConsigne->getAllConsigne();

include '../models/lstRawData-model.php';
$lstRawData = new RawDataModel($db);
$RawData = $lstRawData->getAllRawData();


// Affichage du résultat
include '../views/splitData'.$DataInput.'_default-view.php';
