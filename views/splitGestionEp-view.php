
<script type="text/javascript" src="js/splitGestionEp.js"></script>
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header" style="height:15%;">
      <h2 class="modal-title" style="text-align: center;">
        Specimen :&nbsp;<?= (($eprouvette['prefixe']=="")?"": $eprouvette['prefixe'].' - ').$eprouvette['nom_eprouvette']  ?>
        <button class="btn" data-toggle="modal" data-target="#login-modal" style="float:right;">
          <span class="glyphicon glyphicon-log-in"></span>
        </button>
      </h2>
    </div>
    <div class="modal-body" style="height:83%;">
      <div id="exTab1" class="container-fluid">
        <div class="row" style="height:100%;">
          <div class="col-sm-4" style="height:100%;">
            <ul  class="nav nav-pills nav-stacked" style="height:100%;">
              <li style="height:25%;" id="newTest">
                <a href="#1a" data-toggle="tab" style="line-height:7vh;"><p>NEW TEST</p></a>
              </li>
              <li style="height:25%;" id="prepa">
                <a href="#1b" data-toggle="tab" style="line-height:7vh;"><p>PREPA</p></a>
              </li>
              <li style="height:25%;" id="eval">
                <a href="#1e" data-toggle="tab" style="line-height:7vh;"><p>EVAL</p></a>
              </li>
              <li style="height:25%;" id="document">
                <a href="#1d" data-toggle="tab" style="line-height:7vh;"><p>DOC</p></a>
              </li>
              <li style="height:25%;" id="prestart">
                <a href="#1c" data-toggle="tab" style="line-height:7vh;"><p>PRESTART CHECKLIST</p></a>
              </li>
              <li style="height:25%;" id="retest"><a href="#2a" data-toggle="tab" style="line-height:7vh;">RETEST</a>
              </li>
              <li style="height:25%;" id="delete"><a href="#3a" data-toggle="tab" style="line-height:7vh;">DELETE</a>
              </li>
              <li style="height:25%;"id="cancel"><a href="#3b" data-toggle="tab" style="line-height:7vh;">CANCEL</a>
              </li>
            </ul>
          </div>


          <div class="col-sm-8 carre" style="height:100%;">
            <div class="tab-content clearfix" style="height:100%;">

              <div class="tab-pane" id="1a" style="height:100%;">
                <h3 style="height:100%;">
                  <form class="form-group" style="height:100%;" id="newTestForm">
                    <div style="height:25%;">
                      <label for="id_prestart">Machine :
                        <button type="button" class="btn btn-default btn-sm" id="openPrestart">
                          <span class="glyphicon glyphicon-floppy-open" aria-hidden="true"></span>
                        </button>
                      </label>
                      <select class="form-control" id="id_prestart" name="id_prestart">
                        <option value="0">No Prestart Recorded</option>
                        <?php foreach ($lstPoste->getAllPrestartSplit($_GET['idEp']) as $row): ?>
                          <option value="<?= $row['id_prestart'] ?>" <?=  ((isset($_COOKIE['id_machine']) and ($_COOKIE['id_machine']==$row['id_machine']))?"selected":"" )  ?>><?= $row['machine'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div style="height:25%;">
                      <label for="user">Operator :</label>
                      <input type="text" class="form-control" name="user" value="<?= $_COOKIE['technicien'] ?>" readonly>
                    </div>


                    <div style="height:25%;">
                      <?php if ($eprouvette['c_checked']==0 OR $eprouvette['checked']==0) :  ?>
                        <p style="color:red; padding-top:10px;">
                          <?= ($eprouvette['c_checked']==0)?'Consigne Unchecked !':'' ?>
                          <?= ($eprouvette['checked']==0)?'<br/>Job Unchecked !':'' ?>
                        </p>
                        <input type="hidden" name="checker" value="0">
                      <?php else :  ?>

                        <label for="checker">Checker :</label>
                        <select class="form-control" id="checker" name="checker">
                          <?php include 'lstTech-view.php'; ?>
                        </select>
                        <script type="text/javascript">
                        //on enleve le nom de l'operateur
                        $("#checker option[value='<?= $_COOKIE['id_user'] ?>']").remove();
                        </script>

                      <?php endif ?>
                    </div>
                    <div style="height:25%;">
                      <button type="submit" class="btn btn-default" id="submit_newTest" disabled="disabled">Record Test Number</button>
                    </div>
                    <input type="hidden" id="idEp" name="idEp" value="<?=  $_GET['idEp'] ?>">
                    <input type="hidden" name="id_user" value="<?=  $_COOKIE['id_user'] ?>">
                  </form>
                </h3>
              </div>

              <div class="tab-pane" id="1b" style="height:100%;">
                <h3>
                  <p>File:  <?= $eprouvette ['n_fichier'] ?></p>
                  <p>Test:  <?= $eprouvette ['n_essai'] ?></p>
                  <?php if ($eprouvette['c_checked']==0 OR $eprouvette['checked']==0) :  ?>
                    <p style="color:red; padding-top:10px;">
                      <?= ($eprouvette['c_checked']==0)?'Consigne Unchecked !':'' ?>
                      <?= ($eprouvette['checked']==0)?'<br/>Job Unchecked !':'' ?>
                    </p>
                  <?php endif  ?>
                </h3>
                <form class="form-group" id="newTestCheck" style="padding:20px;">
                  <input type="hidden" id="idEp" name="idEp" value="<?=  $_GET['idEp'] ?>">
                  <input type="hidden" name="checker" value="<?=  $_COOKIE['id_user'] ?>">
                  <button type="submit" class="btn btn-default" id="submit_newTestCheck" <?= ($eprouvette ['id_operateur']==$_COOKIE['id_user'])?"disabled":"" ?>>Check Test</button>
                </form>
              </div>
              <div class="tab-pane" id="1e" style="height:100%;">
                <div class="row" style="font-size:130%;">
                  <div class="col-md-3">File:</b>
                  </div>
                  <div class="col-md-3"><b><?= $eprouvette ['n_fichier'] ?></b>
                  </div>
                  <div class="col-md-3">Test:</b>
                  </div>
                  <div class="col-md-3"><b><?= $eprouvette ['n_essai'] ?></b>
                  </div>
                </div>
                <div class="row" style="font-size:130%;">
                  <div class="col-md-3">Cycles:</b>
                  </div>
                  <div class="col-md-3"><b><?= $eprouvette ['Cycle_final'] ?></b>
                  </div>
                  <div class="col-md-3">State:</b>
                  </div>
                  <div class="col-md-3"><b><?= $eprouvette ['currentBlock'] ?></b>
                  </div>
                </div>
                <form class="form-group" style="height:100%;" id="update_d_commentaire">
                  <div class="row" style="padding: 0px 10px;">
                    <div class="col-md-6">
                      <div class="col-md-12">
                        <label for="Rupture">Rupture :</label>
                        <input type="text" class="form-control" name="Rupture" value="<?= $eprouvette ['Rupture'] ?>" <?= ($eprouvette ['Rupture']=="")?"":"disabled"  ?>>
                      </div>
                      <div class="col-md-12">
                        <label for="Fracture">Fracture :</label>
                        <input type="text" class="form-control" name="Fracture" value="<?= $eprouvette ['Fracture'] ?>"<?= ($eprouvette ['Fracture']=="")?"":"disabled"  ?>>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <button type="button" id="flecheUp" style="width:100%;background-color:dimgray;" title="Previous Test Same Frame">
                        <span class="glyphicon glyphicon-chevron-up"></span>
                      </button>
                      <div class="col-md-12">
                        <div class="col-md-5 col-md-offset-1 check<?=	$eprouvette['check_rupture']	?>" id="check_rupture" data-dTechnicien="<?= $eprouvette['d_technicien'] ?>">
                          <img type="image" src="img/<?=	($eprouvette['check_rupture']==0)?'cross.png':'check.png'	?>" style="height:69px;padding:5px 0px; margin: auto;" title="<?=	($eprouvette['check_rupture']==0)?'Check Rupture/Fracture ('.$eprouvette['d_technicien'].')':$eprouvette['check_rupture']	?>">
                        </div>
                        <div class="col-md-5 col-md-offset-1">
                          <img type="image" src="img/warning_<?= $iconeFlagQualite ?>.png" style="height:69px; padding:5px 0px; margin: auto;" title="<?=	($eprouvette['flag_qualite']==0)?'Quality Flag':$eprouvette['flag_qualite']	?>"  id="flagQualite" data-flagQualite="<?= $eprouvette['flag_qualite'] ?>"  data-idepflagqualite="<?= $eprouvette['id_eprouvette'] ?>">
                        </div>
                      </div>
                      <button type="button" id="flecheDown" style="width:100%;background-color:dimgray;" title="Next Test Same Frame">
                        <span class="glyphicon glyphicon-chevron-down"></span>
                      </button>
                    </div>
                  </div>
                  <div class="row" style="padding: 0px 10px;">
                    <b style="float:left;">Lab Observation</b><br/>
                    <textarea class="form-control" id="d_commentaire" name="d_commentaire"  style="resize:none;background-color:#5B9BD5;border-width:0px; color:white;"><?= $eprouvette['d_commentaire'] ?></textarea>
                    <div id="save_d_commentaire" style="height:7%; width:14%; background-color:#5B9BD5; float:right;cursor:pointer;">
                      <img type="image" src="img/save.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
                    </div>
                  </div>
                  <input type="hidden" id="idEp" name="idEp" value="<?=  $_GET['idEp'] ?>">
                  <input type="hidden" name="technicien" value="<?=  $_COOKIE['technicien'] ?>">
                </form>
              </div>

              <div class="tab-pane" id="1c" style="height:100%;">
                <form class="form-group" style="height:100%;" id="prestartForm" onkeypress="return event.keyCode != 13;">
                  <div class="row">
                    <div class="col-sm-6">
                      <label>Machine</label>
                      <select class="form-control" name="id_poste">
                        <?php foreach ($lstPoste->getAllPoste() as $row): ?>
                          <option value="<?= $row['id_poste'] ?>" <?=  ((isset($_COOKIE['id_machine']) and ($_COOKIE['id_machine']==$row['id_machine']))?"selected":"" )  ?>><?= $row['machine'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="col-sm-6">
                      <label>Job</label>
                      <input type="text" class="form-control" placeholder="<?= $eprouvette['job'] ?>" disabled>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <label>Shunt Cal</label>
                      <input type="text" class="form-control" name="shunt_cal" placeholder="Shunt Cal">
                    </div>
                    <div class="col-sm-6">
                      <label>Tune Verification</label>
                      <label class="radio checkbox"><input type="radio" name="tune" value="1"> Dummy</label>
                      <label class="radio checkbox"><input type="radio" name="tune" value="2"> Same Param.</label>
                      <label class="radio checkbox"><input type="checkbox" name="custom_frequency" value="2">Custom Frequency</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <label class="checkbox">
                        <input type="checkbox" name="valid_alignement"> Alignement
                      </label>
                    </div>
                    <div class="col-sm-6">
                      <label class="checkbox">
                        <input type="checkbox" name="valid_extenso"> Cal Extenso
                      </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <label class="checkbox">
                        <input type="checkbox" name="valid_temperature"> Cal Temperature
                      </label>
                    </div>
                    <div class="col-sm-6">
                      <label class="checkbox">
                        <input type="checkbox" name="valid_temperature_line"> Cal Temp. Line
                      </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <label class="checkbox">
                        <input type="checkbox" name="signal_true"> True Signal
                      </label>
                    </div>
                    <div class="col-sm-6">
                      <label class="checkbox">
                        <input type="checkbox" name="signal_tapered"> Tapered Signal
                      </label>
                    </div>
                  </div>
                  <div style="height:25%;">
                    <button type="submit" class="btn btn-default" id="submit_prestart">Record Prestart Checklist</button>
                  </div>
                  <input type="hidden" id="idEp" name="idEp" value="<?=  $_GET['idEp'] ?>">
                  <input type="hidden" name="id_user" value="<?=  $_COOKIE['id_user'] ?>">
                  <input type="hidden" name="id_tbljob" value="<?= $eprouvette['id_job'] ?>">
                </form>
              </div>

              <div class="tab-pane" id="1d" style="height:100%;">
                <!--              <a href="controller/createFT-controller.php?id_ep=<?= $_GET['idEp'] ?>" style="display:inline-block;">
              -->
              <figure style="display:inline-block;" class="document" data-type="FT">
                <img  src="img/excel-FT.png" height="90" width="60"  alt="" />
                <figcaption>FT (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'FT'); ?>)</figcaption>
              </figure>


              <a href="controller/createPrestart-controller.php?id_prestart=<?= $eprouvette['id_prestart'] ?>&id_ep=<?= $_GET['idEp'] ?>" style="display:inline-block;">
                <figure>

                  <img  src="img/excel-Prestart.png" height="90" width="60" alt="" />
                  <figcaption>Prestart</figcaption>
                </figure>
              </a>

              <figure style="display:inline-block;" class="document" data-type="Restart">
                <img  src="img/excel-Restart.png" height="90" width="60"  alt="" />
                <figcaption>Restart (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'Restart'); ?>)</figcaption>
              </figure>

              <!--          <div style="position:relative; height:90px;display:inline-block;">
              <div style="position:absolute;z-index:1; height:100%">
              <img src="img/excel-Restart.png" style="height:100%">
            </div>
            <div style="position:absolute;top:70px; width:60px; height:30px; z-index:2;font-size:100%; color:black;">
            <center><b><?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'Restart'); ?></b></center>
          </div>
          <div style="position:absolute;top:90px; width:60px; height:30px; z-index:2;font-size:100%;">
          <center>Restart</center>
        </div>
      </div>
      <div style="position:relative; height:90px;display:inline-block;">
      <div style="position:absolute;z-index:1; height:100%">
      <img src="img/excel-IRR.png" style="height:100%">
    </div>
    <div style="position:absolute;top:70px; width:60px; height:30px; z-index:2;font-size:100%; color:black;">
    <center><b><?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'IRR'); ?></b></center>
  </div>
  <div style="position:absolute;top:90px; width:60px; height:30px; z-index:2;font-size:100%;">
  <center>IRR</center>
