<?php
include('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php


// Rendre votre modèle accessible
include '../models/split-model.php';
// Création d'une instance
$oSplit = new LstSplitModel($db,$_GET['id_tbljob']);
$split=$oSplit->getSplit();


// Rendre votre modèle accessible
include '../models/workflow.class.php';
// Création d'une instance
$oWorkflow = new WORKFLOW($db,$_GET['id_tbljob']);
$splits=$oWorkflow->getAllSplit();
$groupes=$oWorkflow->getAllGroupes();
//$eprouvettes=$oWorkflow->getAllGroupes();



  foreach ($groupes as $key=>$val)  {
    $groupes[$key]['split_explode']=explode(",", $val['ordre']);
    foreach ($groupes[$key]['split_explode'] as $k => $v) {
      $groupes[$key]['split'][$v]=1;
    }
  }





//var_dump($splits);
//var_dump($groupes);


// Affichage du résultat
include '../views/schedule-view.php';
