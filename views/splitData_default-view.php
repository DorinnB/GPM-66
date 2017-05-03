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
    <span class="name">Signal :</span>
    <span class="date"><?= $split['waveform'] ?></span>
  </p>
  <p class="title">
    <span class="name">Temp :</span>
    <span class="date"><?= $splitEp['temperature'] ?></span>
  </p>
  <p class="title">
    <span class="name">Grip :</span>
    <span class="date"><i>taillegrip</i></span>
  </p>
  <p class="title">
    <span class="name">Load :</span>
    <span class="date">
        <button type="button" id="load10" class="btn-group-xs">10</button>
        <button type="button" id="load100" class="btn-group-xs">100</button>
        <button type="button" id="load250" class="btn-group-xs">250</button>
    </span>
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
    <span class="name">Est test days :</span>
    <span class="date"><i>est day</i></span>
  </p>
  <p class="title">
    <span class="name">Dy T :</span>
    <span class="date"><?= (($split['test_leadtime']=="")?'Undefined':$split['test_leadtime']) ?></span>
  </p>



</div>
