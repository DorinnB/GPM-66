$(document).on("click", ".open-GestionEp", function () {
  alert($(this).data('id'));
     $("#id_gestionEp").html($(this).data('id'));
});
