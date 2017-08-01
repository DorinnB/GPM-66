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
  goto('split','id_tbljob',$('#id_tbljob').val());
});

//Un click sur recommendation affiche le div ou le textarea
$("#flipRecommendation").click(function() {
  $('#inOut_recommendation_alt').toggleClass('flip');
  $('#inOut_recommendation').toggleClass('flip');

  $('#inOut_recommendation_alt').html($('#inOut_recommendation').val());
});
