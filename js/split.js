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
        goto('split','id_tbljob',data['id_tbljob']);
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
        goto('split','id_tbljob',data['id_tbljob']);
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l insertion au planning. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
}
});

//Un click sur le bouton InOut ouvre le split correspondant
$("#inOutLoad").click(function() {
  goto('inOut','id_tbljob',$('#id_tbljob').val());
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
