<link href="css/splitData.css" rel="stylesheet">


<div class="col-md-12" id="splitData" style="height:85%">

  <div class="bs-example designation" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Specification :</span>
      <span class="value"><?= $split['specification'] ?></span>
    </p>
    <p class="title">
      <span class="name">Drawing :</span>
      <span class="value"><?= $split['dessin'] ?></span>
    </p>
    <?php for($i=1;$i<=count($nomDimension);$i++): ?>
      <p class="title">
        <span class="name"><?= $nomDimension[$i-1] ?> :</span>
        <span class="value"><?= $Dwg['nominal_'.$i] ?> <?= ($Dwg['tolerance_plus_'.$i]==$Dwg['tolerance_moins_'.$i])?'&plusmn;'.$Dwg['tolerance_plus_'.$i]:'+'.$Dwg['tolerance_plus_'.$i].'-'.$Dwg['tolerance_moins_'.$i] ?></span>
      </p>
    <?php  endfor  ?>
    <p class="title">
      <span class="name">Equipments :</span>
      <span class="value"><?= $split['comments'] ?></span>
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
  </div>

  <div class="bs-example planning" data-example-id="basic-forms">
    <p class="title">
      <span class="name">Availability :</span>
      <span class="value"><?= $split['available'] ?></span>
    </p>
    <p class="title">
      <span class="name">DyT Cust :</span>
      <span class="value"><?= $split['DyT_Cust'] ?></span>
    </p>
  </div>






ATTENTION : L'ICONE POUR TELECHARGER LA FEUILLE DIMENSIONNEL EST MAINTENANT SUR L'ICONE OT


<form id="uploadDim" action="controller/updateIQC/php " method="post" enctype="multipart/form-data">
    Upload Dim data
    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" value="i:\temp\pgo">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">
    <button type="submit" value="aaaa">
</form>


</div>
<script type="text/javascript" src="js/splitData_IQC.js"></script>
