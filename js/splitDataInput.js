$("#save").css('cursor', 'pointer');

$("#save").click(function(e) {

  e.preventDefault();

  $.ajax({
    type: "POST",
    url: 'controller/updateData.php',
    dataType: "json",
    data:  $("#updateData").serialize()
    ,
    success : function(data, statut){
      goto('split','id_tbljob',data['id_tbljob']);
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
});



$( function() {

  $( "#test_leadtime" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

} );
$( function() {

  $( "#DyT_expected" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

} );
