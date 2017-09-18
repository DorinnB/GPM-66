<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

// Rendre votre modèle accessible
include_once '../models/lstJobs-model.php';

// Création d'une instance
$oFollowup = new LstJobsModel($db);


$filtreFollowup=(isset($_GET['filtreFollowup']))?$_GET['filtreFollowup']:'';

foreach ($oFollowup->getAllFollowup($filtreFollowup) as $row) {
  if ($row['nbtest']>0 AND $row['etape']<=80) {

    echo "<script>window.open('../controller/createReport-controller.php?id_tbljob=".$row['id_tbljob']."','".$row['id_tbljob']."', 'width=600,height=150,left=100,top=50')</script>";
  }
}
