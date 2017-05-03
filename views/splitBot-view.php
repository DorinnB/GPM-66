<div class="row splitBot" style="height:20%">
	<div class="col-md-3" style="height:100%"><p class="">Instructions :</p><textarea class="commentaire<?= (($_GET['modif']=="dataInput")?"Editable":"")	?>" name="tbljob_instruction" form="updateData"><?= $split['tbljob_instruction'] ?></textarea></div>
	<div class="col-md-3" style="height:100%"><p class="">Commentaires :</p><textarea class="commentaire<?= (($_GET['modif']=="eprouvetteConsigne")?"Editable":"")	?>" name="tbljob_commentaire"><?= $split['tbljob_commentaire'] ?></textarea></div>
	<div class="col-md-4" style="height:100%"><p class="">Commentaires Qualité :</p><textarea class="commentaire<?= (($_GET['modif']=="eprouvetteValue")?"Editable":"")	?>" name="tbljob_commentaire_qualite"><?= $split['tbljob_commentaire_qualite'] ?></textarea></div>
	<div class="col-md-2" id="split-bouton" style="height:100%">

		<div class="row" style="height:100%">
			<div class="col-md-6" style="height:33%">
				<label>Planning</label>
				<input type="checkbox" value="">
			</div>
			<div class="col-md-6" id="save" style="height:33%">
				<img type="image" src="img/save.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
			</div>

			<div class="col-md-6 check<?=	$split['checked']	?>" id="check" style="height:33%">
				<img type="image" src="img/<?=	($split['checked']==0)?'cross.png':'check.png'	?>" style="max-width:100%; max-height:100%;padding:5px 0px;display: block; margin: auto;" />
			</div>
			<div class="col-md-6" style="height:33%">CHK</div>

			<div class="col-md-6" style="height:33%">
				<label>Completed</label>
				<input type="checkbox" value="">
			</div>
			<div class="col-md-6" id="export" style="height:33%">
				<a href="controller/createReport-controller.php?id_tbljob=<?=	$split['id_tbljob']	?>">

					<img type="image" src="img/export.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
				</a>
			</div>
		</div>



	</div>
</div>
