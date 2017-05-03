<?php
class LabModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getTest() {
      $req='select info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, currentBlock, eprouvettes.Cycle_final, split, machine, poste, id_job, n_essai,
        c_frequence, c_frequence_STL, c_cycle_STL, d_frequence, d_frequence_STL, Cycle_STL, runout
        from enregistrementessais
        left join eprouvettes on eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        left join master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
        left join info_jobs on info_jobs.id_info_job=tbljobs.id_info_job
        left join prestart on prestart.id_prestart=enregistrementessais.id_prestart
        left join postes on postes.id_poste=prestart.id_poste
        left join machines on machines.id_machine=postes.id_machine

        where n_fichier in (SELECT max(n_fichier)
        FROM `enregistrementessais`
        left join prestart on prestart.id_prestart=enregistrementessais.id_prestart
        left join postes on postes.id_poste=prestart.id_poste
        group by postes.id_machine)
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }

    public function getCheckList() {
      $req='SELECT info_jobs.customer, info_jobs.job, master_eprouvettes.prefixe, master_eprouvettes.nom_eprouvette, n_fichier, split, machine, poste, id_job,
        machine, t1.technicien as operateur, t2.technicien as controleur, currentBlock, tbljobs.id_tbljob

        FROM enregistrementessais
        left join eprouvettes on eprouvettes.id_eprouvette=enregistrementessais.id_eprouvette
        left join master_eprouvettes on master_eprouvettes.id_master_eprouvette=eprouvettes.id_master_eprouvette
        left join tbljobs on tbljobs.id_tbljob=eprouvettes.id_job
        left join info_jobs on info_jobs.id_info_job=tbljobs.id_info_job
        left join prestart on prestart.id_prestart=enregistrementessais.id_prestart
        left join postes on postes.id_poste=prestart.id_poste
        left join machines on machines.id_machine=postes.id_machine
        LEFT JOIN techniciens t1 ON t1.id_technicien=enregistrementessais.id_operateur
        LEFT JOIN techniciens t2 ON t2.id_technicien=enregistrementessais.id_controleur

        where currentBlock="Check"
        order by machine';
        //echo $req;
        return $this->db->getAll($req);
    }
}
