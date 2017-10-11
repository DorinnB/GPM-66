<div class="col-md-12" style="height:100%">
  <table id="table_GestionEp" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th>Poste</th>
        <th>Date</th>
        <th>commentaire</th>
        <th>Job</th>
        <th>Dessin</th>
        <th>Mat</th>

        <th><acronym title='Disp : <b>P</b>iD'>D P</acronym></th>
        <th><acronym title='Disp : P<b>i</b>D'>D i</acronym></th>
        <th><acronym title='Disp : Pi<b>D</b>'>D D</acronym></th>
        <th><acronym title='Disp : Convergence'>D C</acronym></th>
        <th><acronym title='Disp : Sensibility'>D S</acronym></th>
        <th><acronym title='LoaD : <b>P</b>iD'>L P</acronym></th>
        <th><acronym title='LoaD : P<b>i</b>D'>L i</acronym></th>
        <th><acronym title='LoaD : Pi<b>D</b>'>L D</acronym></th>
        <th><acronym title='LoaD : Convergence'>L C</acronym></th>
        <th><acronym title='LoaD : Sensibility'>L S</acronym></th>
        <th><acronym title='Strain : <b>P</b>iD'>S P</acronym></th>
        <th><acronym title='Strain : P<b>i</b>D'>S i</acronym></th>
        <th><acronym title='Strain : Pi<b>D</b>'>S D</acronym></th>
        <th><acronym title='Strain : Convergence'>S C</acronym></th>
        <th><acronym title='Strain : Sensibility'>S S</acronym></th>
        
        <th>cell_displacement_serial</th>
        <th>cell_load_serial</th>
        <th>extensometre</th>
        <th>outillage_top</th>
        <th>outillage_bot</th>
        <th>enregistreur</th>
        <th>chauffage</th>
        <th>ind_temp_top</th>
        <th>ind_temp_strap</th>
        <th>ind_temp_bot</th>
        <th>compresseur</th>
        <th>poste</th>


      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>Poste</th>
        <th>Date</th>
        <th>commentaire</th>
        <th>Job</th>
        <th>Dessin</th>
        <th>Mat</th>

        <th>D P</th>
        <th>D i</th>
        <th>D D</th>
        <th>D C</th>
        <th>D S</th>
        <th>L P</th>
        <th>L i</th>
        <th>L D</th>
        <th>L C</th>
        <th>L S</th>
        <th>S P</th>
        <th>S i</th>
        <th>S D</th>
        <th>S C</th>
        <th>S S</th>

        <th>cell_displacement_serial</th>
        <th>cell_load_serial</th>
        <th>extensometre</th>
        <th>outillage_top</th>
        <th>outillage_bot</th>
        <th>enregistreur</th>
        <th>chauffage</th>
        <th>ind_temp_top</th>
        <th>ind_temp_strap</th>
        <th>ind_temp_bot</th>
        <th>compresseur</th>
        <th>poste</th>


      </tr>
    </tfoot>
    <tbody>
      <?php for($k=0;$k < count($history);$k++): ?>
        <tr onclick="location.href='index.php?page=gestionPoste&id_poste=<?= $history[$k]['id_poste'] ?>';">
          <td><?= $history[$k]['id_poste'] ?></td>
          <td><?= date_format(date_create($history[$k]['date'] ), 'Y-m-d')  ?></td>
          <td class="popover-markup" data-placement="right"><?= ($history[$k]['poste_commentaire']=="")?"":substr($history[$k]['poste_commentaire'],0,50)." [...]" ?>
            <?php if ($history[$k]['poste_commentaire'] !=""):  ?>
              <div class="head hide">Order Comment</div>
              <div class="content hide">
                <div class="form-group">
                  <textarea class"bubble_commentaire" name="c_commentaire" rows="10" cols="50" style="resize:none;" disabled><?= $history[$k]['poste_commentaire'] ?></textarea>
                </div>
              </div>
            <?php endif ?>
          </td>

          <td style="white-space: normal;"><?= $history[$k]['job'] ?></td>
          <td style="white-space: normal;"><?= $history[$k]['dessin'] ?></td>
          <td style="white-space: normal;"><?= $history[$k]['matiere'] ?></td>

          <td><?= $history[$k]['Disp_P'] ?></td>
          <td><?= $history[$k]['Disp_i'] ?></td>
          <td><?= $history[$k]['Disp_D'] ?></td>
          <td><?= $history[$k]['Disp_Conv'] ?></td>
          <td><?= $history[$k]['Disp_Sens'] ?></td>
          <td><?= $history[$k]['Load_P'] ?></td>
          <td><?= $history[$k]['Load_i'] ?></td>
          <td><?= $history[$k]['Load_D'] ?></td>
          <td><?= $history[$k]['Load_Conv'] ?></td>
          <td><?= $history[$k]['Load_Sens'] ?></td>
          <td><?= $history[$k]['Strain_P'] ?></td>
          <td><?= $history[$k]['Strain_i'] ?></td>
          <td><?= $history[$k]['Strain_D'] ?></td>
          <td><?= $history[$k]['Strain_Conv'] ?></td>
          <td><?= $history[$k]['Strain_Sens'] ?></td>

          <td><?= $history[$k]['cell_displacement_serial'] ?></td>
          <td><?= $history[$k]['cell_load_serial'] ?></td>
          <td><?= $history[$k]['extensometre'] ?></td>
          <td><?= $history[$k]['outillage_top'] ?></td>
          <td><?= $history[$k]['outillage_bot'] ?></td>
          <td><?= $history[$k]['enregistreur'] ?></td>
          <td><?= $history[$k]['chauffage'] ?></td>
          <td><?= $history[$k]['ind_temp_top'] ?></td>
          <td><?= $history[$k]['ind_temp_strap'] ?></td>
          <td><?= $history[$k]['ind_temp_bot'] ?></td>
          <td><?= $history[$k]['compresseur'] ?></td>
          <td><?= $history[$k]['poste'] ?></td>

        </tr>
      <?php endfor ?>
    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/gestionPosteHistory.js"></script>
