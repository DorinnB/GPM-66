$(document).ready(function() {

  // DataTable
  var table = $('#table_group').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    filter:false,
    info: false,

    order: [ 0, "asc" ]
  } );

  var table = $('#table_Report').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    filter:false,
    info: false,

    order: [ 0, "asc" ]
  } );
} );



// Check Qualité
$(".report_Q").click(function(e) {
  if ($(this).attr('data-report_Q')>0) {

    var confirmation = confirm('UnCheck this Report ? Only Quality Manager should do this');
  }
  else {
    var confirmation = confirm('Check this Report ? Only Quality Manager should do this');
  }

  if (confirmation) {

    $.ajax({
      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $(this).attr('data-idtbljob')
      }
      ,
      success : function(data, statut){
        //reload

      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du check qualité du rapport. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  }
});
