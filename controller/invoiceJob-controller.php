<?php


// Rendre votre modèle accessible
include 'models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include 'models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();


// Rendre votre modèle accessible
include 'models/invoice-model.php';
// Création d'une instance
$oInvoices = new InvoiceModel($db);





//var_dump($splits);




// Affichage du résultat
include 'views/invoiceJob-view.php';
