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


//Un click sur le bouton InOut ouvre le split correspondant
$("#inOutLoad").click(function() {
  goto('inOut','id_tbljob',$('#id_tbljob').val());
});
