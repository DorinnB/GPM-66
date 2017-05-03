<div class="row">
  <?php if (isset($poste[$n_poste])): ?>
  <div class="col-md-12 <?= $poste[$n_poste]['currentBlock']  ?>" style="border:1px solid black; margin:1px 0px;">
    <?= $poste[$n_poste]['machine']  ?><br/>
    <?= $poste[$n_poste]['job'].'&nbsp;'. $poste[$n_poste]['split']  ?><br/>
    <?= $poste[$n_poste]['currentBlock']  ?>
  </div>
<?php endif ?>
</div>
