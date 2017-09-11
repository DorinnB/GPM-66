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
    <span class="name"><acronym title="Nb specimen / Test done / Test planned">Qty :</acronym></span>
    <span class="date"><?= $split['nbep'].' / '.$split['nbtestdone'].' / '.$split['nbtest'] ?></span>
  </p>
  <p class="title">
    <span class="name">Available :</span>
    <span class="date"><?= (($split['available']=="")?'Undefined':$split['available']) ?></span>
  </p>
  <p class="title">
    <span class="name"><acronym title="Test duration (Calc)/ 'Heures Sup' (Calc)">Test (hrs) :</acronym></span>

    <span class="date"><?=
    (($split['tpstest']=="")?'N/A':number_format($split['tpstest'], 1, '.', ' ')).
    ' <i style="font-size:75%">('.
    (($split['tpscalc']=="")?'N/A':number_format($split['tpscalc'], 1, '.', ' ')).
    ')</i> / '.
    (($split['hrsup']=="")?'N/A':number_format($split['hrsup'], 1, '.', ' ')).
      ' <i style="font-size:75%">('.
    (($split['tpssupcalc']=="")?'N/A':number_format($split['tpssupcalc'], 1, '.', ' ')).
    ')</i>'
    ?></span>

  </p>

  <p class="title">
    <span class="name">Est test days left :</span>
    <span class="date"><i>est day</i></span>
  </p>
  <p class="title">
    <span class="name">Dy T :</span>
    <span class="date"><?= (($split['DyT_Cust']=="")?'Undefined':$split['DyT_Cust']) ?></span>
  </p>



</div>
