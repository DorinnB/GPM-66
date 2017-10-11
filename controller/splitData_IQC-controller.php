<?php




$DataInput=(isset($_GET['modif']) AND $_GET['modif']=="dataInput")?"Input":"";

// Rendre votre modèle accessible
include 'models/lstDrawing-model.php';
$lstDwg = new DwgModel($db);
$Dwg = $lstDwg->getDwg($split['id_dessin']);
$lstDwg->dimension($Dwg['type']);
$nomDimension=$lstDwg->dimDenomination();


// Affichage du résultat
include 'views/splitData'.$DataInput.'_IQC-view.php';
