<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php


echo $_GET['id_info_job'];
$id_old_tbljob=$_GET['id_info_job'];


// Rendre votre modèle accessible
include '../models/copyJob-model.php';

// Création d'une instance
$oInfoJob = new InfoJob($db, $id_old_tbljob);

//copy de l'info job et récupération du nouvel id
$newInfoJob = $oInfoJob->copyInfoJob();
echo '<br/>new id_info_job : '.$newInfoJob;



//on parcourt les masters de l'infojob et pour chacun, on le copy en enregistrant dans un tableau l'ancien et le nouvel id equivalent
foreach ($oInfoJob->getMasterEprouvettes() as $masterEprouvette) {
  $idMasterEprouvette[$masterEprouvette['id_master_eprouvette']]=$oInfoJob->copyMasterEprouvette($masterEprouvette['id_master_eprouvette']);
}
var_dump($idMasterEprouvette);


//pour chaque tbljob (split)
foreach ($oInfoJob->getTbljobs() as $tbljob) {
  //on copy le split et on recupere l'id du nouveau
  $newIdTbljob = $oInfoJob->copyTbljobs($tbljob['id_tbljob']);
  //pour chaque eprouvette de l'ancien split
  foreach ($oInfoJob->getEprouvettes($tbljob['id_tbljob']) as $eprouvette) {
    //on copy l'eprouvette en changeant l'id du nouveau split et du nouveau masterEprouvette
    $oInfoJob->copyEprouvettes($newIdTbljob,$idMasterEprouvette[$eprouvette['id_master_eprouvette']] ,$eprouvette['id_eprouvette']);
  }
}



 ?>
 <br/>
<a href="../index.php?page=updateJob&id_tbljob=<?= $newIdTbljob  ?>">Job copied</a>
