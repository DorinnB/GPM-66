<link href="css/splitEprouvette.css" rel="stylesheet">
<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>id</th>
        <th><acronym title="Availability">A</acronym></th>
        <th><acronym title="Specimen Prefix">Prefix</acronym></th>
        <th><acronym title="Specimen Name">ID</acronym></th>
        <th><acronym title="Temperature">T°</acronym></th>
        <th><acronym title="Frequency">F.</acronym></th>
        <th><acronym title="Switch To Load">STL</acronym></th>
        <th><acronym title="Load Frequency">F.<SUB>STL</SUB></acronym></th>
        <th><?= $split['cons1'] ?></th>
        <th><?= $split['cons2'] ?></th>
        <th><acronym title="Lab Max">M <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Lab Min">m <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Est.<SUB>Cy</SUB></acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Order Check">Chk</acronym></th>
        <th><acronym title="Lab Observation">L. Obs.</acronym></th>
        <th><acronym title="Quality Review">Q.</acronym></th>
        <th><acronym title="Quality Observation">Q. Obs</acronym></th>
        <th><acronym title="Current Block">State</acronym></th>
        <th><acronym title="Test Number">Test</acronym></th>
        <th><acronym title="Files Number">Files</acronym></th>
        <?php  foreach ($dimDenomination as $dimTexte): ?>
          <th><?= $dimTexte  ?></th>
        <?php  endforeach  ?>
        <th>Frame</th>
        <th>Date</th>
        <th><acronym title="Waveform">Wave.</acronym></th>
        <th><acronym title="STL Cycles">STL</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
        <th><acronym title="Rupture">Rupt</acronym></th>
        <th><acronym title="Fracture">Fract.</acronym></th>
        <th><acronym title="Test Duration (h)">Tps</acronym></th>
        <th><acronym title="Dilatation">&Delta;&epsilon; °</acronym></th>
        <th><acronym title="Round Temp. Modulus">E RT</acronym></th>
        <th><acronym title="First Cycle Modulus">E 1th</acronym></th>
        <th><acronym title="First Cycle Stress Max">&sigma; M</acronym></th>
        <th><acronym title="First Cycle Stress Min">&sigma; m</acronym></th>
        <th><acronym title="First Cycle Strain M">&epsilon; M</acronym></th>
        <th><acronym title="First Cycle Strain M">&epsilon; m</acronym></th>
        <th><acronym title="Half Life Cycle">HL Cy</acronym></th>
        <th><acronym title="Half Life Cycle Modulus">E</acronym></th>
        <th><acronym title="Half Life Cycle Stress Max">&sigma; M</acronym></th>
        <th><acronym title="Half Life Cycle Stress Min">&sigma; m</acronym></th>
        <th><acronym title="Half Life Cycle Delta Strain">&Delta;&epsilon;</acronym></th>
        <th><acronym title="Half Life Cycle Elastic Strain">&Delta;&epsilon;<SUB>e</SUB></acronym></th>
        <th><acronym title="Half Life Cycle Inelastic Strain Calculated">&Delta;&epsilon;<SUB>p</SUB></acronym></th>
        <th><acronym title="Half Life Cycle Inelastic Strain Measured">&Delta;&epsilon;<SUB>pm</SUB></acronym></th>
        <th><acronym title="Ni">Ni</acronym></th>
        <th><acronym title="NF 75%">NF<SUB>75%</SUB></acronym></th>
        <th><acronym title="Rupture Check">R.</acronym></th>
        <th><acronym title="Data Check">D.</acronym></th>
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
        <th><acronym title="Switch To Load">STL</acronym></th>
        <th><acronym title="Load Frequency">F.STL</acronym></th>
        <th><?= $split['cons1'] ?></th>
        <th><?= $split['cons2'] ?></th>
        <th><acronym title="Lab Max">M <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Lab Min">m <?= ($split['c_unite']=="MPa")?"kN":$split['c_unite']  ?></acronym></th>
        <th><acronym title="Minimum Requirement">Cy Min</acronym></th>
        <th>Runout</th>
        <th><acronym title="Estimated Cycle">Cy Est.</acronym></th>
        <th><acronym title="Order Comment">Com.</acronym></th>
        <th><acronym title="Order Check">Chk</acronym></th>
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
        <th><acronym title="STL Cycles">STL</acronym></th>
        <th><acronym title="Final Cycles">Final</acronym></th>
        <th><acronym title="Rupture">Rupt</acronym></th>
        <th><acronym title="Fracture">Fract.</acronym></th>
        <th><acronym title="Test Duration (h)">Tps</acronym></th>
        <th><acronym title="Dilatation">&Delta;&epsilon; °</acronym></th>
        <th><acronym title="Round Temp. Modulus">E RT</acronym></th>
        <th><acronym title="First Cycle Modulus">E 1th</acronym></th>
        <th><acronym title="First Cycle Stress Max">&sigma; max</acronym></th>
        <th><acronym title="First Cycle Stress Min">&sigma; min</acronym></th>
        <th><acronym title="First Cycle Strain M">&sigma; max</acronym></th>
        <th><acronym title="First Cycle Strain M">&sigma; min</acronym></th>
        <th><acronym title="Half Life Cycle">Cy half life</acronym></th>
        <th><acronym title="Half Life Cycle Modulus">E</acronym></th>
        <th><acronym title="Half Life Cycle Stress Max">&sigma; max</acronym></th>
        <th><acronym title="Half Life Cycle Stress Min">&sigma; min</acronym></th>
        <th><acronym title="Half Life Cycle Delta Strain">&Delta;&epsilon;</acronym></th>
        <th><acronym title="Half Life Cycle Elastic Strain">&Delta;&epsilon; e</acronym></th>
        <th><acronym title="Half Life Cycle Inelastic Strain Calculated">&Delta;&epsilon; p</acronym></th>
        <th><acronym title="Half Life Cycle Inelastic Strain Measured">&Delta;&epsilon; pm</acronym></th>
        <th><acronym title="Ni">Ni</acronym></th>
        <th><acronym title="NF 75%">NF 75%</acronym></th>
        <th><acronym title="Rupture Check">R.Chk</acronym></th>
        <th><acronym title="Data Check">D.Chk</acronym></th>
      </tr>
    </tfoot>

    <tbody>
      <?php for($k=0;$k < count($ep);$k++): ?>
        <tr class="chartTR">
          <td><?= $ep[$k]['id_master_eprouvette'] ?></td>
          <td class="dispo open-GestionEp selectable"  data-toggle="modal" data-target="#gestionEp" data-id="<?= $ep[$k]['id_eprouvette'] ?>" data-dispo="<?= $ep[$k]['dispo'] ?>"><?= $ep[$k]['dispo'] ?></td>
          <td><?= $ep[$k]['prefixe'] ?></td>
          <td><?= $ep[$k]['nom_eprouvette'] ?><sup><?= ($ep[$k]['retest']!=1)?$ep[$k]['retest']:'' ?></sup></td>
          <td><?= $ep[$k]['c_temp'] ?></td>
          <td><?= $ep[$k]['c_frequence'] ?></td>
          <td><?= $ep[$k]['c_cycle_STL'] ?></td>
          <td><?= $ep[$k]['c_frequence_STL'] ?></td>
          <td><?= $ep[$k]['c_type_1_val'] ?></td>
          <td><?= $ep[$k]['c_type_2_val'] ?></td>
          <td class="chartMax"><?= $ep[$k]['max'] ?></td>
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
          <td><?= $ep[$k]['Cycle_STL'] ?></td>
          <td class="chartCycle <?= $ep[$k]['Cycle_min_nonAtteint']  ?>"><?= $ep[$k]['Cycle_final'] ?></td>
          <td><?= $ep[$k]['Rupture'] ?></td>
          <td class="<?= $ep[$k]['CheckValue_Fracture'] ?>"><?= $ep[$k]['Fracture'] ?></td>
          <td style=" white-space: pre;"><?= $ep[$k]['temps_essais'] ?></td>

          <td><?= $ep[$k]['dilatation'] ?></td>

          <td class="checkValue_E_RT"><?= $ep[$k]['E_RT'] ?></td>

          <td class="checkValue_c1_E_montant"><?= $ep[$k]['c1_E_montant'] ?></td>
          <td><?= $ep[$k]['c1_max_strain'] ?></td>
          <td><?= $ep[$k]['c1_min_strain'] ?></td>
          <td class="checkValue_c1_max_stress"><?= $ep[$k]['c1_max_stress'] ?></td>
          <td class="checkValue_c1_min_stress"><?= $ep[$k]['c1_min_stress'] ?></td>

          <td><?= $ep[$k]['c2_cycle'] ?></td>
          <td class="checkValue_c2_E_montant"><?= $ep[$k]['c2_E_montant'] ?></td>
          <td class="checkValue_c2_max_stress"><?= $ep[$k]['c2_max_stress'] ?></td>
          <td class="checkValue_c2_min_stress"><?= $ep[$k]['c2_min_stress'] ?></td>
          <td><?= $ep[$k]['c2_delta_strain'] ?></td>
          <td><?= $ep[$k]['c2_strain_e'] ?></td>
          <td><?= $ep[$k]['c2_calc_inelastic_strain'] ?></td>
          <td><?= $ep[$k]['c2_meas_inelastic_strain'] ?></td>


          <td><?= $ep[$k]['Ni'] ?></td>
          <td><?= $ep[$k]['Nf75'] ?></td>

          <td class="dCheckEp" data-dchecked="<?= max(0,$ep[$k]['check_rupture']) ?>" ><?= $ep[$k]['check_rupture'] ?></td>
          <td class="dCheckEp selectable" data-dchecked="<?= max(0,$ep[$k]['d_checked']) ?>"  data-idepdchecked="<?= $ep[$k]['id_eprouvette'] ?>"><?= $ep[$k]['d_checked'] ?></td>

        </tr>

      <?php endfor ?>
    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/splitEprouvette.js"></script>
