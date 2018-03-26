<?php
class InvoiceModel
{
  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __set($property,$value) {
    $this->$property = ($value=="")? "NULL" : $this->db->quote($value);
  }

  public function getAllInvoiceList($id_tbljob="null") {

    if ($id_tbljob=="null") {
      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, prodCode, OpnCode, USD, EURO, temperature, pricingList_actif, id_test_type
      FROM `pricinglists`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_pricinglist=pricinglists.id_pricinglist
      WHERE id_test_type=0';
    }
    else {
      $req='SELECT pricinglists.id_pricingList, pricingList, pricingListFR, prodCode, OpnCode, USD, EURO, temperature, pricingList_actif
      FROM `tbljobs`
      INNER JOIN test_type_pricinglists ON test_type_pricinglists.id_test_type=tbljobs.id_type_essai
      INNER JOIN pricinglists ON pricinglists.id_pricingList=test_type_pricinglists.id_pricingList
      WHERE id_tbljob='.$id_tbljob;
    }


    return $this->db->getAll($req);
  }

  public function getInvoiceListSplit($id_tbljob) {

    //calcul du nombre d'essai ou du temps d'heures sup (arrondi superieur)
    $req='SELECT
    pricinglists.id_pricingList,
    id_invoiceline,
    id_info_job,
    id_tbljob,
    prodCode,
    OpnCode,
    invoicelines.pricingList,
    qteUser,
    if(type=1,
      count(n_fichier),
      if(type=2,
        sum(
          ceil(
            if(
              if(temps_essais is null,
                if(Cycle_final >0 AND c_frequence is not null and c_frequence !=0,
                  if(Cycle_STL is null and c_cycle_STL is null,
                    eprouvettes.Cycle_final/eprouvettes.c_frequence/3600,
                    if(Cycle_STL is null,
                      if(eprouvettes.Cycle_final>c_cycle_STL,(c_cycle_STL/c_frequence+(eprouvettes.Cycle_final-c_cycle_STL)/c_frequence_STL)/3600,
                      (eprouvettes.Cycle_final/c_frequence)/3600
                    )
                    ,if(eprouvettes.Cycle_final>cycle_STL,
                      (cycle_STL/c_frequence+(eprouvettes.Cycle_final-cycle_STL)/c_frequence_STL)/3600,
                      (eprouvettes.Cycle_final/c_frequence)/3600
                    )
                  )
                )
                ,
                ""
              )
              ,temps_essais
            )>24,
            if(temps_essais is null,
              if(Cycle_final >0 AND c_frequence is not null and c_frequence !=0,
                if(Cycle_STL is null and c_cycle_STL is null,
                  eprouvettes.Cycle_final/eprouvettes.c_frequence/3600,
                  if(Cycle_STL is null,
                    if(eprouvettes.Cycle_final>c_cycle_STL,
                      (c_cycle_STL/c_frequence+(eprouvettes.Cycle_final-c_cycle_STL)/c_frequence_STL)/3600,
                      (eprouvettes.Cycle_final/c_frequence)/3600
                    )
                    ,if(eprouvettes.Cycle_final>cycle_STL,
                      (cycle_STL/c_frequence+(eprouvettes.Cycle_final-cycle_STL)/c_frequence_STL)/3600,
                      (eprouvettes.Cycle_final/c_frequence)/3600
                    )
                  )
                ),
                "")
                ,temps_essais
              )-24,
              0
            )
          )
        )
        ,""
      )
    ) as qteGPM,
    priceUnit,
    totalUser


    FROM `invoicelines`
    LEFT JOIN pricinglists ON pricinglists.id_pricingList=invoicelines.id_pricinglist
    LEFT JOIN eprouvettes on eprouvettes.id_job=invoicelines.id_tbljob
    LEFT JOIN enregistrementessais ON enregistrementessais.id_eprouvette=eprouvettes.id_eprouvette
    WHERE id_tbljob='.$id_tbljob.'
    AND valid=1
    GROUP BY id_invoiceline
    ORDER BY pricinglists.id_pricingList
    ';


    //echo $req;
    return $this->db->getAll($req);
  }

  public function getInvoiceListJob($id_tbljob) {

    $req='SELECT
    pricinglists.id_pricingList,
    id_invoiceline,
    id_info_job,
    id_tbljob,
    prodCode,
    OpnCode,
    invoicelines.pricingList,
    qteUser,

    priceUnit,
    totalUser


    FROM `invoicelines`
    LEFT JOIN pricinglists ON pricinglists.id_pricingList=invoicelines.id_pricinglist
    WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->db->quote($id_tbljob).')
    AND id_tbljob IS NULL
    GROUP BY id_invoiceline
    ORDER BY pricinglists.id_pricingList
    ';


    //echo $req;
    return $this->db->getAll($req);
  }

  public function deleteInvoiceLine() {

    $reqDelete = 'DELETE FROM invoicelines
    WHERE id_invoiceline='.$this->id_invoiceLine.';';

    //echo $reqDelete;
    $this->db->execute($reqDelete);
  }

  public function updateInvoiceLine() {

    $reqUpdate = '
    UPDATE invoicelines
    SET  pricingList='.$this->pricingList.',
    qteUser='.$this->qteUser.',
    priceUnit='.$this->priceUnit.'
    WHERE id_invoiceline='.$this->id_invoiceLine.';';

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function addNewEntry() {

    $req = 'INSERT INTO invoicelines
    (id_pricinglist, pricingList, qteUser, priceUnit,  id_info_job, id_tbljob)
    VALUES ('.$this->id_pricingList.', '.$this->pricingList.', '.$this->qteUser.', '.$this->priceUnit.', '.$this->id_info_job.', '.$this->id_tbljob.');';

    //echo $req;
    $this->db->execute($req);
  }

  public function updateInvoiceComments($invoice_lang,$invoice_currency,$comments, $id_tbljob) {

    $reqUpdate = 'UPDATE info_jobs
    SET
    invoice_lang='.$this->db->quote($invoice_lang).',
    invoice_currency='.$this->db->quote($invoice_currency).',
    invoice_commentaire='.$this->db->quote($comments).'
    WHERE id_info_job=(SELECT id_info_job FROM tbljobs WHERE id_tbljob='.$this->db->quote($id_tbljob).');';

    //echo $reqUpdate;
    $this->db->query($reqUpdate);
  }

  public function getAllPayablesJob($id_tbljob) {

    $req='SELECT *
    FROM payables
    WHERE job=(SELECT job FROM tbljobs LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job WHERE id_tbljob='.$this->db->quote($id_tbljob).');';

    return $this->db->getAll($req);
  }
}
