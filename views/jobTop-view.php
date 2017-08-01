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
		<button type="button" class="btn btn-default btn-lg" style="width:100%; border-radius:10px; margin-top:10px;" id="inOutLoad">
			<p><?=	(explode('-',basename (parse_url($_SERVER['PHP_SELF'])["path"]))[0]=="inOut")?'Split':'InOut';	?></p>
			<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
		</button>
	</div>
</div>
