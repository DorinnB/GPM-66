<?php




$DataInput=($_GET['modif']=="dataInput")?"Input":"";


include '../models/lstConsigne-model.php';
$lstConsigne = new ConsigneModel($db);
$Consigne = $lstConsigne->getAllConsigne();

// Rendre votre modèle accessible
include '../models/lstContact-model.php';
$lstCustomer = new ContactModel($db);
$ref_customerST=$lstCustomer->getAllref_customer();


// Affichage du résultat
include '../views/splitData'.$DataInput.'_.default-view.php';
