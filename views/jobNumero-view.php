<input type="hidden" id="id_tbljob" value="<?=	$split['id_tbljob']	?>">

<div class="row" style="height:100%">
	<div class="col-md-12" id="job" style="height:25%"><a href="index.php?page=labo&id_tbljob=<?= $split['id_tbljob'] ?>"><?= $split['customer'].'&nbsp;-&nbsp;'.$split['job'] ?></a></div>
	<div class="col-md-12" style="height:25%">



		<a href=
<?php if (isset($_COOKIE['id_user'])): ?>
	"index.php?page=updateJob&id_tbljob=<?= $split['id_tbljob'] ?>"
<?php else: ?>
	"#" onclick="alert('Please Login then refresh the browser');"
<?php endif; ?>>




			<button type="button" class="btn btn-default" style="width:100%;">Modification</button>


		</a>


	</div>
	<div class="col-md-12" style="height:25%">PO : <?= $split['po_number'] ?></div>
	<div class="col-md-12" style="height:25%">Quote : <?= $split['devis'] ?></div>
</div>
