
$('#table_ep').selectable ({
  filter:"td.selectable",
  distance:0,
  selecting: function( event, ui ) {
    if ($(ui.selecting).hasClass('open-GestionEp')) {
      $('#gestionEp').modal('show');
      gestionEp($(ui.selecting).data('id'));
      //$("#id_gestionEp").html($(ui.selecting).data('id'));

    }
  },
  selected: function( event, ui ) {
    if ($(ui.selected).attr('data-idepdchecked')) {
      dChecked($(ui.selected));
    }
    else if ($(ui.selected).attr('data-idepchecked')) {
      checked($(ui.selected));
    }
    else if ($(ui.selected).attr('data-flagQualite')) {
      flagQualite($(ui.selected));
    }
}

});




$(document).ready(function() {
  // Setup - add a text input to each footer cell
  $('#table_ep tfoot th').each( function (i) {
    var title = $('#table_ep thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );

  // DataTable
  var table = $('#table_ep').DataTable( {
    scrollY:        "50vh",
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    info: false,
    fixedColumns:   {leftColumns: 4},
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


// Gestion Eprouvette
function gestionEp(idEp) {
  $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+idEp);
}




// Check Eprouvette

function checked(e) {
  $.ajax({
    type: "POST",
    url: 'controller/updateCheckEp.php',
    dataType: "json",
    data:  {
      idEp : e.attr('data-idepchecked'),
      checked : e.attr('data-checked')
    }
    ,
    success : function(data, statut){
      if(data['id_user']==0)  {
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("background-color","darkred");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("color","darkred");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").html('0');
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").attr("data-checked","0");
      }
      else {
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("background-color","darkgreen");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("color","darkgreen");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").html(data['id_user']);
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").attr("data-checked",data['id_user'] );
      }

    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification du check de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
}



function dChecked(e) {

  $.ajax({
    type: "POST",
    url: 'controller/updateDCheckEp.php',
    dataType: "json",
    data:  {
      idEp : e.attr('data-idepdchecked'),
      dchecked : e.attr('data-dchecked'),
      iduser: iduser
    }
    ,
    success : function(data, statut){
      if(data['id_user']==0)  {
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("background-color","darkred");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("color","darkred");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").html('0');
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").attr("data-dchecked","0");
      }
      else {
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("background-color","darkgreen");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("color","darkgreen");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").html(data['id_user']);
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").attr("data-dchecked",data['id_user'] );
      }

    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification du check des valeurs de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
}



// Flag Qualité
function flagQualite(e) {
  if (e.attr('data-flagQualite')>0) {

    var confirmation = confirm('Unflag this test ? Only Quality Manager should do this');
    if (confirmation) {

      $.ajax({
        type: "POST",
        url: 'controller/updateFlagQualite.php',
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
            url: 'controller/updateFlagQualite.php',
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


$('.popover-markup').popover({
  html: true,
  container:'body',
  trigger: "manual",
  title: function () {
    return $(this).find('.head').html();
  },
  content: function () {
    return $(this).find('.content').html();
  }
})
.on("mouseenter", function () {
  var _this = this;
  $(this).popover("show");
  $(".popover").on("mouseleave", function () {
    $(_this).popover('hide');
  });
}).on("mouseleave", function () {
  var _this = this;
  setTimeout(function () {
    if (!$(".popover:hover").length) {
      $(_this).popover("hide");
    }
  });
});
