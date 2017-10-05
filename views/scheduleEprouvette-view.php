<link href="css/scheduleEprouvette.css" rel="stylesheet">
<div class="col-md-12" style="height:100%">
  <table id="table_ep" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
    <thead>
      <tr>
        <th rowspan=2>Groupe</th>
        <th rowspan=2>Nb</th>
        <th rowspan=2>receipt</th>
        <?php  foreach ($splits as $splitJob): ?>
          <th colspan=2><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
        <?php  endforeach  ?>
      </tr>
      <tr>
        <?php  foreach ($splits as $splitJob): ?>
          <?php if (substr( $splitJob['test_type_abbr'], 0, 1 ) === ".") :  ?>
            <th>DyT SubC</th>
            <th>Expected</th>
          <?php else: ?>
            <th>DyT Cust</th>
            <th>Expected</th>
          <?php endif ?>
        <?php  endforeach  ?>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <th>Groupe</th>
        <th>Nb</th>
        <th>receipt</th>
        <?php  foreach ($splits as $splitJob): ?>
          <?php if ($splitJob['ST'] == 1) :  ?>
            <th>Sent</th>
            <th><?= $splitJob['test_type_abbr']  ?></th>
          <?php else: ?>
            <th>Start <?= $splitJob['test_type_abbr']  ?></th>
            <th><?= $splitJob['test_type_abbr']  ?></th>
          <?php endif ?>
        <?php  endforeach  ?>
      </tr>
    </tfoot>

    <tbody>
      <?php foreach ($groupes as $key => $line): ?>
        <tr>
          <td>Group <?= $key ?></td>
          <td></td>
          <td><?= $split['available_expected'] ?></td>

          <?php  foreach ($splits as $splitJob): ?>
            <?php if (isset($groupes[$key]['split'][$splitJob['id_tbljob']])) : ?>
              <?php if ($splitJob['ST'] == 1) :  ?>
                <td><?= $splitJob['DyT_SubC']  ?></td>
                <td><?= $splitJob['DyT_expected']  ?></td>
              <?php elseif ($splitJob['auxilaire'] == 1) :  ?>
                <td><?= $splitJob['DyT_Cust']  ?></td>
                <td><?= $splitJob['DyT_expected']  ?></td>
              <?php else: ?>
                <td><?= $splitJob['DyT_Cust']  ?></td>
                <td><?= $splitJob['DyT_expected']  ?></td>
              <?php endif ?>
            <?php else: ?>
              <td class="noInOut"></td>
              <td class="noInOut"></td>
            <?php endif ?>
          <?php  endforeach  ?>

        </tr>
      <?php endforeach ?>

    </tbody>
  </table>


</div>

<script type="text/javascript" src="js/scheduleEprouvette.js"></script>
