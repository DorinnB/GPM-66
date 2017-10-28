<?php
class LstJobsModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllJobs($filtre="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
          if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbtest,
          CONVERT((if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent,
					IF(tbljobs.DyT_Cust>NOW(),0,1) as delay
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        '.$filtre.'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        LIMIT 1000';
        return $this->db->getAll($req);
    }

    public function getAllFollowup($filtreFollowup="final") {

      if ($filtreFollowup=='ALL') {
        $reqfiltre='AND etape <90';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay,
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }
      elseif ($filtreFollowup=='SubC') {
        $reqfiltre='AND test_type_abbr like ".%" AND etape <90';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay,
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }
      elseif ($filtreFollowup=='ALLNoTime') {
        $reqfiltre='';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay, "" as available';
        $limit='';
      }
      else {
        $reqfiltre='AND final=1 AND etape <90';
        $DyT=', IF(tbljobs.DyT_Cust>NOW(),0,1) as delay,
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase < tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase < tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }



		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color,
          statut, max(entreprises.entreprise_abbr) as entreprise_abbr, max(entreprises.entreprise) as entreprise, entrepriseST.entreprise_abbr as entreprise_abbrST,
          po_number, instruction,
          customer, job, split,
					test_type_abbr, final,
          etape, matiere, ref_matiere,
          GROUP_CONCAT(DISTINCT(dessin) SEPARATOR " ") as dessin,
          GROUP_CONCAT(DISTINCT(c_temperature) SEPARATOR " ") as temperature,
          DyT_expected, DyT_Cust, DyT_SubC, refSubC,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
          if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbstart,
          SUM(IF(d_checked > 0 OR n_fichier is not null , 1, 0)) as  nbtest,
          SUM(IF(eprouvette_InOut_A IS NOT NULL, 1, 0)) as nbsent,
          count(eprouvettes.id_master_eprouvette)-count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbRetest,
          CONVERT((count(DISTINCT(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent
          '.$DyT.'

				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
          LEFT JOIN entreprises ON info_jobs.customer=entreprises.id_entreprise

          LEFT JOIN contacts contactST ON contactST.id_contact=tbljobs.id_contactST
          LEFT JOIN entreprises entrepriseST ON entrepriseST.id_entreprise=contactST.ref_customer

          LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

				WHERE tbljob_actif=1 AND eprouvette_actif=1
        '.$reqfiltre.'
        GROUP BY tbljobs.id_tbljob
				ORDER BY id_statut ASC, job DESC, split ASC
        '.$limit;
        //echo $req;
        return $this->db->getAll($req);
    }


    public function getAllFollowupJob($filtreFollowup="final") {

      if (is_numeric ($filtreFollowup)) {
        $limit=$filtreFollowup;
      }
      else {
        $limit='100';
      }


    $req = 'SELECT
          min(statuts.id_statut) as id_statut, (select st.statut_color from statuts st where st.id_statut= min(statuts.id_statut)) as statut_color, (select st.statut from statuts st where st.id_statut= min(statuts.id_statut)) as statut,
          max(entreprises.entreprise_abbr) as entreprise_abbr, max(entreprises.entreprise) as entreprise,
          po_number, instruction, commentaire, devis, max(ref_pricing) as ref_pricing ,
          customer, job, min(tbljobs.id_tbljob) as id_tbljob,
          GROUP_CONCAT(DISTINCT(test_type_abbr) SEPARATOR " ") as test_type_abbr,
          matiere, ref_matiere,available_expected,
          GROUP_CONCAT(DISTINCT(dessin) SEPARATOR "<br />") as dessin,
          max(DyT_expected), max(DyT_Cust) as DyT_Cust, max(DyT_SubC),
          count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep,
          if(count(n_fichier)=0, sum(if(d_checked > 0,1,0)),count(n_fichier)) as nbstart,
          SUM(IF(d_checked > 0 OR n_fichier is not null , 1, 0)) as  nbtest,
          contacts.id_contact, contacts.lastname, contacts.surname,
          contacts2.id_contact as id_contact2, contacts2.lastname as lastname2, contacts2.surname as surname2,
          contacts3.id_contact as id_contact3, contacts3.lastname as lastname3, contacts3.surname as surname3,
          contacts4.id_contact as id_contact4, contacts4.lastname as lastname4, contacts4.surname as surname4

        FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
          LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
          LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
          LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
          LEFT JOIN entreprises ON info_jobs.customer=entreprises.id_entreprise
          LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact
          LEFT JOIN contacts  contacts2 ON contacts2.id_contact=info_jobs.id_contact2
          LEFT JOIN contacts  contacts3 ON contacts3.id_contact=info_jobs.id_contact3
          LEFT JOIN contacts  contacts4 ON contacts4.id_contact=info_jobs.id_contact4
          LEFT JOIN pricing ON pricing.id_pricing=info_jobs.pricing
          LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

        WHERE tbljob_actif=1 AND eprouvette_actif=1 and info_job_actif=1

        GROUP BY tbljobs.id_info_job
        order by job desc
        LIMIT '.$limit.'
      ';
      //echo $req;
      return $this->db->getAll($req);
    }


    public function searchJob($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1
        AND job LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }

    public function searchPO($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        AND po_number LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }

    public function searchInst($searchInfo="") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer, statuts.etape,
					job, split, test_type_abbr,
          po_number, instruction
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN tbljobs_temp ON tbljobs_temp.id_tbljobs_temp=tbljobs.id_tbljob
          LEFT JOIN statuts ON statuts.id_statut=tbljobs_temp.id_statut_temp
				WHERE tbljob_actif=1 AND eprouvette_actif=1
        AND instruction LIKE '.$this->db->quote('%'.$searchInfo.'%').'
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        ';
        return $this->db->getAll($req);
    }

    public function searchEp($searchInfo="") {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
      n_essai, n_fichier, id_tbljob,
      info_jobs.job, info_jobs.customer, split, test_type, eprouvettes.id_master_eprouvette, id_job,
      po_number, instruction,test_type_abbr

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

      WHERE master_eprouvettes.nom_eprouvette LIKE '.$this->db->quote('%'.$searchInfo.'%').'
       AND tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1';
      //echo $req;
      return $this->db->getALL($req);
    }

    public function searchPrefixe($searchInfo="") {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
      n_essai, n_fichier, id_tbljob,
      info_jobs.job, info_jobs.customer, split, test_type, eprouvettes.id_master_eprouvette, id_job,
      po_number, instruction,test_type_abbr

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

      WHERE master_eprouvettes.prefixe LIKE '.$this->db->quote('%'.$searchInfo.'%').'
       AND tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1';
      //echo $req;
      return $this->db->getALL($req);
    }

    public function searchFile($searchInfo="") {

      $req = 'SELECT eprouvettes.id_eprouvette,
      master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette,
      n_essai, n_fichier, id_tbljob,
      info_jobs.job, info_jobs.customer, split, test_type, eprouvettes.id_master_eprouvette, id_job,
      po_number, instruction,test_type_abbr

      FROM eprouvettes
      LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
      LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
      LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
      LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
      LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai

      WHERE n_fichier = '.$this->db->quote($searchInfo).'
       AND tbljob_actif=1 AND eprouvette_actif=1 AND info_job_actif=1';
      //echo $req;
      return $this->db->getALL($req);
    }
}
