<div class="row jobTop" style="height:15%">
	<div class="col-md-2" style="height:100%"><?php include('jobNumero-view.php');?></div>
	<div class="col-md-2" style="height:100%">
		<p class="titre">Mat. :</p><p class="commentaire2" ><?= $split['ref_matiere'] ?></p>
		<p class="titre">Std. :</p><p class="commentaire2"><?= $split['matiere'] ?></p>
	</div>
	<div class="col-md-2" style="height:100%"><?php include('jobContact-view.php');?></div>
	<div class="col-md-2" style="height:100%"><p class="titre">Instructions :</p>
		<textarea class="commentaire" disabled><?= $split['info_jobs_instruction'] ?></textarea>
	</div>
	<div class="col-md-3" style="height:100%"><p class="titre">Commentaires :</p>
		<textarea class="commentaire" disabled><?= $split['info_jobs_commentaire'] ?></textarea>
	</div>
	<div class="col-md-1" style="height:100%">
		<div class="row" style="height:100%">

			<div class="col-md-6" id="inOutLoad" style="height:50%; padding:0px;">

				<button type="button" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
					<p style="font-size:small;">
						<?=	(explode('-',basename (parse_url($_SERVER['PHP_SELF'])["path"]))[0]=="inOut")?'Split':'InOut';	?>
						<img type="image" src="img/<?=	(explode('-',basename (parse_url($_SERVER['PHP_SELF'])["path"]))[0]=="inOut")?'home':'plane';	?>.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
					</p>
				</button>
			</div>

			<div class="col-md-6" id="schedule" style="height:50%; padding:0px;">
				<button type="button" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
					<p style="font-size:small;">
						<?=	(explode('-',basename (parse_url($_SERVER['PHP_SELF'])["path"]))[0]=="schedule")?'Split':'schedule';	?>
						<img type="image" src="img/<?=	(explode('-',basename (parse_url($_SERVER['PHP_SELF'])["path"]))[0]=="schedule")?'home':'calendar_yes';	?>.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
					</p>
				</button>
			</div>

			<div class="col-md-6" id="" style="height:50%; padding:0px;">

			</div>

			<div class="col-md-6" id="" style="height:50%">

			</div>
		</div>
	</div>
</div>
