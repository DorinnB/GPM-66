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

    public function getAllFollowup($filtreFollowup="final") {

      if ($filtreFollowup=='ALL') {
        $reqfiltre='';
      }
      else {
        $reqfiltre='AND final=1 AND etape <95';
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
          test_leadtime,
					count(DISTINCT(eprouvettes.id_master_eprouvette)) as nbep, count(DISTINCT(n_fichier)) as nbtest, CONVERT((count(DISTINCT(n_fichier))/count(DISTINCT(eprouvettes.id_master_eprouvette))*100), SIGNED INTEGER) as nbpercent,
					IF(tbljobs.test_leadtime>NOW(),0,1) as delay,


          min((select test_leadtime from eprouvettes ep
           left join tbljobs tbl on tbl.id_tbljob=ep.id_job
           where ep.id_master_eprouvette=eprouvettes.id_master_eprouvette
           and tbl.phase< tbljobs.phase
               order by phase desc
           limit 1
         )) as previousDyT


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


				WHERE tbljob_actif=1

        '.$reqfiltre.'
        GROUP BY tbljobs.id_tbljob
				ORDER BY id_statut ASC, job DESC, split ASC
        LIMIT 1000';
        return $this->db->getAll($req);
    }


}
