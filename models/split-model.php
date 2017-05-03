<?php
class LstSplitModel
{

protected $db;
private $id;


  public function __construct($db,$id)
  {
      $this->db = $db;
      $this->id = $id;
  }


  public function __set($property,$value) {
    if (is_numeric($value)){
      $this->$property = $value;
    }
    else {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
    }
  }




    public function getSplit() {
//ADD AFFICHAGE DESSIN en concat
		$req = 'SELECT id_tbljob,
          customer, job, split, po_number, devis, info_jobs.instruction as info_jobs_instruction, info_jobs.commentaire as info_jobs_commentaire,
          contacts.genre, contacts.lastname, contacts.surname, contacts.compagnie,
          contacts2.genre as genre2, contacts2.lastname as lastname2, contacts2.surname as surname2, contacts2.compagnie as compagnie2,
          contacts3.genre as genre3, contacts3.lastname as lastname3, contacts3.surname as surname3, contacts3.compagnie as compagnie3,
          contacts4.genre as genre4, contacts4.lastname as lastname4, contacts4.surname as surname4, contacts4.compagnie as compagnie4,
          tbljob_commentaire, tbljob_instruction, tbljob_commentaire_qualite,
          tbljobs.id_statut, statut, etape, statut_color, test_type_abbr,
          specification, ref_matiere, matiere, tbljobs.waveform, GROUP_CONCAT(DISTINCT dessin SEPARATOR " ") as dessin, GROUP_CONCAT(DISTINCT master_eprouvettes.id_dwg SEPARATOR " ") as id_dessin,
          type1.consigne_type as c_type_1, type2.consigne_type as c_type_2, c_unite,
          type1.id_consigne_type as id_c_type_1, type2.id_consigne_type as id_c_type_2,
          test_leadtime, checked, comments, contacts.adresse

				FROM tbljobs
				LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg
        LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
        LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
        LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
        LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
        LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
        LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std
        LEFT JOIN consigne_types as type1 ON type1.id_consigne_type=tbljobs.c_1
        LEFT JOIN consigne_types as type2 ON type2.id_consigne_type=tbljobs.c_2

				WHERE id_tbljob='.$this->id.'
        AND eprouvette_actif=1
        AND master_eprouvette_actif=1
        GROUP BY id_tbljob';
//echo $req;
        return $this->db->getOne($req);
    }

    public function getEprouvettes() {

		$req = 'SELECT id_tbljob,
          GROUP_CONCAT( DISTINCT round(c_temperature,0) ORDER BY c_temperature DESC SEPARATOR \' / \') as temperature,
          COUNT(eprouvettes.id_eprouvette) as nbep

				FROM eprouvettes
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere
				WHERE id_tbljob='.$this->id.'
          AND eprouvette_actif=1
          AND master_eprouvette_actif=1
        GROUP BY id_tbljob';
//echo $req;
        return $this->db->getOne($req);
    }

    public function updateStatut($id_statut){
      $reqUpdate='UPDATE `tbljobs` SET `id_statut` = '.$id_statut.' WHERE `tbljobs`.`id_tbljob` = '.$this->id.';';
      $result = $this->db->execute($reqUpdate);

      $newStatut = $this->db->getOne('SELECT * FROM statuts WHERE id_statut='.$id_statut);
      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_statut' => $newStatut['id_statut'], 'statut_color' => $newStatut['statut_color'], 'statut' => $newStatut['statut']);
      			echo json_encode($maReponse);
    }


    public function updateData(){
      $reqUpdate='UPDATE `tbljobs` SET
        `specification` = '.$this->specification.',
        `waveform` = '.$this->waveform.',
        `c_1` = '.$this->c_type_1.',
        `c_2` = '.$this->c_type_2.',
        `c_unite` = '.$this->c_unite.',
        `test_leadtime` = '.$this->test_leadtime.',
        `tbljob_instruction` = '.$this->tbljob_instruction.',
        `comments` = '.$this->comments.',
        `checked` = 0,
        `modif` = '.$_COOKIE['id_user'].'
       WHERE `tbljobs`.`id_tbljob` = '.$this->id.';';
//echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
      			echo json_encode($maReponse);
    }

    public function updateCommentaire(){
      $reqUpdate='UPDATE `tbljobs` SET
        `tbljob_commentaire` = '.$this->tbljob_commentaire.'
       WHERE `tbljobs`.`id_tbljob` = '.$this->id.';';
//echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
      			echo json_encode($maReponse);
    }

    public function updateCommentaireQuality(){
      $reqUpdate='UPDATE `tbljobs` SET
        `tbljob_commentaire_qualite` = '.$this->tbljob_commentaire_qualite.'
       WHERE `tbljobs`.`id_tbljob` = '.$this->id.';';
//echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
      			echo json_encode($maReponse);
    }

    public function updateCheck(){
      $reqUpdate='UPDATE `tbljobs` SET
        `checked` = '.$_COOKIE['id_user'].'
       WHERE `tbljobs`.`id_tbljob` = '.$this->id.';';
//echo $reqUpdate;
      $result = $this->db->query($reqUpdate);

      $maReponse = array('result' => 'ok', 'req'=> $reqUpdate, 'id_tbljob' => $this->id);
      			echo json_encode($maReponse);
    }

    public function newSplit(){
      $reqInsert='INSERT INTO `tbljobs` (id_info_job, id_type_essai) VALUES ('.$this->id_info_job.','.$this->id_test_type.');';
      echo $reqInsert;
      $this->db->execute($reqInsert);
      return $this->db->lastId();
    }


    public function updateSplitNumber($phase){
      $reqUpdate='UPDATE `tbljobs` SET `phase` = '.$phase.', `split` = '.$this->splitNumber.' WHERE `tbljobs`.`id_tbljob` = '.$this->id.';';
echo $reqUpdate;
      $this->db->query($reqUpdate);

    }

}
