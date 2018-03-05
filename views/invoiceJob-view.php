
<link href="css/weeklyReport.css" rel="stylesheet">
<?php
include('controller/weeklyReport-controller.php');
?>
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<form id="invoiceJob" method="POST" style="height:100%;">
			<div style="height:93%; overflow:auto; width:100%;">
				<?php foreach ($splits as $key => $value) :?>
					<div class="row">
						<div class="col-md-1">
							<?=	$value['split'].'-'.$value['test_type_abbr']	?>
						</div>
						<div class="col-md-11">
							<div class="row">
								<div class="col-md-1">
									test
								</div>
							</div>
						</div>



					</div>


				<?php endforeach ?>
			</div>
			<div style="height:7%; width:100%; padding:10px 0px;">
				<input type="submit" value="SAVE & PRINT" style="width:100%;" >
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="js/weeklyReport.js"></script>

<?php require('views/login-view.php');	?>
