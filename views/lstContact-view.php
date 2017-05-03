<option value="0">-</option>
<?php foreach ($ref_customer as $row): ?>
	<option value="<?= $row['id_contact'] ?>" <?=  (($_GET['id_contact']== $row['id_contact'])?"selected":"")  ?>><?= $row['lastname'][0] ?>. <?= $row['surname'] ?></option>
<?php endforeach ?>
