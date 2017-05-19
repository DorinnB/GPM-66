<link href="css/qualiteList.css" rel="stylesheet">
<div class="container" id="qualiteList" style="width:100%">
  <h3>Quality List
  </h3>
  <div class="row">
    <div class="col-md-12">
      <div class="row" style="border-bottom:2px solid white;">
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Unchecked
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Unchecked Started
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            Quality Flag
          </div>
        </div>
        <div class="col-md-3">
          <div class="col-md-12 titre">
            <i>Report Check</i>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <?php foreach ($uncheckedJob as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=labo&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($uncheckedStartedJob as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=labo&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
          <?php foreach ($flag as $key => $value) : ?>
            <div class="col-md-12 valeur" onclick="document.location='index.php?page=labo&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
              <?= $value['job'].'-'.$value['split']  ?>
            </div>
          <?php endforeach  ?>
        </div>
        <div class="col-md-3">
        </div>
      </div>
    </div>
  </div>
</div>
