<?php
class StatutModel
{

  protected $db;


  public function __construct($db)
  {
    $this->db = $db;
  }


  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }




  public function getJobFromEp($idEp){
    $req='SELECT id_job FROM eprouvettes WHERE id_eprouvette= '. $this->db->quote($idEp).';';

    return $this->db->getOne($req);
  }

  public function getJobFromInfoJob($idInfoJob){
    $req='SELECT id_tbljob FROM tbljobs WHERE id_info_job= '. $this->db->quote($idInfoJob).';';

    return $this->db->getAll($req);
  }

  public function updateStatut($id_statut){
    $reqUpdate='UPDATE `tbljobs`
    LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
    SET tbljobs.id_statut = '.$id_statut.'
    WHERE statuts.statut_lock=0
    AND tbljobs.id_tbljob = '.$this->id_tbljob.';';
    $result = $this->db->query($reqUpdate);

    $newStatut = $this->db->getOne('SELECT

 statuts.id_statut,	statut,	statut_lock, etape,	statut_color,	statut_actif
       FROM tbljobs
       LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
      WHERE id_tbljob='.$this->id_tbljob);
    $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_statut' => $newStatut['id_statut'], 'statut_color' => $newStatut['statut_color'], 'statut' => $newStatut['statut']);
  //  echo json_encode($maReponse);
  }



  public function findStatut(){
    $req='SELECT
      sum(if(master_eprouvette_inOut_A is null,0,1)) as nbInOut_A,
      if(checked>0,1,0) as checked,

      count(distinct master_eprouvettes.id_master_eprouvette) as nbMasterEp,
      count(master_eprouvettes.id_master_eprouvette) as nbEp,
      count(eprouvettes.id_eprouvette) as nbTestPlanned,

      count(eprouvettes.id_eprouvette) - if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbTestLeft,

      sum(if(c_type_1_val is null OR c_type_2_val is null OR n_fichier is not null,0,1)) as nbConsLeft,
      sum(if(c_type_1_val is null OR c_type_2_val is null OR n_fichier is not null OR master_eprouvette_inOut_A is null,0,1)) as nbConsLeftAndInOut_A,

      sum(if(c_checked>0 AND d_checked <=0,1,0)) as nbConsLeftAux,
      sum(if(c_checked>0 AND d_checked <=0 AND master_eprouvette_inOut_A is not null,1,0)) as nbConsLeftAndInOut_AAux,

      sum(if(master_eprouvette_inOut_A is not null AND
        (SELECT ST FROM eprouvettes ep
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=ep.id_job
          LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
          WHERE ep.id_master_eprouvette= (SELECT id_master_eprouvette FROM eprouvettes epp WHERE epp.id_eprouvette=eprouvettes.id_eprouvette)
          AND ep.eprouvette_inOut_B is null
          AND ep.eprouvette_actif=1
          ORDER BY phase asc
          LIMIT 1)=1,1,0)) as nbSubC,
          sum(if(master_eprouvette_inOut_A is not null AND
            (SELECT local FROM eprouvettes ep
              LEFT JOIN tbljobs tbljoblocal ON tbljoblocal.id_tbljob=ep.id_job
              LEFT JOIN test_type on test_type.id_test_type=tbljoblocal.id_type_essai
              WHERE ep.id_master_eprouvette= (SELECT id_master_eprouvette FROM eprouvettes epp WHERE epp.id_eprouvette=eprouvettes.id_eprouvette)
              AND ep.eprouvette_inOut_B is null
              AND ep.eprouvette_actif=1
              AND tbljoblocal.phase<tbljobs.phase
              ORDER BY tbljoblocal.phase asc
              LIMIT 1)=1
              ,1,0)) as nbLocal,


        sum(if(n_fichier is not null and check_rupture <=0,1,0)) as running,
        sum(if(c_type_1_val is null OR c_type_2_val is null,0,1)) as nbCons,
        if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbtest,
        sum(if(d_checked>0,0,1)) as nbUnDChecked

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job

      WHERE tbljobs.id_tbljob= '. $this->id_tbljob.'
      AND master_eprouvette_actif=1
      AND eprouvette_actif=1
      GROUP BY tbljobs.id_tbljob
    ';

    //echo $req;

    $state = $this->db->getOne($req);


    $statut='';
    $id_statut=0;

    if ($state['nbInOut_A']<$state['nbMasterEp']) {
      $statut='awaiting specimen';
      $id_statut=120;
    }

    if ($state['nbLocal'] > 0) {
      $statut='awaiting InHouse';
      $id_statut=130;
    }
    elseif ($state['nbSubC'] > 0) {
      $statut='awaiting SubC';
      $id_statut=122;
    }
    elseif ($state['checked']==1 AND $state['nbConsLeftAndInOut_A']>1 AND $state['nbTestLeft']>0) {
      $statut='ready to test';
      $id_statut=140;
    }
    elseif ($state['nbConsLeft']==0 AND $state['nbTestLeft']>0 AND $state['nbInOut_A']>=$state['nbMasterEp']) {
      $statut='inHouse but need condition';
      $id_statut=152;
    }


    if ($state['nbConsLeftAux']>0 AND $state['nbTestLeft']>0 AND $state['nbInOut_A']>=$state['nbTestLeft']) {
      $statut='Ready to test Aux';
      $id_statut=140;
    }
    elseif ($state['nbConsLeft']==0 AND $state['nbTestLeft']>0 AND $state['nbConsLeftAndInOut_A']>0) {
      $statut='Testing on Hold Condition';
      $id_statut=152;
    }


    if ($state['running']>=1) {
      if ($state['nbTestLeft']==0) {
        $statut='running last spec';
        $id_statut=154;
      }
      elseif ($state['nbConsLeft']==0) {
        $statut='running last cond';
        $id_statut=151;
      }
      else {
        $statut='running';
        $id_statut=150;
      }
    }
    else {
      if ($state['nbUnDChecked']==0 AND $state['nbTestLeft']==0) {
        $statut='Emission rapport';
        $id_statut=180;
      }
      elseif ($state['nbUnDChecked']>0 AND $state['nbTestLeft']==0) {
        $statut='Emission rapport mais check ep demandé';
        $id_statut=180;
      }
    }

    $this->updateStatut($id_statut);

  }

  public function findStatut2(){
    $req='SELECT
        if(checked>0,1,0) as OT_checked,
        sum(if(master_eprouvette_inOut_A is null,1,0)) as nb_awaiting_specimen,

        sum(if((eprouvette_inOut_A is not null or enregistrementessais.date is not null) and
               (eprouvette_inOut_B is null and report_creation_date is null and d_checked <=0)
               ,1,0)) as nbrunning,

        sum(if((eprouvette_inOut_B is not null or report_creation_date is not null or d_checked >0),0,1)) as nb_before_end,
        sum(if((eprouvette_inOut_A is null AND enregistrementessais.date is null),1,0)) as nb_untested,

        sum(if(d_checked<=0,1,0)) as nb_unDchecked,

        sum( IFNULL(
            (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
              FROM eprouvettes ep
              LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
              WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
                AND tbl.phase<tbljobs.phase
              ORDER BY phase asc
              LIMIT 1
          ),0)
        ) as nb_awaiting_previous_split,

        sum(
        if((master_eprouvette_inOut_A is null OR
          IFNULL(
            (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
              FROM eprouvettes ep
              LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
              WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
                AND tbl.phase<tbljobs.phase
              ORDER BY phase asc
              LIMIT 1
          ),0) =1 )  OR (eprouvette_inOut_A is not null or enregistrementessais.date is not null)
            ,0,1)
        ) as nb_ep_dispo,

        sum(
          if(((master_eprouvette_inOut_A is null OR
            IFNULL(
              (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
                FROM eprouvettes ep
                LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
                WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
                  AND tbl.phase<tbljobs.phase
                ORDER BY phase asc
                LIMIT 1
            ),0) =1 )  OR (eprouvette_inOut_A is not null or enregistrementessais.date is not null)) OR c_checked<=0
              ,0,1)
          ) as nb_consigne_dispo  ,

        sum(
        if((master_eprouvette_inOut_A is null OR
          IFNULL(
            (SELECT if((ep.eprouvette_inOut_B is not null or ep.report_creation_date is not null or ep.d_checked >0),0,1)
              FROM eprouvettes ep
              LEFT JOIN tbljobs tbl ON tbl.id_tbljob=ep.id_job
              WHERE ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
                AND tbl.phase<tbljobs.phase
              ORDER BY phase asc
              LIMIT 1
          ),0) =1 )  OR c_checked>0
            ,0,1)
        ) as nb_ep_dispo_sans_consigne


      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job

      WHERE tbljobs.id_tbljob= '. $this->id_tbljob.'
      AND master_eprouvette_actif=1
      AND eprouvette_actif=1
      GROUP BY tbljobs.id_tbljob
    ';

    //echo $req;

    $state = $this->db->getOne($req);


    $statut='';
    $id_statut=0;

    if ($state['OT_checked']==0) {
      $id_statut=10;
      $statut='Check OT';
    }
    else {
      if ($state['nbrunning']>0) {  //si une ep en test
        if ($state['nb_untested']==0) { //plus d'ep restant
          $id_statut=52;
          $statut='Last Tests Running';
        }
        else {
          if ($state['nb_consigne_dispo']==0) {  //derniere consigne
            $id_statut=51;
            $statut='Last Condition';
          }
          else {  //running
            $id_statut=50;
            $statut='Running';
          }
        }
      }
      else {  //non running
        if ($state['nb_before_end']==0 and $state['nb_unDchecked']>0) { //manque de check technicien
          $id_statut=59;
          $statut='Waiting data check by technician';
        }
        elseif ($state['nb_before_end']==0) { //plus d'essai a faire
          $id_statut=70;
          $statut='FQC';
        }
        else {  //s'il reste des essais a faire
          if ($state['nb_consigne_dispo']>0) { //ready to start
            $id_statut=40;
            $statut='Ready to Start';
          }
          else {
            if ($state['nb_ep_dispo_sans_consigne']>0) {
              $id_statut=30;
              $statut='Awaiting Consigne';
            }
            elseif ($state['nb_awaiting_specimen']>0) { //attente specimen
              $id_statut=20;
              $statut='Awaiting Raw Specimen (initial)';
            }
            elseif ($state['nb_awaiting_previous_split']>0) { //attente specimen split
              $id_statut=21;
              $statut='Awaiting Specimen (from previous split)';
            }
            else {
            $id_statut=0;
            $statut='ERREUR contact PGO with job number please';
            }
          }
        }
      }
    }



    $maReponse = array('req'=> $req, 'id_statut' => $id_statut, 'statut' => $statut);
    echo json_encode($maReponse);
    //$this->updateStatut($id_statut);

  }

}
