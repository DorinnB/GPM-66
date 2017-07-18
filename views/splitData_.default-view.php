<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
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
    <span class="name">Dy T Availlable :</span>
    <span class="date"><?= (($split['test_leadtime']=="")?'Undefined':$split['test_leadtime']) ?></span>
  </p>
  modif depuis split ou directement ici ?
  recheck split ? (non)
  <p class="title">
    <span class="name">Dy T Req. :</span>
    <span class="date"><?= (($split['test_leadtime']=="")?'Undefined':$split['test_leadtime']) ?></span>
  </p>
fixe en debut de job, sujet a check split ?

</div>
