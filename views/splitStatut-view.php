<div class="col-md-12" style="height:15%">
  <!-- Single button -->
  <div class="btn-group btnstatut" style="width:100%;">
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="splitStatut" style="background-color : <?= $split['statut_color'] ?>" style="float:none;">
      <?= $split['statut'] ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu statut">
      <?php foreach ($statut as $row): ?>
        <li  style="background-color : <?= $row['statut_color'] ?>" onclick="<?php if (isset($_COOKIE['id_user'])): ?>updateStatut('<?= $split['id_tbljob'] ?>', '<?= $row['id_statut'] ?>');<?php else: ?>alert('Please Login then refresh the browser');<?php endif; ?>"><a href="#"><?= $row['statut'].(($row['statut_lock']==1)?' *':'') ?></a></li>
        <?php endforeach ?>
      </ul>
      <span class="glyphicon glyphicon-education" id="findStatut" onclick="findStatut('<?= $split['id_tbljob'] ?>')"></span>
    </div>
    <div class="btn-group btnstatut" role="group" aria-label="..." style="width:100%;">
      <button type="button" class="btn btn-default" style="width:30%;" onclick="<?php if (isset($_COOKIE['id_user'])): ?>
        <?php if ($split['checked']>0): ?>
          var confirmation = confirm('Update Split already Checked ?');
          if (confirmation) {
            goto('split','id_tbljob','<?= $split['id_tbljob'] ?>','dataInput');
          }
        <?php else: ?>
          goto('split','id_tbljob','<?= $split['id_tbljob'] ?>','dataInput');
        <?php endif; ?>
      <?php else: ?>alert('Please Login then refresh the browser');<?php endif; ?>">Split</button>
        <button type="button" class="btn btn-default" style="width:30%;" onclick="<?php if (isset($_COOKIE['id_user'])): ?>goto('split','id_tbljob','<?= $split['id_tbljob'] ?>','eprouvetteConsigne');<?php else: ?>alert('Please Login then refresh the browser');<?php endif; ?>">Cons</button>
          <button type="button" class="btn btn-default" style="width:30%;" onclick="<?php if (isset($_COOKIE['id_user'])): ?>goto('split','id_tbljob','<?= $split['id_tbljob'] ?>','eprouvetteValue');<?php else: ?>alert('Please Login then refresh the browser');<?php endif; ?>">Value</button>
          </div>

          <!--Pour les couleurs des statuts -->
          <script type="text/javascript" src="js/statut.js"></script>

          <script type="text/javascript" src="js/splitStatut.js"></script>
          <link href="css/statut.css" rel="stylesheet">
          <!--Bug sur les dropdown a cause de bootstrap, ajout de la commande js pour les autoriser-->
          <script>$('.dropdown-toggle').dropdown()</script>
        </div>
