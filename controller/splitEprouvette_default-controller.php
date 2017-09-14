<?php



//Appel du model
$ep=$oEprouvettes->getAllEprouvettes();

// Rendre votre modèle accessible
include '../models/eprouvette-model.php';




// Definition des consignes et unites
if (($split['c_type_1']=="R") OR ($split['c_type_1']=="A")) {
  $split['cons1']=$split['c_type_1'];
}
else {
  $split['cons1']=$split['c_type_1'].' ('.$split['c_unite'].')';
}
if (($split['c_type_2']=="R") OR ($split['c_type_2']=="A")) {
  $split['cons2']=$split['c_type_2'];
}
else {
  $split['cons2']=$split['c_type_2'].' '.$split['c_unite'];
}



//declaration des variables calculées
for($k=0;$k < count($ep);$k++)	{
  $oEp = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
  $ep[$k]=$oEp->getTest();
  $workflow=$oEp->getWorkflow();

  $tempCorrected=$oEp->getTempCorrected();

$ep[$k]['comm']=(isset($workflow['comm']))?$workflow['comm']:"";

$cycleEstimeAVG=$oEp->getEstimatedTime();




  //temporaire pour les cas sans c_checked (mettre defaut 0 ds mysql)
  $ep[$k]['c_checked']=($ep[$k]['c_checked']=="")?0:$ep[$k]['c_checked'];
  //temporaire pour les cas sans flag_qualite (mettre defaut 0 ds mysql)
  $ep[$k]['flag_qualite']=($ep[$k]['flag_qualite']=="")?0:$ep[$k]['flag_qualite'];



  //disponibilite eprouvette
  if ($ep[$k]['d_checked']>0) {
    $ep[$k]['dispo']='6';
  }
  elseif ($ep[$k]['currentBlock']=='Send') {
    $ep[$k]['dispo']='5';
  }
  elseif ($ep[$k]['n_fichier']>0) {
    $ep[$k]['dispo']='4';
  }
  else if (isset($workflow['ST']) & $workflow['ST']>0) {
    $ep[$k]['dispo']='0';
  }
  elseif (isset($workflow['local']) & $workflow['local']>0) {
    $ep[$k]['dispo']='1';
  }
  elseif ($ep[$k]['c_checked']<=0) {
    $ep[$k]['dispo']='2';
  }
  elseif ($ep[$k]['c_checked']>0) {
    $ep[$k]['dispo']='3';
  }
  elseif (isset($ep[$k]['master_eprouvette_inOut_A']) & $ep[$k]['master_eprouvette_inOut_A']>0) {
    $ep[$k]['dispo']='3';
  }

/*
  if ($ep[$k]['c_checked']>0)  {
    if ($ep[$k]['n_fichier']>0) {
      $ep[$k]['dispo']=2;
    }
    else {
      $ep[$k]['dispo']=0;
    }
  }
  else {
    $ep[$k]['dispo']=1;
  }
*/







  if ($k>0 && $format!=$ep[$k]['type']) {
    //pour la 2eme et + eprouvette du split, si le format change
    $dimDenomination=array("ERREUR !","MULTIPLE","FORMAT");
    $nbDim = 3;
  }
  else {
    //on sauvegarde le format d'eprouvette (pour voir s'il change)
    $format=  $ep[$k]['type'];

    $oEp->dimension($ep[$k]['type'], $ep[$k]['dim1'], $ep[$k]['dim2'], $ep[$k]['dim3']);

    $dimDenomination=$oEp->dimDenomination();
    $nbDim = count($dimDenomination);



    $oEp->niveaumaxmin($ep[$k]['c_1_type'], $ep[$k]['c_2_type'],$ep[$k]['c_type_1_val'], $ep[$k]['c_type_2_val']);

    $ep[$k]['max']="";
    $ep[$k]['min']="";
    $maxcell=0;
    //max min control
    if ($ep[$k]['c_unite']=="MPa") {
      if ($oEp->area()>0) {
        $ep[$k]['max']=round($oEp->MAX()*$oEp->area()/1000,3);
        $ep[$k]['min']=round($oEp->MIN()*$oEp->area()/1000,3);
        $maxcell=max(abs($ep[$k]['max']),abs($ep[$k]['min']));
      }
    }
    elseif ($ep[$k]['c_unite']=="kN") {
      $ep[$k]['max']=round($oEp->MAX(),3);
      $ep[$k]['min']=round($oEp->MIN(),3);
      $maxcell=max(abs($ep[$k]['max']),abs($ep[$k]['min']));
    }
    elseif ($ep[$k]['c_unite']=="%") {
      $ep[$k]['max']=round($oEp->MAX(),3);
      $ep[$k]['min']=round($oEp->MIN(),3);
      $maxcell=max(abs($ep[$k]['max']),abs($ep[$k]['min']))*$ep[$k]['young']*$oEp->area()/100;
    }
    else  {
      $ep[$k]['max']="?";
      $ep[$k]['min']="?";
    }

    //check max cell used
    if ($maxcell>85) {
      echo '<script>
      $("#load250").addClass( "load250" );
      </script>';
    }
    elseif ($maxcell>8) {
      echo '<script>
      $("#load100").addClass( "load100" );
      </script>';
    }
    elseif ($maxcell>0) {
      echo '<script>
      $("#load10").addClass( "load10" );
      </script>';
    }



  }



  //Formatage des données
  //!empty($ep[$k]['Nf75'])?number_format($ep[$k]['Nf75'], 0, '.', ' '):""
  $ep[$k]['c_temp']=!empty($ep[$k]['c_temp'])?number_format($ep[$k]['c_temp'], 1,'.', ' '):'';
  $ep[$k]['c_frequence']=!empty($ep[$k]['c_frequence'])?(is_numeric($ep[$k]['c_frequence'])?number_format($ep[$k]['c_frequence'], 1,'.', ' '):$ep[$k]['c_frequence']):'';
  $ep[$k]['c_cycle_STL']=!empty($ep[$k]['c_cycle_STL'])?number_format($ep[$k]['c_cycle_STL'], 0,'.', ' '):'';
  $ep[$k]['c_frequence_STL']=!empty($ep[$k]['c_frequence_STL'])?number_format($ep[$k]['c_frequence_STL'], 1,'.', ' '):'';
//  $ep[$k]['c_type_1_val']=!empty($ep[$k]['c_type_1_val'])?number_format($ep[$k]['c_type_1_val'], 3,'.', ' '):'';
//  $ep[$k]['c_type_2_val']=!empty($ep[$k]['c_type_2_val'])?number_format($ep[$k]['c_type_2_val'], 3,'.', ' '):'';
//  $ep[$k]['max']=(is_numeric($ep[$k]['max']) && !empty($ep[$k]['max']))?number_format($ep[$k]['max'], 3,'.', ' '):'';
//  $ep[$k]['min']=(is_numeric($ep[$k]['min']) && !empty($ep[$k]['min']))?number_format($ep[$k]['min'], 3,'.', ' '):'';

$ep[$k]['Cycle_min_nonAtteint']=(!empty($ep[$k]['Cycle_min']) AND $ep[$k]['Cycle_final']<$ep[$k]['Cycle_min'] AND strtolower($ep[$k]['currentBlock'])=='send' )?'flagMini':'a';
//$ep[$k]['Cycle_min_nonAtteint']=(!empty($ep[$k]['Cycle_min']) AND $ep[$k]['Cycle_final']<$ep[$k]['Cycle_min'] AND $ep[$k]['d_checked']>0 )?'flagMini':'a';
  $ep[$k]['Cycle_min']=!empty($ep[$k]['Cycle_min'])?number_format($ep[$k]['Cycle_min'], 0,'.', ' '):'';
  $ep[$k]['runout']=!empty($ep[$k]['runout'])?number_format($ep[$k]['runout'], 0,'.', ' '):'';


  $ep[$k]['cycle_estime']=!empty($ep[$k]['cycle_estime'])?number_format($ep[$k]['cycle_estime'], 0,'.', ' '):(!empty($cycleEstimeAVG['cycle_estime'])?'<i style="font-size : 75%;">'.number_format($cycleEstimeAVG['cycle_estime'], 0,'.', ' ').'</i>':'');


  $ep[$k]['dim1'] =!empty($ep[$k]['dim1'] )?number_format($ep[$k]['dim1'] , 3,'.', ' '):'';
  $ep[$k]['dim2'] =!empty($ep[$k]['dim2'] )?number_format($ep[$k]['dim2'] , 3,'.', ' '):'';
  $ep[$k]['dim3'] =!empty($ep[$k]['dim3'] )?number_format($ep[$k]['dim3'] , 3,'.', ' '):'';
  $ep[$k]['Cycle_STL']=!empty($ep[$k]['Cycle_STL'])?number_format($ep[$k]['Cycle_STL'], 0,'.', ' '):'';
  $ep[$k]['Cycle_final']=!empty($ep[$k]['Cycle_final'])?number_format($ep[$k]['Cycle_final'], 0,'.', ' '):'';
  $ep[$k]['dilatation']=!empty($ep[$k]['dilatation'])?number_format(($ep[$k]['dilatation']-1)*100, 3,'.', ' '):'';
  $ep[$k]['E_RT']=!empty($ep[$k]['E_RT'])?number_format($ep[$k]['E_RT'], 1,'.', ' '):'';
  $ep[$k]['c1_E_montant']=!empty($ep[$k]['c1_E_montant'])?number_format($ep[$k]['c1_E_montant'], 1,'.', ' '):'';
  $ep[$k]['c1_max_strain']=!empty($ep[$k]['c1_max_strain'])?number_format($ep[$k]['c1_max_strain'], 2,'.', ' '):'';
  $ep[$k]['c1_min_strain']=!empty($ep[$k]['c1_min_strain'])?number_format($ep[$k]['c1_min_strain'], 2,'.', ' '):'';
  $ep[$k]['c1_max_stress']=!empty($ep[$k]['c1_max_stress'])?number_format($ep[$k]['c1_max_stress'], 1,'.', ' '):'';
  $ep[$k]['c1_min_stress']=!empty($ep[$k]['c1_min_stress'])?number_format($ep[$k]['c1_min_stress'], 1,'.', ' '):'';
  $ep[$k]['c2_cycle']=!empty($ep[$k]['c2_cycle'])?number_format($ep[$k]['c2_cycle'], 0,'.', ' '):'';
  $ep[$k]['c2_E_montant']=!empty($ep[$k]['c2_E_montant'])?number_format($ep[$k]['c2_E_montant'], 1,'.', ' '):'';
  $ep[$k]['c2_max_stress']=!empty($ep[$k]['c2_max_stress'])?number_format($ep[$k]['c2_max_stress'], 1,'.', ' '):'';
  $ep[$k]['c2_min_stress']=!empty($ep[$k]['c2_min_stress'])?number_format($ep[$k]['c2_min_stress'], 1,'.', ' '):'';

//  $ep[$k]['c2_delta_strain']=!empty($ep[$k]['c2_meas_inelastic_strain'])?number_format($ep[$k]['c2_meas_inelastic_strain'], 2,'.', ' '):'';
//  $ep[$k]['c2_strain_e']=!empty($ep[$k]['c2_meas_inelastic_strain'])?number_format($ep[$k]['c2_meas_inelastic_strain'], 2,'.', ' '):'';
  $ep[$k]['c2_delta_strain']=!empty($ep[$k]['c2_max_strain'])?number_format($ep[$k]['c2_max_strain'], 2,'.', ' '):'';
  $ep[$k]['c2_strain_e']=!empty($ep[$k]['c2_min_strain'])?number_format($ep[$k]['c2_min_strain'], 2,'.', ' '):'';



  $ep[$k]['c2_calc_inelastic_strain']=!empty($ep[$k]['c2_calc_inelastic_strain'])?number_format($ep[$k]['c2_calc_inelastic_strain'], 2,'.', ' '):'';
  $ep[$k]['c2_meas_inelastic_strain']=!empty($ep[$k]['c2_meas_inelastic_strain'])?number_format($ep[$k]['c2_meas_inelastic_strain'], 2,'.', ' '):'';
  $ep[$k]['Ni']=!empty($ep[$k]['Ni'])?number_format($ep[$k]['Ni'], 0,'.', ' '):'';
  $ep[$k]['Nf75']=!empty($ep[$k]['Nf75'])?number_format($ep[$k]['Nf75'], 0,'.', ' '):'';

  $ep[$k]['temps_essais']=(!empty($ep[$k]['temps_essais'] AND is_numeric($ep[$k]['temps_essais'])))?number_format($ep[$k]['temps_essais'], 1,'.', ' '):$ep[$k]['temps_essais'];

  $ep[$k]['prefixe']=$ep[$k]['prefixe'];
  $ep[$k]['nom_eprouvette']=$ep[$k]['nom_eprouvette'];
  $ep[$k]['c_commentaire']=$ep[$k]['c_commentaire'];
  $ep[$k]['c_checked']=$ep[$k]['c_checked'];
  $ep[$k]['flag_qualite']=$ep[$k]['flag_qualite'];
  $ep[$k]['d_commentaire']=$ep[$k]['d_commentaire'];
  $ep[$k]['n_essai']=$ep[$k]['n_essai'];
  $ep[$k]['n_fichier']=$ep[$k]['n_fichier'];
  $ep[$k]['machine']=$ep[$k]['machine'];
  $ep[$k]['date']=$ep[$k]['date'];
  $ep[$k]['waveform']=$ep[$k]['waveform'];
  $ep[$k]['Rupture']=$ep[$k]['Rupture'];
  $ep[$k]['Fracture']=$ep[$k]['Fracture'];
  $ep[$k]['CheckValue_Fracture']=(strpos($ep[$k]['Fracture'], 'OX') !==false OR strpos($ep[$k]['Fracture'], 'IX') !==false OR strpos(strtolower($ep[$k]['Fracture']), 'gage') !==false OR strpos(strtoupper($ep[$k]['Fracture']), 'NR') !==false)?'':'checkValue_actif';



  if ($ep[$k]['c_unite']=='MPa') {
    $ep[$k]['c_type_1_val']=!empty($ep[$k]['c_type_1_val'])?number_format($ep[$k]['c_type_1_val'], 2,'.', ' '):'';
    $ep[$k]['c_type_2_val']=!empty($ep[$k]['c_type_2_val'])?number_format($ep[$k]['c_type_2_val'], 2,'.', ' '):'';
    $ep[$k]['max']=(!empty($ep[$k]['max']) || ($ep[$k]['max']=="0"))?number_format($ep[$k]['max'], 2,'.', ' '):'';
    $ep[$k]['min']=(!empty($ep[$k]['min']) || ($ep[$k]['min']=="0"))?number_format($ep[$k]['min'], 2,'.', ' '):'';
  }
  elseif ($ep[$k]['c_unite']=='kN') {
    $ep[$k]['c_type_1_val']=!empty($ep[$k]['c_type_1_val'])?number_format($ep[$k]['c_type_1_val'], 3,'.', ' '):'';
    $ep[$k]['c_type_2_val']=!empty($ep[$k]['c_type_2_val'])?number_format($ep[$k]['c_type_2_val'], 3,'.', ' '):'';
    $ep[$k]['max']=(!empty($ep[$k]['max']) || ($ep[$k]['max']=="0"))?number_format($ep[$k]['max'], 2,'.', ' '):'';
    $ep[$k]['min']=(!empty($ep[$k]['min']) || ($ep[$k]['min']=="0"))?number_format($ep[$k]['min'], 2,'.', ' '):'';
  }
  elseif ($ep[$k]['c_unite']=='%') {
    $ep[$k]['c_type_1_val']=!empty($ep[$k]['c_type_1_val'])?number_format($ep[$k]['c_type_1_val'], 3,'.', ' '):'';
    $ep[$k]['c_type_2_val']=!empty($ep[$k]['c_type_2_val'])?number_format($ep[$k]['c_type_2_val'], 3,'.', ' '):'';
    $ep[$k]['max']=(!empty($ep[$k]['max']) || ($ep[$k]['max']=="0"))?number_format($ep[$k]['max'], 3,'.', ' '):'';
    $ep[$k]['min']=(!empty($ep[$k]['min']) || ($ep[$k]['min']=="0"))?number_format($ep[$k]['min'], 3,'.', ' '):'';
  }



//var_dump($ep[$k]);
}




/*
//Changement de la page chargé selon le menu choisi
$eprouvetteConsigne=($_GET['modif']=="eprouvetteConsigne")?"Consigne":"";
$eprouvetteValue=($_GET['modif']=="eprouvetteValue")?"Value":"";

// Affichage du résultat selon le type de test
$filename = '../views/splitEprouvette'.$eprouvetteConsigne.$eprouvetteValue.'_'.$split['test_type_abbr'].'-view.php';

if (file_exists($filename)) {
  include $filename;
} else {
  include '../views/splitEprouvette'.$eprouvetteConsigne.$eprouvetteValue.'_default-view.php';
}
*/
include $splitEp_View;
