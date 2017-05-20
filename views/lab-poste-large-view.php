<div class="row lab">
  <?php if (isset($poste[$n_poste])): ?>

    <div class="col-md-12 machine" style="border:1px solid black; margin:5px 0px;background-color:<?= $poste[$n_poste]['background-color'] ?>;color:<?= $poste[$n_poste]['color'] ?>;display:<?=  ($poste[$n_poste]['currentBlock']=='Send')?'none':'block'  ?>;">
      <b><?= $poste[$n_poste]['machine']  ?><br/></b>
      <a href="index.php?page=labo&amp;id_tbljob=<?=  $poste[$n_poste]['id_job'] ?>">
        Job: <?= $poste[$n_poste]['customer'].' '.$poste[$n_poste]['job'].' '. $poste[$n_poste]['split']  ?><br/>
        Files: <?= $poste[$n_poste]['n_fichier'].' Test: '.$poste[$n_poste]['n_essai'].' T°: '.(!empty($poste[$n_poste]['c_temperature'])?number_format($poste[$n_poste]['c_temperature'], 0,'.', ' '):'')  ?><br/>
        ID: <?= $poste[$n_poste]['prefixe'].' '.$poste[$n_poste]['nom_eprouvette']  ?><br/>
        <?= isset($poste[$n_poste]['Cycle_final'])?$poste[$n_poste]['Cycle_final'].'&nbsp;cycles&nbsp;('.$poste[$n_poste]['tempsRestant'].'&nbsp;h&nbsp;left)':''  ?><br/>
        <?= $poste[$n_poste]['currentBlock']  ?>
      </a>
    </div>

    <div class="col-md-12 foreCast" style="border:1px solid black; margin:5px 0px;background-color:indigo;color:white;display:<?=  ($poste[$n_poste]['currentBlock']=='Send')?'block':'none'  ?>;">
      <div class="col-md-3">
        <img src="img/<?= $poste[$n_poste]['icone_file']  ?>" style="width: auto;max-height: 30px;">
      </div>
      <div class="col-md-6">
        <b><?= $poste[$n_poste]['machine']  ?></b>
      </div>
      <div class="col-md-3">
        <img src="img/medal_<?= $poste[$n_poste]['prio_machine_forcast']  ?>" style="width: auto;max-height: 30px;">
      </div>
      <textarea disabled readonly style="resize: none; background-color:indigo; width:100%; border:0px;"><?= $poste[$n_poste]['texte_machine_forcast'] ?></textarea>
    </div>

  <?php endif ?>
</div>
