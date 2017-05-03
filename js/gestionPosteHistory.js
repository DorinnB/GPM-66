$(document).ready(function() {

  var table = $('#table_GestionEp').DataTable({
    scrollY: '16vh',
    scrollCollapse: true,
    "scrollX": true,
    paging: false,
    info: false
  });


  // Setup - add a text input to each footer cell
  $(".dataTables_scrollFootInner tfoot th, .DTFC_LeftFootWrapper tfoot th").each(function() {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="' + title + '" / style="width:100%">');
  });

  table.columns().every(function() {
    var that = this;

    $('input', this.footer()).on('keyup change', function() {
      if (that.search() !== this.value) {
        that
        .search(this.value)
        .draw();
      }
    });
  });

  document.getElementById("table_GestionEp_filter").style.display = "none";


  table.columns.adjust().draw();

});


// Gestion Eprouvette
function gestionEp(idEp) {
  $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+idEp);
}
