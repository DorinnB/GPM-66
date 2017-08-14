<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData" style="height:100%">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">
    <p class="title">
      <span class="name">Sub Contractor :</span>
      <span class="date"><acronym title="<?= $split['entrepriseST'] ?>"><?= $split['entreprise_abbrST'] ?></acronym></span>
    </p>
    <p class="title">
      <span class="name">Contact :</span>
      <span class="date"><?= $split['lastnameST'].' '.$split['surnameST'] ?></span>
    </p>
    <p class="title">
      <span class="name" id="subCRef">Sub Ref :</span>
      <span class="date" id="refSubC_alt"><?= $split['refSubC'] ?></span>

      <input type="text" class="form-control flip" id="refSubC" name="refSubC" value="<?= $split['refSubC'] ?>">
    </p>


    <p class="title">
      <span class="name">Spec :</span>
      <span class="date"><?= $split['specification'] ?></span>
    </p>
    <p class="title">
      <span class="name">Dwg :</span>
      <span class="date"><?= $split['dessin'] ?></span>
    </p>

    <p class="title">
      <span class="name">Qty :</span>
      <span class="date"><?= $splitEp['nbep'] ?></span>
    </p>

    <p class="title">
      <span class="name">Specimen Recept :</span>
      <span class="date"><i>part</i></span>
    </p>


    <p class="title">
      <span class="name" id="DyT_expectedFlip">Dy T expected :</span>
      <span class="date" id="DyT_expected_alt"><?= (($split['DyT_expected']=="")?'Undefined':$split['DyT_expected']) ?></span>

      <input type="text" class="form-control flip" name="DyT_expected" id="DyT_expected" value="<?= $split['DyT_expected'] ?>">
    </p>

    <p class="title">
      <span class="name">Dy T Req. :</span>
      <span class="date"><?= (($split['test_leadtime']=="")?'Undefined':$split['test_leadtime']) ?></span>
    </p>
  </form>
</div>
<script type="text/javascript" src="js/splitData.js"></script>
