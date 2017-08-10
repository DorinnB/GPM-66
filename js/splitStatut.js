function updateStatut(id_tbljob, id_statut){
  $.ajax({
		type: "POST",
		url: 'controller/updateStatut.php',
		dataType: "json",
    data: {
      id_tbljob : id_tbljob,
      id_statut: id_statut
    },
    success : function(data, statut){
      $("#splitStatut").css('background-color',data['statut_color']);
      $("#splitStatut").html(data['statut']);
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification du statut. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
}

function findStatut(id_tbljob){
  $.ajax({
		type: "POST",
		url: 'controller/findStatut-controller.php',
		dataType: "json",
    data: {
      id_tbljob : id_tbljob
    },
    success : function(data, statut){
      alert(data['statut']);
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR');
    }
  });
}
