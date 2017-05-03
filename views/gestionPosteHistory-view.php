<div class="col-md-12" style="height:100%">
  <table id="table_GestionEp" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th><acronym title="Poste">P</acronym></th>
        <th>cartouche_stroke</th>
        <th>cartouche_load</th>
        <th>cartouche_strain</th>
        <th>enregistreur</th>
        <th>Disp_P</th>
        <th>Disp_i</th>
        <th>Disp_D</th>
        <th>Disp_Conv</th>
        <th>Disp_Sens</th>
        <th>Load_P</th>
        <th>Load_i</th>
        <th>Load_D</th>
        <th>Load_Conv</th>
        <th>Load_Sens</th>
        <th>Strain_P</th>
        <th>Strain_i</th>
        <th>Strain_D</th>
        <th>Strain_Conv</th>
        <th>Strain_Sens</th>

      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>P</th>
        <th>cartouche_stroke</th>
        <th>cartouche_load</th>
        <th>cartouche_strain</th>
        <th>enregistreur</th>
        <th>Disp_P</th>
        <th>Disp_i</th>
        <th>Disp_D</th>
        <th>Disp_Conv</th>
        <th>Disp_Sens</th>
        <th>Load_P</th>
        <th>Load_i</th>
        <th>Load_D</th>
        <th>Load_Conv</th>
        <th>Load_Sens</th>
        <th>Strain_P</th>
        <th>Strain_i</th>
        <th>Strain_D</th>
        <th>Strain_Conv</th>
        <th>Strain_Sens</th>
        
      </tr>
    </tfoot>
    <tbody>
      <?php for($k=0;$k < count($history);$k++): ?>
        <tr onclick="location.href='index.php?page=gestionPoste&id_poste=<?= $history[$k]['id_poste'] ?>';">
          <td>Poste</td>
          <td><?= $history[$k]['cartouche_stroke'] ?></td>
          <td><?= $history[$k]['cartouche_load'] ?></td>
          <td><?= $history[$k]['cartouche_strain'] ?></td>
          <td><?= $history[$k]['enregistreur'] ?></td>
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
        </tr>
      <?php endfor ?>
    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/gestionPosteHistory.js"></script>
