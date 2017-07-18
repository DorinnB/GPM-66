<?php




$DataInput=($_GET['modif']=="dataInput")?"Input":"";


include '../models/lstConsigne-model.php';
$lstConsigne = new ConsigneModel($db);
$Consigne = $lstConsigne->getAllConsigne();

// Affichage du résultat
include '../views/splitData'.$DataInput.'_.default-view.php';
