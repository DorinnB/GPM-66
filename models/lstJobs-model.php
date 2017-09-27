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
				  LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
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
        IF((SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) is null,
          available_expected,
          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1)
          ) AS available';
        $limit='LIMIT 1000';
      }



		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color,
          statut, max(entreprise_abbr) as entreprise_abbr, max(entreprise) as entreprise,
          po_number, instruction,
          customer, job, split,
					test_type_abbr, final,
          etape, matiere,
          GROUP_CONCAT(DISTINCT(dessin) SEPARATOR " ") as dessin,
          GROUP_CONCAT(DISTINCT(c_temperature) SEPARATOR " ") as temperature,
          DyT_expected, DyT_Cust,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep, count(DISTINCT(n_fichier)) as nbtest,
          count(eprouvettes.id_master_eprouvette)-count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbRetest,
          CONVERT((count(DISTINCT(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent
          '.$DyT.'

				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
          LEFT JOIN entreprises ON info_jobs.customer=entreprises.id_entreprise
          LEFT JOIN matieres ON matieres.id_matiere=info_jobs.id_matiere_std
          LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
          LEFT JOIN dessins ON dessins.id_dessin=master_eprouvettes.id_dwg

				WHERE tbljob_actif=1 AND eprouvette_actif=1
        '.$reqfiltre.'
        GROUP BY tbljobs.id_tbljob
				ORDER BY id_statut ASC, job DESC, split ASC
        '.$limit;
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
				  LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
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
				  LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
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
				  LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
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
