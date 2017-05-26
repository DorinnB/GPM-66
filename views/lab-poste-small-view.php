<div class="row">
  <?php if (isset($poste[$n_poste])): ?>
  <div class="col-md-12 machine" style="border:1px solid black; margin:1px 0px;background-color:<?= $poste[$n_poste]['background-color'] ?>;color:<?= $poste[$n_poste]['color'] ?>;display:<?=  ($poste[$n_poste]['currentBlock']=='Send')?'none':'block'  ?>;">
    <?= $poste[$n_poste]['machine']  ?><br/>
    <?= $poste[$n_poste]['job'].'&nbsp;'. $poste[$n_poste]['split']  ?><br/>
    <?= $poste[$n_poste]['currentBlock']  ?>
  </div>


  <div class="col-md-12 foreCast" style="border:1px solid black; margin:5px 0px;background-color:#536E94;color:white;display:<?=  ($poste[$n_poste]['currentBlock']=='Send')?'block':'none'  ?>;">
    <?= $poste[$n_poste]['machine']  ?><br/>
    <?= $poste[$n_poste]['icone_name']  ?><br/>
  </div>


<?php endif ?>
</div>