</div>
</div>

-->
<figure style="display:inline-block;" id="doc_IRR" class="document" data-type="IRR">
  <img  src="img/excel-IRR.png" height="90" width="60"  alt="" />
  <figcaption>IRR (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'IRR'); ?>)</figcaption>
</figure>

<figure style="display:inline-block;" class="document" data-type="CAR">
  <img  src="img/excel-Corrective.png" height="90" width="60"  alt="" />
  <figcaption><acronym title="Corrective Action Request">CAR</acronym> (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'CAR'); ?>)</figcaption>
</figure>

<figure style="display:inline-block;" class="document" data-type="Other">
  <img  src="img/doc.png" height="40" width="40"  alt="" />
  <figcaption><acronym title="Other">Oth</acronym> (<?= $oDocument->nbDocuments('eprouvettes',$eprouvette['id_eprouvette'],'Other'); ?>)</figcaption>
</figure>



<div id="doc_ep" style=height:40%;>

</div>

</div>


<div class="tab-pane" id="2a" style="height:100%;">
  <h3 style="height:100%;">
    <div class="row">
      <div class="col-md-12">
        <p>Do you really want to retest this specimen ?</p>
      </div>
    </div>
    <div class="row">
      <button type="submit" class="btn btn-default" onclick="retest('<?= $eprouvette ['id_eprouvette'] ?>','<?= $eprouvette ['id_job'] ?>');">
        Retest this specimen
      </button>
    </div>
  </h3>
