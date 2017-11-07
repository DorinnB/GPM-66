<link href="css/qualiteList.css" rel="stylesheet">
<div class="container" id="qualiteList" style="width:100%">
  <h3>SubC List
  </h3>
  <div class="row">
    <div class="col-md-12">
      <div class="row" style="border-bottom:2px solid white;">
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Awaiting Arrival
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Ready to send
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            One_Week Dy.T.
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            <i>Error</i>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <?php foreach ($awaiting as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($readyToSend as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($oneWeek as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($errorInOut as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=inOut&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
      </div>
    </div>
  </div>
</div>
