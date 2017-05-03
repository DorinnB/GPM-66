<?php
class LstJobsModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllJobs($filtre="1", $symbol="=", $value="1") {
		$req = 'SELECT id_tbljob,
					tbljobs.id_statut, statut_color, customer,
					job,
					split,
					test_type_abbr,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep, count(n_fichier) as nbtest, CONVERT((count(n_fichier)/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent,
					IF(tbljobs.test_leadtime>NOW(),0,1) as delay
				FROM eprouvettes
        LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
          LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
				  LEFT JOIN test_type ON test_type.id_test_type=tbljobs.id_type_essai
				  LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
				  LEFT JOIN statuts ON statuts.id_statut=tbljobs.id_statut
				WHERE tbljob_actif=1
        AND '.$filtre.' '.$symbol.' "'.$value.'"
        GROUP BY tbljobs.id_tbljob
				ORDER BY customer=8000 desc, id_statut ASC, job DESC, split ASC
        LIMIT 100';
        return $this->db->getAll($req);
    }
}