</div>
<div class="tab-pane" id="3a" style="height:100%;">
  <h3 style="height:100%;">
    <div class="row">
      <div class="col-md-12">
        <p>Do you really want to delete this restart ?</p>
      </div>
    </div>
    <div class="row">
      <button type="submit" class="btn btn-default" onclick="delTest('<?= $eprouvette ['id_eprouvette'] ?>','<?= $eprouvette ['id_job'] ?>');">
        Delete this restart
      </button>
    </div>
  </h3>
</div>
<div class="tab-pane" id="3b" style="height:100%;">
  <h3 style="height:100%;">
    <div class="row">
      <div class="col-md-12">
        <p>Do you really want to cancel the test <?= $eprouvette ['n_fichier'] ?></p>
        <p style="font-size:small;">The file number will be lost.
          <br/>
          Changer criere d'apparition sur demarrage TS au lieu affichage cycle ?</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">Job :</div>
        <div class="col-md-6"><?= $eprouvette ['customer'].'-'.$eprouvette ['job']. '-'.$eprouvette ['split'].' ('.$eprouvette ['test_type'].')' ?></div>
      </div>
      <div class="row">
        <div class="col-md-6">Test Nb :</div>
        <div class="col-md-6"><?= $eprouvette ['n_essai'] ?></div>
      </div>
      <div class="row">
        <div class="col-md-6">Machine :</div>
        <div class="col-md-6"><?= $eprouvette ['machine'] ?></div>
      </div>
      <div class="row">
        <button type="submit" class="btn btn-default" onclick="cancelTest('<?= $eprouvette ['id_eprouvette'] ?>','<?= $eprouvette ['id_job'] ?>');">
          Cancel this test
        </button>
      </div>
    </h3>
  </div>
  <div class="tab-pane" id="logon" style="height:100%; display:none;">
    <h3 style="height:100%;">
      <div class="row">
        <div class="col-md-12">
          <p><br/><br/>You need to login to show the menu.</p>
        </div>
      </div>
    </h3>
  </div>
</div>
</div>
</div>

<div class="modal-footer">
  <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
</div>
</div>

</div>
</div>


</div>
