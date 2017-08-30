<link href="css/splitEprouvette.css" rel="stylesheet">
<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>id</th>
        <th><acronym title="Availability">A</acronym></th>
        <th>Prefixe</th>
        <th>ID</th>
        <th><acronym title="Temperature">T°</acronym></th>
        <th>Freq</th>
        <th><?= $split['cons1'] ?></th>
        <th><?= $split['cons2'] ?></th>
        <th><acronym title="Lab Max">M <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Lab Min">m <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Quality Check">Chk</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Current Block">Block</acronym></th>
        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <th>Machine</th>
        <th>Date</th>
        <th><acronym title="Waveform">Wave.</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
        <th><acronym title="Rupture">Rupt</acronym></th>
        <th><acronym title="Fracture">Fract.</acronym></th>
        <th><acronym title="Test Duration (h)">Tps</acronym></th>
        <th><acronym title="Value Check">Valid</acronym></th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th>id</th>
        <th><acronym title="Availability">A</acronym></th>
        <th>Prefixe</th>
        <th>ID</th>
        <th><acronym title="Temperature">T°</acronym></th>
        <th>Freq</th>
        <th><?= $split['cons1'] ?></th>
        <th><?= $split['cons2'] ?></th>
        <th><acronym title="Lab Max">M <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Lab Min">m <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Quality Check">Chk</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Current Block">Block</acronym></th>
        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <th>Machine</th>
        <th>Date</th>
        <th><acronym title="Waveform">Wave.</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
        <th><acronym title="Rupture">Rupt</acronym></th>
        <th><acronym title="Fracture">Fract.</acronym></th>
        <th><acronym title="Test Duration (h)">Tps</acronym></th>
        <th><acronym title="Value Check">Valid</acronym></th>
      </tr>
    </tfoot>

    <tbody>
      <?php for($k=0;$k < count($ep);$k++): ?>
        <tr>
          <td><?= $ep[$k]['id_master_eprouvette'] ?></td>
          <td class="dispo open-GestionEp selectable"  data-toggle="modal" data-target="#gestionEp" data-id="<?= $ep[$k]['id_eprouvette'] ?>" data-dispo="<?= $ep[$k]['dispo'] ?>"><?= $ep[$k]['dispo'] ?></td>
          <td><?= $ep[$k]['prefixe'] ?></td>
          <td><?= $ep[$k]['nom_eprouvette'] ?><sup><?= ($ep[$k]['retest']!=1)?$ep[$k]['retest']:'' ?></sup></td>
          <td><?= $ep[$k]['c_temp'] ?></td>
          <td><?= $ep[$k]['c_frequence'] ?></td>
          <td><?= $ep[$k]['c_type_1_val'] ?></td>
          <td><?= $ep[$k]['c_type_2_val'] ?></td>
          <td><?= $ep[$k]['max'] ?></td>
          <td><?= $ep[$k]['min'] ?></td>
          <td><?= $ep[$k]['Cycle_min'] ?></td>
          <td><?= $ep[$k]['runout'] ?></td>
          <td><?= $ep[$k]['cycle_estime'] ?></td>
          <td class="popover-markup" data-placement="left"><?= ($ep[$k]['comm'].$ep[$k]['c_commentaire']=="")?"":substr($ep[$k]['comm'].$ep[$k]['c_commentaire'],0,5)." [...]" ?>
            <?php if ($ep[$k]['comm'].$ep[$k]['c_commentaire'] !=""):  ?>
              <div class="head hide">Order Comment</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="c_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $ep[$k]['comm'].$ep[$k]['c_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>
          <td class="checkEp selectable" data-checked="<?= max(0,$ep[$k]['c_checked']) ?>" data-idepchecked="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['c_checked'] ?></td>
          <td class="popover-markup" data-placement="left"><?= ($ep[$k]['d_commentaire']=="")?"":substr($ep[$k]['d_commentaire'],0,5)." [...]" ?>
            <?php if ($ep[$k]['d_commentaire'] !=""):  ?>
              <div class="head hide">Lab Observation</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="d_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $ep[$k]['d_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>
          <td class="flag_qualite selectable" data-flagQualite="<?= $ep[$k]['flag_qualite'] ?>"  data-idepflagqualite="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['flag_qualite'] ?></td>
          <td class="popover-markup" data-placement="left"><?= ($ep[$k]['q_commentaire']=="")?"":substr($ep[$k]['q_commentaire'],0,5)." [...]" ?>
            <?php if ($ep[$k]['q_commentaire'] !=""):  ?>
              <div class="head hide">Quality Observation</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="q_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $ep[$k]['q_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>
          <td><?= $ep[$k]['currentBlock'] ?></td>
          <td><?= $ep[$k]['n_essai'] ?></td>
          <td><?= $ep[$k]['n_fichier'] ?></td>
          <?php for($i=1;$i <= count($dimDenomination);$i++): ?>
            <td><?= $ep[$k]['dim'.$i]  ?></td>
          <?php  endfor  ?>
          <td><?= $ep[$k]['machine'] ?></td>
          <td><?= $ep[$k]['date'] ?></td>
          <td><?= $ep[$k]['waveform'] ?></td>
          <td class="<?= (intval($ep[$k]['Cycle_final'])<intval($ep[$k]['Cycle_min']) AND intval($ep[$k]['Cycle_min'])>0)?'flagMini':'' ?>"><?= $ep[$k]['Cycle_final'] ?></td>
          <td><?= $ep[$k]['Rupture'] ?></td>
          <td><?= $ep[$k]['Fracture'] ?></td>
          <td style=" white-space: pre;"><?= $ep[$k]['temps_essais'] ?></td>

          <td class="dCheckEp selectable" data-dchecked="<?= max(0,$ep[$k]['d_checked']) ?>"  data-idepdchecked="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['d_checked'] ?></td>

        </tr>

      <?php endfor ?>
    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/splitEprouvette.js"></script>
