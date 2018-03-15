<?php
class InvoiceModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllInvoiceList($id_tbljob="null") {

    if ($id_tbljob=="null") {
      $req='SELECT pricinglists.id_pricingList, pricingList, prodCode, OpnCode, USD, EURO, temperature, pricingList_actif, id_test_type
      FROM `pricinglists`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_pricinglist=pricinglists.id_pricinglist
      WHERE id_test_type=0';
    }
    else {
      $req='SELECT pricinglists.id_pricingList, pricingList, prodCode, OpnCode, USD, EURO, temperature, pricingList_actif
      FROM `tbljobs`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_test_type=tbljobs.id_type_essai
      INNER JOIN pricinglists ON pricinglists.id_pricingList=test_type_pricinglists.id_pricingList
      WHERE id_tbljob='.$id_tbljob;
    }


    return $this->db->getAll($req);
  }


}
