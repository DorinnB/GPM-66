<?php



//Appel du model
$ep=$oEprouvettes->getAllEprouvettes();


include '../models/eprouvette-model.php';



// Rendre votre modèle accessible
include '../models/annexe_IQC-model.php';


$oEp = new AnnexeIQCModel($db);
$ep=$oEp->getAllIQC($split['id_tbljob']);



//declaration des variables calculées
for($k=0;$k < count($ep);$k++)	{
  $oEp = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $workflow=$oEp->getWorkflow();





  //temporaire pour les cas sans c_checked (mettre defaut 0 ds mysql)
  $ep[$k]['c_checked']=($ep[$k]['c_checked']=="")?0:$ep[$k]['c_checked'];
  //temporaire pour les cas sans flag_qualite (mettre defaut 0 ds mysql)
  $ep[$k]['flag_qualite']=($ep[$k]['flag_qualite']=="")?0:$ep[$k]['flag_qualite'];





$ep[$k]['dispo']='0';
    //disponibilite eprouvette
    if ($ep[$k]['d_checked']>0) {
      $ep[$k]['dispo']='6';
    }
    elseif (isset($workflow['ST']) & $workflow['ST']>0) {
      $ep[$k]['dispo']='0';
    }
    elseif (isset($workflow['local']) & $workflow['local']>0) {
      $ep[$k]['dispo']='1';
    }
    elseif (isset($ep[$k]['master_eprouvette_inOut_A']) & $ep[$k]['master_eprouvette_inOut_A']>0) {
      $ep[$k]['dispo']='3';
    }





  if ($k>0 && $format!=$ep[$k]['type']) {
    //pour la 2eme et + eprouvette du split, si le format change
    $dimDenomination=array("ERREUR !","MULTIPLE","FORMAT");
    $nbDim = 3;
  }
  else {
    //on sauvegarde le format d'eprouvette (pour voir s'il change)
    $format=  $ep[$k]['type'];


    $oEprouvette = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
    $oEprouvette->dimension($ep[$k]['type'], $ep[$k]['dim1'], $ep[$k]['dim2'], $ep[$k]['dim3']);
    $dimDenomination=$oEprouvette->dimDenomination();
    $nbDim = count($dimDenomination);







  }



  //Formatage des données
  $ep[$k]['dim1'] =!empty($ep[$k]['dim1'] )?number_format($ep[$k]['dim1'] , 3,'.', ' '):'';
  $ep[$k]['dim2'] =!empty($ep[$k]['dim2'] )?number_format($ep[$k]['dim2'] , 3,'.', ' '):'';
  $ep[$k]['dim3'] =!empty($ep[$k]['dim3'] )?number_format($ep[$k]['dim3'] , 3,'.', ' '):'';






}





//Changement de la page chargé selon le menu choisi
include $splitEp_View;
