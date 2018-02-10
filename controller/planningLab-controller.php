<?php
include_once('models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()

$nbJourPlanning=31*6;
$nbAvantNow=5;


// Rendre votre modèle accessible
include 'models/planningLab-model.php';
$oPlanningLab = new PLANNINGLAB($db);



// Rendre votre modèle accessible
include 'models/lstJobs-model.php';
$oJob = new LstJobsModel($db);
$lstJobs=$oJob->getAllFollowup(); //job a faire ou en cours


// Rendre votre modèle accessible
include 'models/eprouvettes-model.php';
include 'models/eprouvette-model.php';

foreach ($lstJobs as $key => $value) {
  $oEprouvettes = new LstEprouvettesModel($db,$value['id_tbljob']);
  $ep=$oEprouvettes->getAllEprouvettes();

  //declaration variable calcul temps essai restant
  $estimatedTimeLeft=0;
  $unestimatedTestLeft=0;
  $shortTest=0;

    for($k=0;$k < count($ep);$k++)	{
      $oEp = new EprouvetteModel($db,$ep[$k]['id_eprouvette']);
      $ep[$k]=$oEp->getTest();

      $cycle_estimeAVG=$oEp->getEstimatedCycle();



        $ep[$k]['Cycle_min_nonAtteint']=(!empty($ep[$k]['Cycle_min']) AND $ep[$k]['Cycle_final']<$ep[$k]['Cycle_min'] AND strtolower($ep[$k]['currentBlock'])=='send' )?'flagMini':'a';

        $ep[$k]['cycle_estimeCSS']=!empty($ep[$k]['cycle_estime'])?$ep[$k]['cycle_estime']:((!empty($cycle_estimeAVG['cycle_estime']) AND $ep[$k]['d_checked']<=0)?'estimated':'');
        $ep[$k]['cycle_estime']=!empty($ep[$k]['cycle_estime'])?$ep[$k]['cycle_estime']:((!empty($cycle_estimeAVG['cycle_estime']) AND $ep[$k]['d_checked']<=0)?$cycle_estimeAVG['cycle_estime']:'');
        $ep[$k]['Cycle_STL']=!empty($ep[$k]['Cycle_STL'])?$ep[$k]['Cycle_STL']:'';
        $ep[$k]['Cycle_final']=!empty($ep[$k]['Cycle_final'])?$ep[$k]['Cycle_final']:'';


        $ep[$k]['dilatation']=!empty($ep[$k]['dilatation'])?($ep[$k]['dilatation']-1)*100:'';

        $ep[$k]['temps_essaisCSS']=(!empty($ep[$k]['temps_essais'] AND is_numeric($ep[$k]['temps_essais'])))?'':'estimated';
        $ep[$k]['temps_essais']=(!empty($ep[$k]['temps_essais'] AND is_numeric($ep[$k]['temps_essais'])))?number_format($ep[$k]['temps_essais'], 1,'.', ' '):$ep[$k]['temps_essais'];

        $ep[$k]['CheckValue_Fracture']=(strpos($ep[$k]['Fracture'], 'OX') !==false OR strpos($ep[$k]['Fracture'], 'IX') !==false OR strpos(strtolower($ep[$k]['Fracture']), 'gage') !==false OR strpos(strtoupper($ep[$k]['Fracture']), 'NR') !==false)?'':'checkValue_actif';




      $EstimatedTestTime=0;
      $modulo=0;

      if ($ep[$k]['Cycle_final']>0) {  //si essai demarré, on calcul le temps estimé restant (mini 0 pour pas mettre un temps negatif). 24h mini. Si l'essai s'arrete dans la journée on considere qu'on n'aura pas le temps de redemarrer
        if ($ep[$k]['cycle_estime']>0 AND $ep[$k]['c_frequence']>0) {
          if ($ep[$k]['c_cycle_STL']=="" OR $ep[$k]['cycle_estime']-$ep[$k]['Cycle_final'] < $ep[$k]['c_cycle_STL']) {
            $EstimatedTestTime=(max($ep[$k]['cycle_estime']-$ep[$k]['Cycle_final'],0)/$ep[$k]['c_frequence'])/3600;
          }
          elseif ($ep[$k]['cycle_estime'] > $ep[$k]['c_cycle_STL'] AND $ep[$k]['c_frequence_STL']>0) {
            $EstimatedTestTime=($ep[$k]['c_cycle_STL']/$ep[$k]['c_frequence']+(max($ep[$k]['cycle_estime']-$ep[$k]['Cycle_final'],0)-$ep[$k]['c_cycle_STL'])/$ep[$k]['c_frequence_STL'])/3600;
          }
          else {
            $unestimatedTestLeft+=1;  //cas particulier ou l'on n'arrive pas a calculer malgré demarrage
          }
        }

        if ($EstimatedTestTime>0) { //minimum 24h restant d'occupation machine
          $modulo=24;
        }



      }
      else {

        if ($ep[$k]['cycle_estime']>0 AND $ep[$k]['c_frequence']>0) {
          if ($ep[$k]['c_cycle_STL']=="" OR $ep[$k]['cycle_estime'] < $ep[$k]['c_cycle_STL']) {

            $EstimatedTestTime=($ep[$k]['cycle_estime']/$ep[$k]['c_frequence'])/3600;
          }
          elseif ($ep[$k]['cycle_estime'] > $ep[$k]['c_cycle_STL'] AND $ep[$k]['c_frequence_STL']>0) {
            $EstimatedTestTime=($ep[$k]['c_cycle_STL']/$ep[$k]['c_frequence']+($ep[$k]['cycle_estime']-$ep[$k]['c_cycle_STL'])/$ep[$k]['c_frequence_STL'])/3600;
          }
          else {
            $unestimatedTestLeft+=1;
          }
        }
        elseif ($ep[$k]['n_fichier']>0) {
          $EstimatedTestTime+=0;
        }
        else {
          $unestimatedTestLeft+=1;
        }


        //calcul estimé du temps "occupation machine" pour chaque essai
        if ($EstimatedTestTime>0) {

          $EstimatedTestTime+=.5; //ajout temps preparation.

          if ($ep[$k]['c_temp']>50) {
            $EstimatedTestTime+=4; //ajout temps chauffage.
          }

          $modulo=$EstimatedTestTime%24;

          if ($modulo<5) { //cumul de l'essai avec le suivant
            if ($shortTest=0) {
              $shortTest=$modulo;
            }
            else {
              $modulo=24-$shortTest;
              $shortTest=0;
            }
          }
          elseif ($modulo<24) {  //mini 24h (entre maintenant et le prochain demarrage)
            $modulo=24;
          }
        }
      }
      $estimatedTimeLeft+=(intval(floor($EstimatedTestTime/24))+$modulo/24)*24;

      //echo intval(floor($EstimatedTestTime/24))+$modulo;
$nb[$value['id_tbljob']]=(ceil($estimatedTimeLeft/24)).(($unestimatedTestLeft==0)?"":'+'.$unestimatedTestLeft.'(?)');


  }
}



// Rendre votre modèle accessible
include 'models/poste-model.php';
$oPoste = new PosteModel($db,0);
$lstFrames=$oPoste->getAllMachine();


//décompose la liste complete des plannings en tableau, par machine, des dates=id_tbljob
foreach ($lstFrames as $frame)  {
  $planningFrames=$oPlanningLab->getAllPlanningFrame($frame['id_machine']);
  foreach ($planningFrames as $key => $value) {
    $planningFrame[$frame['id_machine']][$value['date']]   =   $value['id_tbljob'];
    $planningJob[$frame['id_machine']][$value['date']]   =   substr($value['job'], -2).'-'.$value['split'];
  }
}



$now=date("Y-m-d");

for ($i=-$nbAvantNow; $i < $nbJourPlanning; $i++) {
  $date[$i]=date('Y-m-d', strtotime($now . ' +'.$i.' day'));
  $month[$i]=date('Y-M', strtotime($now . ' +'.$i.' day'));
}


$occurences = array_count_values($month);

?>
