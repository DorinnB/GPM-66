
function save() {

    $.ajax({
      type: "POST",
      url: 'controller/updateDataInput.php',
      dataType: "json",
      data:  $("#updateData").serialize()
      ,
      success : function(data, statut){
        location.assign("index.php?page=split&id_tbljob="+$("#id_tbljob").val());
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }


$( function() {

  $( "#DyT_Cust" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

} );
