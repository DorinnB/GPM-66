$("#deleteTechSplit").click(function(e) {
  var confirmation = confirm('Force a re-check ?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateTechSplit.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#table_ep').attr('data-idJob'),
        type : 'delete'
      }
      ,
      success : function(data, statut){
        //location.reload();
        $("#deleteTechSplit").html('Done');
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

$("#check.check0").click(function(e) {
  var confirmation = confirm('Check this item ?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateCheck.php',
      dataType: "json",
      data:  {
        id_tbljob:$('#table_ep').attr('data-idJob')
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

$("#planning").click(function(e) {
  var confirmation = confirm('Update Planning Insertion?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updatePlanning.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#table_ep').attr('data-idJob'),
        id_planning : $('#planning').attr('data-planning')
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

$("#report_send").click(function(e) {
  var confirmation = confirm('Update Report Emission ?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateReportSend.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#table_ep').attr('data-idJob'),
        id_reportSend : $('#report_send').attr('data-report_send')
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

$("#flecheUpJob").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextJob.php',
    dataType: "json",
    data:  {
      id_tbljob:$("#id_tbljob").val(),
      sens:'>'
    }
    ,
    success : function(data, statut){
      if (data['id_tbljob']!== undefined) {
        window.location.href = 'index.php?page=split&id_tbljob='+data['id_tbljob'];
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche de Job. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});
$("#flecheDownJob").click(function(e) {
  $.ajax({
    type: "POST",
    url: 'controller/previousNextJob.php',
    dataType: "json",
    data:  {
      id_tbljob:$("#id_tbljob").val(),
      sens:'<'
    }
    ,
    success : function(data, statut){
      if (data['id_tbljob']!== undefined) {
        window.location.href = 'index.php?page=split&id_tbljob='+data['id_tbljob'];
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la recherche de Job. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});


$("#export").contextmenu(function (event) {

    // Avoid the real one
    event.preventDefault();

    // Show contextmenu
    $("#export-menu").finish().toggle(100).

    // In the right position (the mouse)
    css({
        top: (event.pageY - 210) + "px",
        left: (event.pageX -150)+ "px"
    });
        $('#export-menu').load('controller/report-icone-controller.php?id_tbljob='+$('#id_tbljob').val());
});


$("#save").click(function() {
  save();
});

//Selon le navigateur utilisé, on detecte le style de transition utilisé
function whichTransitionEvent(){
  var t,
  el = document.createElement("fakeelement");

  var transitions = {
    "transition"      : "transitionend",
    "OTransition"     : "oTransitionEnd",
    "MozTransition"   : "transitionend",
    "WebkitTransition": "webkitTransitionEnd"
  }

  for (t in transitions){
    if (el.style[t] !== undefined){
      return transitions[t];
    }
  }
}

var transitionEvent = whichTransitionEvent();

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_ep').DataTable().draw();
  });
