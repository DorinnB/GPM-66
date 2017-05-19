<?php
class LabModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getTest() {
      $req='SELECT info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, currentBlock, eprouvettes.Cycle_final, split, machine, poste, id_job, n_essai,
        c_frequence, c_frequence_STL, c_cycle_STL, d_frequence, d_frequence_STL, Cycle_STL, runout, c_temperature
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine

        WHERE n_fichier in (SELECT max(n_fichier)
        FROM `enregistrementessais`
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        GROUP BY postes.id_machine)
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getCheckList() {
      $req='SELECT info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, split, machine, poste, id_job,
        machine, t1.technicien as operateur, t2.technicien as controleur, currentBlock, tbljobs.id_tbljob

        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine
        LEFT JOIN techniciens t1 ON t1.id_technicien=enregistrementessais.id_operateur
        LEFT JOIN techniciens t2 ON t2.id_technicien=enregistrementessais.id_controleur

        WHERE currentBlock="Check" OR (Cycle_final>0 AND id_controleur=0)
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getCheckRuptureList() {
      $req='SELECT eprouvettes.id_eprouvette, info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, split, machine, poste, id_job, n_essai
        FROM enregistrementessais
        LEFT JOIN eprouvettes ON eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        LEFT JOIN master_eprouvettes ON master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        LEFT JOIN tbljobs ON tbljobs.id_tbljob=eprouvettes.id_job
        LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
        LEFT JOIN prestart ON prestart.id_prestart=enregistrementessais.id_prestart
        LEFT JOIN postes ON postes.id_poste=prestart.id_poste
        LEFT JOIN machines ON machines.id_machine=postes.id_machine

        WHERE check_rupture=0
          
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }
}
