$(document).ready(function() {
  // Setup - add a text input to each footer cell
  $('#table_ep tfoot th').each( function (i) {
    var title = $('#table_ep thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="" data-index="'+i+'" style="width:100%;"/>' );
  } );

  // DataTable
  var table = $('#table_ep').DataTable( {
    scrollY:        "50vh",
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    info: false,
    fixedColumns:   {leftColumns: 4},
    order: [[ 0, "asc" ],[3, "asc" ]],
    columnDefs: [
      {
        "targets": [ 0 ],
        "visible": false,
        "searchable": false
      }
    ]
    //          ,
    //          columnDefs: [
    //                  {targets: 13, render: $.fn.dataTable.render.number( ' ', '.', 0 )}
    //              ]
  } );

  // Filter event handler
  $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
    table
    .column( $(this).data('index') )
    .search( this.value )
    .draw();
  } );
  document.getElementById("table_ep_filter").style.display = "none";
} );






$('#table_ep').selectable ({
  filter:"td.selectable",
  distance:0,
  selected: function( event, ui ) {
    if ($(ui.selected).attr('data-id')) {
      alert($(ui.selected).attr('data-id')+$(ui.selected).attr('data-IO'));
      //dChecked($(ui.selected));
    }
    else if ($(ui.selected).attr('data-idMaster')) {
      alert($(ui.selected).attr('data-idMaster')+$(ui.selected).attr('data-IO'));
if ($(ui.selected).val()=="") {
  $(ui.selected).attr('data-newEntry',1);
  $(ui.selected).val('test');
}
else {
    $(ui.selected).attr('data-newEntry',0);
      $(ui.selected).val('');
}
      //updateInOutMaster($(ui.selected));
    }
  }

});


// Flag Qualité
function updateInOutMaster(e) {
  if (e.val()!="") {

    var confirmation = confirm('Unflag this test ? Only Quality Manager should do this');
    if (confirmation) {

      $.ajax({
        type: "POST",
        url: 'controller/updatexxxxxxxFlagQualite.php',
        dataType: "json",
        data:  {
          idEp : e.attr('data-idepflagqualite'),
          flagQualite : e.attr('data-flagQualite')
        }
        ,
        success : function(data, statut){
          if(data['id_user']==0)  {
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#44546A");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#44546A");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html("0");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user']);
          }
          else {
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#b96500");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#b96500");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html(data['id_user']);
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user'] );
          }

        },
        error : function(resultat, statut, erreur) {
          console.log(Object.keys(resultat));
          alert('ERREUR lors de la modification du flag qualité de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
        }
      });
    }
  }
  else {

    $.ajax({
      type: "POST",
      url: 'controller/updatexxxxxxFlagQualite.php',
      dataType: "json",
      data:  {
        idEp : e.attr('data-idepflagqualite'),
        flagQualite : e.attr('data-flagQualite')
      }
      ,
      success : function(data, statut){
        if(data['id_user']==0)  {
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#44546A");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#44546A");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html("0");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user']);
        }
        else {
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#b96500");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#b96500");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html(data['id_user']);
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user'] );
        }

      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du flag qualité de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
      }
    });

  }

}
