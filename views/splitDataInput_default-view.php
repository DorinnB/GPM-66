<link href="css/splitData.css" rel="stylesheet">

<div class="col-md-12" style="height:85%">
  <form type="GET" action="controller/updateData.php" id="updateData">
    <input type="hidden" name="id_tbljob" value="<?= $split['id_tbljob'] ?>">
    <div class="form-group">
      <label for="Spec">Spec :</label>
      <input type="text" class="form-control" name="specification" value="<?= $split['specification'] ?>">
    </div>


    <div class="form-group">
      <label for="Consigne 1">Consigne 1 :</label>
      <select class="form-control" name="c_type_1">
        <?php foreach ($Consigne as $row): ?>
        	<option value="<?= $row['id_consigne_type'] ?>" <?=  ($row['id_consigne_type']==$split['id_c_type_1'])?'selected':''    ?>><?= $row['consigne_type'] ?></option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
      <label for="Consigne 2">Consigne 2 :</label>
      <select class="form-control" name="c_type_2">
        <?php foreach ($Consigne as $row): ?>
        	<option value="<?= $row['id_consigne_type'] ?>" <?=  ($row['id_consigne_type']==$split['id_c_type_2'])?'selected':''    ?>><?= $row['consigne_type'] ?></option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
      <label for="Units">Units :</label>
      <select class="form-control" name="c_unite">
        <option value="%" <?=  ($split['c_unite']=="%")?'selected':'' ?>>%</option>
        <option value="kN" <?=  ($split['c_unite']=="kN")?'selected':'' ?>>kN</option>
        <option value="MPa" <?=  ($split['c_unite']=="MPa")?'selected':'' ?>>MPa</option>
      </select>
    </div>

    <div class="form-group">
      <label for="Waveform">Waveform :</label>
      <select class="form-control" name="waveform">
        <option value="Sinus" <?=  ($split['waveform']=="Sinus")?'selected':'' ?>>Sinus</option>
        <option value="Triangle" <?=  ($split['waveform']=="Triangle")?'selected':'' ?>>Triangle</option>
        <option value="Rampe" <?=  ($split['waveform']=="Rampe")?'selected':'' ?>>Rampe</option>
      </select>
    </div>

    <div class="form-group">
      <label for="Dy T">Dy T :</label>
      <input type="text" class="form-control" name="test_leadtime" id="test_leadtime" value="<?= $split['test_leadtime'] ?>">
    </div>

  </form>
</div>
<script type="text/javascript" src="js/splitDataInput.js"></script>
