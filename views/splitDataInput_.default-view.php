<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">


    <div class="form-group">
      <label for="ref_customerST">Cust. #</label>
      <select id="ref_customerST" name="ref_customerST" class="form-control">
        <?php foreach ($ref_customerST as $row): ?>
          <option value="<?= $row['ref_customer'] ?>" <?=  ($split['id_entrepriseST']== $row['ref_customer'])?"selected":""  ?>><?= $row['ref_customer'] ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="form-group">
      <label for="id_contactST">Report Contact :</label>
      <select id="id_contactST" name="id_contactST" class="form-control">
        <option>Please choose from above</option>
      </select>
    </div>
    <div class="form-group">
      <label for="refSubC">Ref SubC :</label>
      <input type="text" class="form-control" name="refSubC" value="<?= $split['refSubC'] ?>">
    </div>

    <div class="form-group">
      <label for="Spec">Spec :</label>
      <input type="text" class="form-control" name="specification" value="<?= $split['specification'] ?>">
    </div>

    <div class="form-group">
      <label for="DyT_expected">DyT_expected :</label>
      <input type="text" class="form-control" name="DyT_expected" id="DyT_expected" value="<?= $split['DyT_expected'] ?>">
    </div>

    <div class="form-group">
      <label for="Dy T">Dy T :</label>
      <input type="text" class="form-control" name="test_leadtime" id="test_leadtime" value="<?= $split['test_leadtime'] ?>">
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
<script>
$("#ref_customerST").change(function() {
  $("#id_contactST").load("controller/lstContact-controller.php?id_contact=<?= $split['id_contactST'] ?>&ref_customer=" + $("#ref_customerST").val());
}).change();

</script>
