
<link href="css/invoiceJob.css" rel="stylesheet">

<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<form id="invoiceJob" method="POST" style="height:100%;">
			<div style="height:93%; overflow:auto; width:100%;">
				<?php foreach ($splits as $key => $value) :?>
					<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="<?=	$value['split'].'-'.$value['test_type_abbr']	?>">
						<p class="title">
							<div class="splitInvLine">
									<div class="row">
										<div class="col-md-1 code">
											  Metcut Code
										</div>
										<div class="col-md-6 pricingList" style="text-align:left;">
											  Description
										</div>
										<div class="col-md-1 qteGPM">
											  Qté GPM
										</div>
										<div class="col-md-1 qteUser">
											  Qté User
										</div>
										<div class="col-md-1 priceUnit">
											  Prix Unitaire
										</div>
										<div class="col-md-1 totalGPM">
											  Total GPM
										</div>
										<div class="col-md-1 totalUser">
											  Total User
										</div>
									</div>


							</div>
						</p>
						<p class="title">

							<select class="form-control addInvLine">
								<option value="No" >Add an Invoice Line</option>
								<option value="0" data-prodCode="" data-OpnCode=" " data-pricingList="Custom" data-price="">Custom</option>
								<?php foreach ($oInvoices->getAllInvoiceList($value['id_tbljob']) as $row): ?>
									<option value="<?= $row['id_pricingList'] ?>" data-prodCode="<?= $row['prodCode'] ?>" data-OpnCode="<?= $row['OpnCode'] ?>" data-pricingList="<?= $row['pricingList'] ?>" data-price="<?= $row['EURO'] ?>"><?=  $row['prodCode'].'-'.$row['OpnCode'].' '.$row['pricingList'] ?></option>
									<?php endforeach ?>
								</select>
							</p>
						</div >
					<?php endforeach ?>


						<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="OTHERS">
							<p class="title">
								<div class="splitInvLine">
										<div class="row">
											<div class="col-md-1 code">
													Metcut Code
											</div>
											<div class="col-md-6 pricingList">
													Description
											</div>
											<div class="col-md-1 qteGPM">
													Qté GPM
											</div>
											<div class="col-md-1 qteUser">
													Qté User
											</div>
											<div class="col-md-1 priceUnit">
													Prix Unitaire
											</div>
											<div class="col-md-1 totalGPM">
													Total GPM
											</div>
											<div class="col-md-1 totalUser">
													Total User
											</div>
										</div>


								</div>
							</p>
							<p class="title">
								<span class="name">New Invoice Line :</span>
								<select class="form-control addInvLine">
									<option value="No" >Add an Invoice Line</option>
									<option value="0" data-prodCode="" data-OpnCode=" " data-pricingList="Custom" data-price="">Custom</option>
									<?php foreach ($oInvoices->getAllInvoiceList() as $row): ?>
										<option value="<?= $row['id_pricingList'] ?>" data-prodCode="<?= $row['prodCode'] ?>" data-OpnCode="<?= $row['OpnCode'] ?>" data-pricingList="<?= $row['pricingList'] ?>" data-price="<?= $row['EURO'] ?>"><?=  $row['prodCode'].'-'.$row['OpnCode'].' '.$row['pricingList'] ?></option>
										<?php endforeach ?>
									</select>
								</p>
							</div >

				</div>
				<div style="height:7%; width:100%; padding:10px 0px;">
					<input type="submit" value="SAVE & PRINT" style="width:100%;" >
				</div>
			</form>
		</div>
	</div>


	<div class="row" id="invLineVierge" style="display:none;">
		<div class="col-md-1 code"><input class="form-control" name="code" value="" type="text" disabled></div>
		<div class="col-md-6 pricingList"><input class="form-control" name="pricingList" value="" type="text" disabled></div>
		<div class="col-md-1 qteGPM"><input class="form-control" name="qteGPM" value="" type="text" disabled></div>
		<div class="col-md-1 qteUser"><input class="form-control" name="qteUser" value="" type="text"></div>
		<div class="col-md-1 priceUnit"><input class="form-control" name="priceUnit" value="" type="text"></div>
		<div class="col-md-1 totalGPM"><input class="form-control" name="totalGPM" value="" type="text" disabled></div>
		<div class="col-md-1 totalUser"><input class="form-control" name="totalUser" value="" type="text"></div>
	</div>

	<?php require('views/login-view.php');	?>
