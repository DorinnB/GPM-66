<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<link href="css/followup.css" rel="stylesheet">
<script type="text/javascript" src="js/followup.js"></script>

<?php include('controller/followup-controller.php'); ?>

<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<table id="table_followup" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
			<thead>
				<tr>
					<th><acronym title='Phase'>Phase</acronym></th>
					<th><acronym title='N-Test EC'>NT</acronym></th>
					<th><acronym title='No Test MRSAS'>NS</acronym></th>
					<th><acronym title='Test % (en temps)'>T %</acronym></th>
					<th><acronym title='Cust.'>Cust.</acronym></th>
					<th><acronym title='Job'>Job</acronym></th>
					<th><acronym title='Split'>Split</acronym></th>
					<th><acronym title='Customer Name'>Cie</acronym></th>
					<th><acronym title='PO Number'>PO</acronym></th>
					<th><acronym title='Customer Instructions'>Cust. Inst</acronym></th>
					<th><acronym title='Tests type'>Type</acronym></th>
					<th><acronym title='SubC Companie'>SubC</acronym></th>
					<th><acronym title='Material STD'>Mat.Std</acronym></th>
					<th><acronym title='Drawing'>Drawing</acronym></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Phase</th>
					<th>NT</th>
					<th>NS</th>
					<th>T %</th>
					<th>Cust.</th>
					<th>Job</th>
					<th>Split</th>
					<th>Cie</th>
					<th>PO</th>
					<th>Cust. Inst</th>
					<th>Type</th>
					<th>SubC</th>
					<th>Mat.Std</th>
					<th>Drawing</th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($oFollowup->getAllFollowup($filtreFollowup) as $row): ?>
					<tr>

						<td class="popover-markup" data-placement="right" style="background-color:<?= $row['statut_color'] ?>;"><a href="index.php?page=labo&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['etape'] ?></a>
							<div class="content hide">
								<div class="form-group">
									<?= $row['statut'] ?>
								</div>
							</div>
						</td>


						<td><?= $row['nbtest'] ?></td>
						<td><?= $row['nbep'] ?></td>
						<td class="progress"><meter value="<?= $row['nbpercent'] ?>" min="0" max="100" color-sttut="<?= $row['nbpercent'] ?>"><?= $row['nbpercent'] ?></meter></td>
						<td><?= $row['customer'] ?></td>
						<td><a href="index.php?page=labo&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['job'] ?></a></td>
						<td><?= $row['split'] ?></td>
						<td><acronym title='<?= $row['entreprise'] ?>'><?= $row['entreprise_abbr'] ?></acronym></td>

						<?php if (strlen($row['po_number'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['po_number']=="" OR strlen($row['po_number'])<15)?$row['po_number']:substr($row['po_number'],0,15)." [...]" ?>
								<div class="head hide">Instructions</div>
								<div class="content hide">
									<div class="form-group">
										<textarea class"bubble_instruction" rows="5" cols="50" style="width:100%;" disabled><?= $row['po_number'] ?></textarea>
									</div>
								</div>
							<?php else: ?>
								<td><?= $row['po_number'] ?></td>
							<?php endif ?>
						</td>

						<?php if (strlen($row['instruction'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['instruction']=="" OR strlen($row['instruction'])<15)?$row['instruction']:substr($row['instruction'],0,15)." [...]" ?>
								<div class="head hide">Instructions</div>
								<div class="content hide">
									<div class="form-group">
										<textarea class"bubble_instruction" rows="5" cols="50" style="width:100%;" disabled><?= $row['instruction'] ?></textarea>
									</div>
								</div>
							<?php else: ?>
								<td><?= $row['instruction'] ?></td>
							<?php endif ?>
						</td>

						<td><?= $row['test_type_abbr'] ?></td>
						<td><?= $row['matiere'] ?></td>
						<td><?= $row['dessin'] ?></td>
						<td><?= $row['DyT_expected'] ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>

</div>
<?php
require('views/login-view.php');
?>
