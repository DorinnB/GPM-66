// Recuperation des postes
function lstPoste(idEp) {
  $('#id_poste').load('controller/lstPoste-controller.php?id_Ep='+idEp);
}

// Check Eprouvette
$("#openPrestart").click(function() {
  document.getElementById("newTest").style.display = "none";
  document.getElementById("prestart").style.display = "block";
  document.getElementById("prestart").className += " active";
  document.getElementById("1c").className += " active";
  document.getElementById("1a").style.display = "none";
});


$(document).ready(function(){
  $('#newTestForm > div > select').each(function() {
    if ($(this).val() != '0') {
      $('#submit_newTest').removeAttr('disabled');    //si la machine est deja presente, on enleve le disabled
    }
  });

  $('#newTestForm > div > select').change(function() {
    var empty = false;
    $('#newTestForm > div > select').each(function() {
      if ($(this).val() == '0') {
        empty = true;
      }
    });
    //si l'on a pas d'id_user, on n'autorise pas la validation du formulaire
    if ($('#id_userNewTest').val()=='0') {
      empty = true;
    }

    if (empty) {
      $('#submit_newTest').attr('disabled', 'disabled'); // updated according to http://stackoverflow.com/questions/7637790/how-to-remove-disabled-attribute-with-jquery-ie
    } else {
      $('#submit_newTest').removeAttr('disabled');
    }
  });
});



//newTest
$("#newTestForm").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/newTest-controller.php",
    data: $("#newTestForm").serialize(), // serializes the form's elements.
    success: function(data)
    {
      location.assign("controller/createFT-controller.php?id_ep="+$("#idEp").val());
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.

});

//Prestart
$("#prestartForm").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/updatePrestart.php",
    data: $("#prestartForm").serialize(), // serializes the form's elements.
    success: function(data)
    {
      //alert(data);
      location.assign("controller/createPrestart-controller.php?id_prestart="+data+"&id_ep="+$('#idEp').val());
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());

    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.
});

//prepa
$("#newTestCheck").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/newTestCheck-controller.php",
    data: $("#newTestCheck").serialize(), // serializes the form's elements.
    success: function(data)
    {
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$('#idEp').val());
    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.
});




function retest(id_ep, id_tbljob) {
  $.ajax({
    type: "POST",
    url: "controller/retest-controller.php",
    data: 'id_ep=' + id_ep,
    success: function(data)
    {
      $('#gestionEp').modal('toggle');
      goto('split', 'id_tbljob', id_tbljob);
    }
  });
}

function delTest(id_ep, id_tbljob) {
  $.ajax({
    type: "POST",
    url: "controller/delTest-controller.php",
    data: 'id_ep=' + id_ep,
    success: function(data)
    {
      $('#gestionEp').modal('toggle');
      goto('split', 'id_tbljob', id_tbljob);
    }
  });
}

function cancelTest(id_ep, id_tbljob) {
  $.ajax({
    type: "POST",
    url: "controller/cancelTest-controller.php",
    data: 'id_ep=' + id_ep,
    success: function(data)
    {
      $('#gestionEp').modal('toggle');
      goto('split', 'id_tbljob', id_tbljob);
    }
  });
}





$("#save_d_commentaire").click(function(e) {

  e.preventDefault();
  //$('#d_commentaire').val($('#d_commentaire_content').html());
  $.ajax({
    type: "POST",
    url: 'controller/update_d_commentaire.php',
    dataType: "json",
    data:  $("#update_d_commentaire").serialize()
    ,
    success : function(data, statut){
      $('#gestionEp').modal('hide');
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification des commentaires labo. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});




$(".document").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/document-controller.php',
    data:
    {
      tbl:"eprouvettes",
      id_tbl:$("#idEp").val(),
      type:$(this).attr('data-type')
    }
    ,
    success : function(data, statut){
      $("#doc_ep").html(data);
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de l\'affichage des documents. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});
