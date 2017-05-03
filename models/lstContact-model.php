<?php
class ContactModel
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllContact() {
      $req='SELECT * FROM contacts where contact_actif=1 ORDER BY surname;';
        return $this->db->getAll($req);
    }

    public function getAllref_customer() {
      $req='SELECT distinct ref_customer FROM contacts where contact_actif=1 ORDER BY ref_customer;';
        return $this->db->getAll($req);
    }

    public function getContact($ref_customer) {
      $req='SELECT * FROM contacts where ref_customer = '.$ref_customer.' AND contact_actif=1 ORDER BY surname;';
        return $this->db->getAll($req);
    }

    public function getClient($ref_customer) {
      $req='SELECT compagnie FROM contacts where ref_customer = '.$ref_customer.' AND contact_actif=1 ORDER BY surname;';

        return $this->db->getOne($req);
    }

}
