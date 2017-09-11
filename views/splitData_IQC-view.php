<link href="css/splitData.css" rel="stylesheet">
<script type="text/javascript" src="js/splitData_IQC.js"></script>

<div class="col-md-12" style="height:85%">
  <p class="title">
    <span class="name">Spec :</span>
    <span class="date"><?= $split['specification'] ?></span>
  </p>
  <p class="title">
    <span class="name">Dwg :</span>
    <span class="date"><?= $split['dessin'] ?></span>
  </p>

  <?php for($i=1;$i<=count($nomDimension);$i++): ?>
    <p class="title">
      <span class="name"><?= $nomDimension[$i-1] ?> :</span>
      <span class="date"><?= $Dwg['nominal_'.$i] ?> <?= ($Dwg['tolerance_plus_'.$i]==$Dwg['tolerance_moins_'.$i])?'&plusmn;'.$Dwg['tolerance_plus_'.$i]:'+'.$Dwg['tolerance_plus_'.$i].'-'.$Dwg['tolerance_moins_'.$i] ?></span>
    </p>
  <?php  endfor  ?>
  <p class="title">
    <span class="name">Equipments :</span>
    <span class="date"><?= $split['comments'] ?></span>
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
    <span class="name">Dy T :</span>
    <span class="date"><?= (($split['DyT_Cust']=="")?'Undefined':$split['DyT_Cust']) ?></span>
  </p>


  <p class="title">
  <a href="controller/createIQC-controller.php?id_job=<?=  $split['id_tbljob']  ?>">
  <img border="0" alt="W3Schools" src="img/excel.jpg" height="200">
  </a>
</p>



<form id="uploadDim" action="controller/updateIQC/php " method="post" enctype="multipart/form-data">
    Upload Dim data
    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" value="i:\temp\pgo">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">
    <button type="submit" value="aaaa">
</form>


</div>
