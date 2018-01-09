<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_POST['idEp']);

if($_POST['iduser']!=0){

  $oEprouvette->iduser=$_POST['iduser'];

//On supprime les POST n'etant pas des valeurs Aux
$data = array_diff_key($_POST, [
  'checkAux' => "***",
  'idEp' => "***",
  'iduser' => "***"]
);




  if ($_POST['checkAux']=="save") {

    //  $oEprouvette->Rupture=$_POST['Rupture'];

      $oEprouvette->updateAux($data);
$oEprouvette->createTechSplit($_POST['iduser']);
      //$maReponse = array('id_eprouvette' => $_POST['idEp']);
      //echo json_encode($maReponse);

  }
  else {
      $oEprouvette->updateDCheck($_POST['iduser']);
$oEprouvette->createTechSplit($_POST['iduser']);      
    //$oEprouvette->updateCheckRupture($_POST['iduser']);
  }


}

?>
