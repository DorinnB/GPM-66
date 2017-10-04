$( function() {

  $( "#dateInOut" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

} );

//Un click sur le bouton InOut ouvre le split correspondant
$("#inOutLoad").click(function() {
  goto('inOut','id_tbljob',$('#id_tbljob').val());
});
//Un click sur le bouton schedule ouvre le split correspondant
$("#schedule").click(function() {
  goto('split','id_tbljob',$('#id_tbljob').val());
});


$("#save").css('cursor', 'pointer');



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
