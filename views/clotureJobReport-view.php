<div class="col-md-12" style="height:100%">
  <table id="table_Report" data-idJob="<?php echo $split['id_tbljob'];	?>" class="table table-striped table-condensed table-hover table-bordered" cellspacing="0" style="white-space:nowrap;">
    <thead>
      <tr>
        <td>Split</td>
        <td>Report</td>
        <td>Q</td>
        <td>ST</td>
        <td>Date</td>
        <td>Rev</td>
        <td>RawData</td>
        <td>Expected</td>
        <td>Shipped</td>
      </tr>
      <!--
      <tr>
      <th></th>
      <?php  foreach ($splits as $splitJob): ?>
      <?php if (is_numeric($splitJob['split'])): ?>
      <th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
    <?php  endif ?>
  <?php  endforeach  ?>
</tr>
-->
</thead>


<tbody>
  <?php  foreach ($splits as $splitJob): ?>
    <?php if (is_numeric($splitJob['split'])): ?>
      <tr>
        <td><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></td>
        <td>
          <a href="controller/createReportPDF-controller?&id_tbljob=<?=	$splitJob['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:5px;font-size:inherit;">
            Create PDF
          </a>
        </td>
        <td class="report_Q <?=  ($splitJob['report_Q']>0)?'ok':'nok'  ?>" data-idtbljob="<?=	$splitJob['id_tbljob']	?>" data-report_Q="<?= $splitJob['report_Q']  ?>"><?= $splitJob['report_Q']  ?></td>
        <td class="<?=  ($splitJob['report_DT']>0)?'ok':'nok'  ?>"><?= $splitJob['report_DT']  ?></td>
        <td><?= $splitJob['report_date']  ?></td>
        <td><?= $splitJob['report_rev']  ?></td>
        <td class="<?=  ($splitJob['id_rawData']==0)?'none':(($splitJob['report_rawdata']<=0)?'half':'ok')  ?>"><?= $splitJob['report_rawdata']  ?></td>
        <td><?= $splitJob['expected']  ?></td>
        <td class="<?=  ($splitJob['shipped']==$splitJob['expected'])?'ok':'nok'  ?>"><?= $splitJob['shipped']  ?></td>
      </tr>
    <?php  endif ?>
  <?php  endforeach  ?>

  <!--
  <tr>
  <th></th>
  <?php  foreach ($splits as $splitJob): ?>
  <?php if (is_numeric($splitJob['split'])): ?>
  <th>Generate PDF</th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Check QUA</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Check DT</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Date</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Revision</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Raw Data</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Expected</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

<tr>
<th>Shipped</th>
<?php  foreach ($splits as $splitJob): ?>
<?php if (is_numeric($splitJob['split'])): ?>
<th><?= $splitJob['split'].' - '.$splitJob['test_type_abbr']  ?></th>
<?php  endif ?>
<?php  endforeach  ?>
</tr>

-->
</tbody>
</table>


</div>
