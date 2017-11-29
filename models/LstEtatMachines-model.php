<?php
class LstEtatMachines
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllEtatMachines() {
      $req='SELECT * FROM etatmachine_machines
        LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
        LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
        LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
        LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp

        ORDER BY id_etatmachine_machine;';
      return $this->db->getAll($req);
    }

    public function getAllEtatMachines_machine($filtre="Frame") {

      if ($filtre=="Year") {
        $filtered='YEAR(periode)';
      }
      if ($filtre=="Month") {
        $filtered='MONTH(periode)';
      }
      else {
        $filtered='machine';
      }


      $req="SELECT
        max(machine) as machine,
        sum(cumul)/60 as cumul,
        SUM(if(etatmachine in ('Load','Strain','Dwell','Not','Fluage','Switchable'),cumul,0))/60 as cycling,
        SUM(if(etatmachine in ('Ramp'),cumul,0))/60 as rampToTemp,
        SUM(if(etatmachine is null or etatmachine in ( 'Init','Menu','Parameters','Adv.','Check','Amb.','ET','STL','Stop','Straightening','Report','Analysis'),cumul,0))/60 as noncycling,
        SUM(if(etatmachine in ('Send'),cumul,0))/60 as send,
        SUM(if(etatmachine='Send' AND (etape>=59 OR etape=52),cumul,0))/60 as noTest,
        SUM(if(etatmachine='Send' AND etape in (30,47),cumul,0))/60 as waitingCustomer,
        SUM(if(etatmachine='Send' AND etape in (40,46,48,50,51),cumul,0))/60 as waitingLab,

        max(periode) as periode

        FROM etatmachine_machines
          LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
          LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
          LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
          LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp

        group by ".$filtered." ;";
      return $this->db->getAll($req);
    }

    public function getAllEtatMachines_split($id_tbljob) {
      $req="SELECT
          machine,
          sum(cumul)/60 as cumul,
          SUM(if(etatmachine in ('Load','Strain','Dwell','Not','Fluage'),cumul,0))/60 as cycling,
          SUM(if(etatmachine in ('Ramp'),cumul,0))/60 as rampToTemp,
          SUM(if(etatmachine is null or etatmachine in ( 'Init','Menu','Parameters','Adv.','Check','Amb.','ET','Switchable','STL','Stop','Straightening','Report','Analysis'),cumul,0))/60 as noncycling,
          SUM(if(etatmachine in ('Send'),cumul,0))/60 as send,
          SUM(if(etatmachine='Send' AND (etape>=59 OR etape=52),cumul,0))/60 as noTest,
          SUM(if(etatmachine='Send' AND etape in (30,47),cumul,0))/60 as waitingCustomer,
          SUM(if(etatmachine='Send' AND etape in (40,46,48,50,51),cumul,0))/60 as waitingLab,

          max(periode) as periode

        FROM etatmachine_machines
          LEFT JOIN machines on machines.id_machine=etatmachine_machines.id_machine
          LEFT JOIN tbljobs on tbljobs.id_tbljob=etatmachine_machines.id_tbljob
          LEFT JOIN test_type on test_type.id_test_type=tbljobs.id_type_essai
          LEFT JOIN statuts on statuts.id_statut=etatmachine_machines.id_statut_temp
        WHERE id_job='.$this->bd->quote($id_tbljob).'
        group by id_tbljob;";
      return $this->db->getAll($req);
    }

}
