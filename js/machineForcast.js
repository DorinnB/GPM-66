
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-machineForcast.php",
      type: "POST"
    },
    table: "#table_machineForcast",
    fields: [
      { label: "Machine",       name: "machines.machine", type:  "readonly"     },
      { label: "Forcast Message",       name: "machine_forcasts.texte_machine_forcast", type: "textarea" },
      { label: "Text Icon:",              name: "machine_forcasts.id_icone_machine_forcast",
          type: "select"
      },
      { label: "Priority",       name: "machine_forcasts.prio_machine_forcast",
      type:  "radio",
          options: [
              { label: "1", value: 1},
              { label: "2", value: 2},
              { label: "3", value: 3},
              { label: "0", value: 0}
          ]}
    ]
  } );



  var table = $('#table_machineForcast').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-machineForcast.php",
      type: "POST"
    },
    order: [[ 0, "asc" ]],
    columns: [
        { data: "machines.machine" },
        { data: "machine_forcasts.texte_machine_forcast" },
        {
                        data: "icones.icone_file",
                        render: function ( icone_file ) {
                                           return icone_file ?
                                               '<img src="img/'+icone_file+'" style="width: auto;max-height: 30px;background-color:white;">' :
                                               'No image';
                                       }
                    },
        { data: "machine_forcasts.prio_machine_forcast",
        render: function ( prio_machine_forcast ) {
                           return prio_machine_forcast ?
                               '<img src="img/medal_'+prio_machine_forcast+'.png" style="width: auto;max-height: 30px;">' :
                               '-';
                       } }
      ],
      scrollY: '30vh',
      scrollCollapse: true,
      paging: false,
      searching: false,
      select: {
        style:    'os',
        blurable: true
      },
      buttons: [
      {
        extend: "edit",
        editor: editor,
        formButtons: [
          'Edit',
          { label: 'Cancel', fn: function () { this.close(); } }
        ]
      }
    ]
    } );






    $('#container').css('display', 'block');
    table.columns.adjust().draw();

    // Filter event handler
    $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
      table
      .column( $(this).data('index') )
      .search( this.value )
      .draw();
    } );

    //table.columns.adjust().draw();






  } );



  //Selon le navigateur utilisé, on detecte le style de transition utilisé
  function whichTransitionEvent(){
    var t,
    el = document.createElement("fakeelement");

    var transitions = {
      "transition"      : "transitionend",
      "OTransition"     : "oTransitionEnd",
      "MozTransition"   : "transitionend",
      "WebkitTransition": "webkitTransitionEnd"
    }

    for (t in transitions){
      if (el.style[t] !== undefined){
        return transitions[t];
      }
    }
  }

  var transitionEvent = whichTransitionEvent();

  //On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
  $("#wrapper").addClass("toggled");
  $("#wrapper").one(transitionEvent,
    function(event) {
      $('#table_machineForcast').DataTable().draw();
    });
