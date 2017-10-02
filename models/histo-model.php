<?php
class HistoModel
{

  protected $db;


  public function __construct($db)
  {
    $this->db = $db;
  }


  public function __set($property,$value) {
      $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }

  public function getHistoEprouvette($id) {

    $req = 'SELECT *
    FROM histo_eprouvettes
    WHERE id_eprouvette='.$this->db->quote($id).'
    ORDER by date_action asc';

    //echo $req;
    return $this->db->getAll($req);
  }

  public function getHistoAnnexeIQC($id) {

    $req = 'SELECT *
    FROM histo_annexe_IQC
    WHERE id_annexe_IQC='.$this->db->quote($id).'
    ORDER by date_action asc';

    //echo $req;
    return $this->db->getAll($req);
  }

}
