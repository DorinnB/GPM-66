<div class="row">
  <?php if (isset($poste[$n_poste])): ?>
    <a href="index.php?page=labo&amp;id_tbljob=<?=  $poste[$n_poste]['id_job'] ?>">
      <div class="col-md-12 <?= $poste[$n_poste]['currentBlock']  ?>" style="border:1px solid black; margin:5px 0px;">
        <b><?= $poste[$n_poste]['machine']  ?><br/></b>
        Job: <?= $poste[$n_poste]['customer'].' '.$poste[$n_poste]['job'].' '. $poste[$n_poste]['split']  ?><br/>
        Files: <?= $poste[$n_poste]['n_fichier'].' Test: '.$poste[$n_poste]['n_essai']  ?><br/>
        ID: <?= $poste[$n_poste]['prefixe'].' '.$poste[$n_poste]['nom_eprouvette']  ?><br/>
        <?= isset($poste[$n_poste]['Cycle_final'])?$poste[$n_poste]['Cycle_final'].'&nbsp;cycles&nbsp;('.$poste[$n_poste]['tempsRestant'].'&nbsp;h&nbsp;left)':''  ?><br/>
        <?= $poste[$n_poste]['currentBlock']  ?>
      </div>
    </a>
  <?php endif ?>
</div>
