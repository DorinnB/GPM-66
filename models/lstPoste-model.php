<?php
class LstPosteModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllPrestartSplit($id_ep) {
      $req='SELECT max(machines.id_machine) as id_machine, machine, MAX(prestart.id_prestart) as id_prestart
			FROM prestart
      LEFT JOIN postes ON postes.id_poste=prestart.id_poste
			LEFT JOIN machines ON machines.id_machine=postes.id_machine
			WHERE prestart.id_tbljob = (
				SELECT id_job
				FROM eprouvettes
				WHERE id_eprouvette='.$id_ep.'
				)
			GROUP BY machine
			ORDER BY machine ';
//echo $req;
        return $this->db->getAll($req);
    }

    public function getAllPoste() {
      $req='SELECT t1.id_poste, t1.id_machine, machine
								FROM postes t1
								LEFT JOIN machines ON machines.id_machine = t1.id_machine
								WHERE t1.id_poste = (
								SELECT MAX( t2.id_poste )
								FROM postes t2
								WHERE t2.id_machine = t1.id_machine )
								ORDER BY t1.id_machine';

        return $this->db->getAll($req);
    }

    public function getAllMachine() {
      $req='SELECT id_machine, machine
			FROM machines

			WHERE machine_actif=1
			ORDER BY machine ';

        return $this->db->getAll($req);
    }

    public function getLastPoste($id_machine) {
      $req='SELECT id_poste
								FROM postes
								WHERE id_machine = '.$id_machine.'
								ORDER BY id_poste DESC
                LIMIT 1';

        return $this->db->getOne($req);
    }
}
