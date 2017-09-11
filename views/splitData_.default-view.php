<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" id="splitData" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData" style="height:99%">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">


    <div class="bs-example designation" data-example-id="basic-forms">
      <p class="title">
        <span class="name">Specification :</span>
        <span class="value"><?= $split['specification'] ?></span>
      </p>
      <p class="title">
        <span class="name">Drawing :</span>
        <span class="value"><?= $split['dessin'] ?></span>
      </p>
      <p class="title">
        <span class="name">Sub Contractor :</span>
        <span class="value"><acronym title="<?= $split['entrepriseST'] ?>"><?= $split['entreprise_abbrST'] ?></acronym></span>
      </p>
      <p class="title">
        <span class="name">Contact :</span>
        <span class="value"><?= $split['lastnameST'].' '.$split['surnameST'] ?></span>
      </p>
    </div>

    <div class="bs-example avancement" data-example-id="basic-forms">
      <p class="title">
        <span class="name">Test Planned :</span>
        <span class="value"><?= $split['nbtest'] ?></span>
      </p>
      <p class="title">
        <span class="name">Specimen Untested </span>
        <span class="value"><?= $split['nbepCheckedleft'] ?></span>
      </p>

      <p class="title">
        <span class="name" id="subCRef">Sub Ref : <span class="glyphicon glyphicon-pencil"></span></span>
        <span class="date" id="refSubC_alt"><?= $split['refSubC'] ?></span>

        <input type="text" class="form-control flip" id="refSubC" name="refSubC" value="<?= $split['refSubC'] ?>">
      </p>
    </div>

    <div class="bs-example planning" data-example-id="basic-forms">
      <p class="title">
        <span class="name">Availability : </span></span>
        <span class="value"><?= $split['available'] ?></span>
      </p>
      <p class="title">
        <span class="name" id="DyT_SubCFlip">DyT SubC : <span class="glyphicon glyphicon-pencil"></span></span>
        <span class="value" id="DyT_SubC_alt"><?= (($split['DyT_SubC']=="")?'Undefined':$split['DyT_SubC']) ?></span>

        <input type="text" class="form-control flip" name="DyT_SubC" id="DyT_SubC" value="<?= $split['DyT_SubC'] ?>">
      </p>
      <p class="title">
        <span class="name" id="DyT_expectedFlip">DyT expected : <span class="glyphicon glyphicon-pencil"></span></span>
        <span class="value" id="DyT_expected_alt"><?= (($split['DyT_expected']=="")?'Undefined':$split['DyT_expected']) ?></span>

        <input type="text" class="form-control flip" name="DyT_expected" id="DyT_expected" value="<?= $split['DyT_expected'] ?>">
      </p>
      <p class="title">
        <span class="name">DyT Cust :</span>
        <span class="value"><?= $split['DyT_Cust'] ?></span>
<input type="hidden" name="DyT_Cust" id="DyT_Cust" value="<?= $split['DyT_Cust'] ?>">
      </p>
    </div>



  </form>
</div>
<script type="text/javascript" src="js/splitData.js"></script>
