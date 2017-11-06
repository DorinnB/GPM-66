<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()
?>
<?php

// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

// Création d'une instance
$oEprouvette = new EprouvetteModel($db,$_GET['idEp']);
$eprouvette=$oEprouvette->getEprouvette();

$checkTechSplit=$oEprouvette->checkTechSplit($_COOKIE['id_user']);


// Rendre votre modèle accessible
include '../models/lstTech-model.php';

// Création d'une instance
$lstTech = new TechModel($db);


// Rendre votre modèle accessible
include '../models/lstPoste-model.php';
// Création d'une instance
$lstPoste = new LstPosteModel($db);



// Rendre votre modèle accessible
include '../models/document-model.php';

// Création d'une instance
$oDocument = new DocumentModel($db);




//on masque tous les champs
echo'
<script type="text/javascript">
document.getElementById("newTest").style.display = "none";
document.getElementById("prepa").style.display = "none";
document.getElementById("eval").style.display = "none";

document.getElementById("prestart").style.display = "none";


document.getElementById("delete").style.display = "none";
document.getElementById("cancel").style.display = "none";
</script>';

echo '  <script type="text/javascript">';
//affichage des champs utiles
if ($eprouvette['n_fichier']=="") {
  echo'
  document.getElementById("newTest").style.display = "block";
  document.getElementById("newTest").className += " active";
  document.getElementById("1a").className += " active";
  ';
}
elseif ($eprouvette['id_controleur']=="0") {
  echo'
  document.getElementById("prepa").style.display = "block";
  document.getElementById("prepa").className += " active";
  document.getElementById("1b").className += " active";
  ';
}
else {
  echo'
  document.getElementById("eval").style.display = "block";
  document.getElementById("eval").className += " active";
  document.getElementById("1e").className += " active";
  ';

}

if (($eprouvette['currentBlock']=="") & ($eprouvette['n_fichier']!="")) {
  echo'
  document.getElementById("cancel").style.display = "block";
  ';
}
if ($eprouvette['retest']>1) {
  echo'
  document.getElementById("delete").style.display = "block";
  ';
}



echo '</script>';


//Suppression des elements si l'on est pas connecté
if (!isset($_COOKIE['id_user'])) {
  echo'
  <script type="text/javascript">
  document.getElementById("newTest").style.display = "none";
  document.getElementById("1a").style.display = "none";

  document.getElementById("1e").style.display = "none";
  document.getElementById("eval").style.display = "none";

  document.getElementById("logon").style.display = "block";
  document.getElementById("document").style.display = "none";
  document.getElementById("retest").style.display = "none";
  document.getElementById("delete").style.display = "none";
    document.getElementById("cancel").style.display = "none";
  </script>';
}


//choix de l'ione du flag qualité
if ($eprouvette['flag_qualite']>0) {
   $iconeFlagQualite="red";
 }
 elseif ($eprouvette['flag_qualite']<0) {
   $iconeFlagQualite="orange";
 }
 else {
   $iconeFlagQualite="blue";
 }



// Affichage du résultat
include '../views/splitGestionEp-view.php';
