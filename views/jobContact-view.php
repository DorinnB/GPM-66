<div class="row" style="height:100%">
	<div class="col-md-12" id="compagnie" style="height:20%"><?= $split['compagnie'] ?></div>
	<div class="col-md-12" style="height:20%">
		<acronym title="Tel : <?=	$split['telephone']	?>"><?= $split['lastname'].' '.$split['surname'] ?></acronym>
		<acronym title="Send Email to Customer(s)">
		<a href="
		mailto: <?= $split['email']	?>
		?subject=Job : <?= $split['customer'].'&nbsp;-&nbsp;'.$split['job'] ?>
		&cc=<?= $split['email2']	?>;<?= $split['email3']	?>;<?= $split['email4']	?>
		&body=
		">
		<span class="glyphicon glyphicon-envelope"></span>
	</a>
	</acronym></div>
	<div class="col-md-12" style="height:20%">		<acronym title="Tel : <?=	$split['telephone2']	?>"><?= $split['lastname2'].' '.$split['surname2'] ?></acronym></div>
	<div class="col-md-12" style="height:20%">		<acronym title="Tel : <?=	$split['telephone3']	?>"><?= $split['lastname3'].' '.$split['surname3'] ?></acronym></div>
	<div class="col-md-12" style="height:20%">		<acronym title="Tel : <?=	$split['telephone4']	?>"><?= $split['lastname4'].' '.$split['surname4'] ?></acronym></div>
</div>
