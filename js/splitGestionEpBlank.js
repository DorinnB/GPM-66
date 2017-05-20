$(document).on("click", ".open-GestionEp", function () {
     //$("#id_gestionEp").html($(this).data('id'));
     $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+$(this).data('id'));
});
