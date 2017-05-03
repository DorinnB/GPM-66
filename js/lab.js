$("#wrapper").addClass("toggled");


//Un click sur une ligne du tableau des jobs ouvre le split correspondant
$("#table_id >tbody > tr").click(function(e) {
  window.location.href = 'index.php?page=labo&id_tbljob='+$(this).data("id_tbljob");
});
