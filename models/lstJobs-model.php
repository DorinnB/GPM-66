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
					IF(tbljobs.test_leadtime>NOW(),0,1) as delay
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
      }
      elseif ($filtreFollowup=='SubC') {
        $reqfiltre='AND test_type_abbr like ".%" AND etape <90';
      }
      elseif ($filtreFollowup=='SuNoTimebC') {
        $reqfiltre='';
      }
      else {
        $reqfiltre='AND final=1 AND etape <90';
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
          DyT_expected, test_leadtime,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep, count(DISTINCT(n_fichier)) as nbtest, CONVERT((count(DISTINCT(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent,
					IF(tbljobs.test_leadtime>NOW(),0,1) as delay,


          (SELECT DyT_expected FROM tbljobs t WHERE t.id_info_job=tbljobs.id_info_job AND t.phase<tbljobs.phase AND DyT_expected IS NOT NULL ORDER BY phase DESC LIMIT 1) AS available


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
        LIMIT 1000';
        return $this->db->getAll($req);
    }


}
