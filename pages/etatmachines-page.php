<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<link href="css/etatmachines.css" rel="stylesheet">
<script type="text/javascript" src="js/etatmachines.js"></script>
<script src="lib/plotly/plotly-latest.min.js"></script>

<?php include('controller/etatmachines-controller.php'); ?>

<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid" style="height:100%">
		<div id="chartEtatMachine" style="height:95%; margin:20px;"></div>


<?php
	$traceX= "";
	$traceY1= "";
	$traceY2= "";
	$traceY3= "";
	$traceY4= "";
	$traceY5= "";
	$traceY6= "";

	foreach ($oEtatMachines->getAllEtatMachines_machine() as $row)	{
		$traceX .= ',"'.$row['machine'].' '.date("m-Y", strtotime($row['periode'])).'"';

		$traceY1 .=','.$row['cycling'];
		$traceY2 .=','.$row['rampToTemp'];
		$traceY3 .=','.$row['noncycling'];
		$traceY4 .=','.$row['noTest'];
		$traceY5 .=','.$row['waitingCustomer'];
		$traceY6 .=','.$row['waitingLab'];
	}
?>

<script>
var trace1 = {
  x: [''<?= $traceX	?>],
  y: [''<?= $traceY1	?>],
  name: 'Cycling',
	marker: {color: 'darkgreen'},
  type: 'bar'
};

var trace2 = {
	x: [''<?= $traceX	?>],
  y: [''<?= $traceY2	?>],
  name: 'RampToTemp',
	marker: {color: 'yellowgreen'},
  type: 'bar'
};

var trace3 = {
	x: [''<?= $traceX	?>],
  y: [''<?= $traceY3	?>],
  name: 'nonCycling',
	marker: {color: '#999900'},
  type: 'bar'
};

var trace4 = {
	x: [''<?= $traceX	?>],
  y: [''<?= $traceY4	?>],
  name: 'noTest',
	marker: {color: 'darkred'},
  type: 'bar'
};

var trace5 = {
	x: [''<?= $traceX	?>],
  y: [''<?= $traceY5	?>],
  name: 'waitingCustomer',
	marker: {color: 'coral'},
  type: 'bar'
};

var trace6 = {
	x: [''<?= $traceX	?>],
  y: [''<?= $traceY6	?>],
  name: 'waitingLab',
	marker: {color: 'tomato'},
  type: 'bar'
};


var data = [trace1, trace2, trace3, trace4, trace5, trace6];

var layout = {barmode: 'stack'};

Plotly.newPlot('chartEtatMachine', data, layout);

</script>


<!--
		<table id="table_etatmachines" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
			<thead>
				<tr>
					<th><acronym title='etatmachine'>Etat</acronym></th>
					<th><acronym title='Machine'>Machine</acronym></th>
					<th><acronym title='Test Type'>Test Type</acronym></th>
					<th><acronym title='Split'>Split</acronym></th>
					<th><acronym title='Statut'>Statut</acronym></th>
					<th><acronym title='Period'>Period</acronym></th>
					<th><acronym title='Cumul'>Cumul</acronym></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><acronym title='etatmachine'>Etat</acronym></th>
					<th><acronym title='Machine'>Machine</acronym></th>
					<th><acronym title='Test Type'>Test Type</acronym></th>
					<th><acronym title='Split'>Split</acronym></th>
					<th><acronym title='Statut'>Statut</acronym></th>
					<th><acronym title='Period'>Period</acronym></th>
					<th><acronym title='Cumul'>Cumul</acronym></th>
				</tr>
			</tfoot>
			<tbody>
				<php foreach ($oEtatMachines->getAllEtatMachines() as $row): ?>
					<tr>
						<td><= $row['etatmachine'] ?></td>
						<td><= $row['machine'] ?></td>
						<td><= $row['test_type_abbr'] ?></td>
						<td><= $row['id_tbljob'] ?></td>
						<td><= $row['statut'] ?></td>
						<td><= $row['periode'] ?></td>
						<td><= $row['cumul'] ?></td>
					</tr>
				<php endforeach ?>
			</tbody>
		</table>
	</div>
-->
</div>
<?php
include('controller/splitChart-controller.php');
require('views/login-view.php');
?>
