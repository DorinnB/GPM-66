<link rel="stylesheet" href="css/splitTab.css">

	<ul  class="nav nav-pills tab">
		<?php foreach ($Tabs as $row): ?>
			<li onclick="goto('split','id_tbljob','<?= $row['id_tbljob'] ?>');">
				<a href="#" data-toggle="tab" style="background-color : <?= $row['statut_color'] ?>" class="<?=	$row['class']	?>"><?= $row['split'] ?>-<?= $row['test_type_abbr'] ?></a>
			</li>
		<?php endforeach ?>
	</ul>
