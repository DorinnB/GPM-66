$("#myChart").click(function(e) {

  endLevel_x=  ["endLevel_x"];
  endLevel_y=  ["endLevel"];
  //pour chaque ligne du tableau eprouvettes
  $('#table_ep').find('tr.chartTR').each( function (i) {
    //on cherche ceux ou il y a un nombre de cycle (en otant les espaces)
    if ($(this).find('.chartCycle').html().replace(/ /g,'')>0) {
      endLevel_x.push($(this).find('.chartCycle').html().replace(/ /g,''));
      endLevel_y.push($(this).find('.chartMax').html().replace(/ /g,''));
    }
  });
  //Passage des valeurs de cycle en log
  data_test = ['endLevel_x'];
  for(var i=1; i<endLevel_x.length; i++){
    data_test[i] = Math.log(endLevel_x[i]) / Math.LN10;
  }
  console.log(endLevel_x);
  console.log(endLevel_y);

  //affichage du graph
  var chart = c3.generate({
    point: {
      r: 5
    },
    data: {
      xs: {
        endLevel: 'endLevel_x',
      },
      // iris data from R
      columns: [
        endLevel_x,
        endLevel_y,
      ],
      type: 'scatter'
    },
    axis: {
      x: {
        label: {
          text: 'Cycle',
          position: 'outer-center'
        },
        tick: {
          fit: false,
          //  max: 2000000,
          //  min: 0,
          //calcul inverse pour recuperer la puissance de 10 des valeurs pour l'affichage du tooltip
          format: function (d) { return Math.pow(10,d).toFixed(0); }
        }
      },
      y: {
        label: {
          text: 'niveau',
          position: 'outer-middle'
        },
        tick: {
          format: function (d) { return  d.toFixed(2); }
        }
      }
    },
    tooltip: {
      format: {
        title: function (d) { return 'Cycle ' + Math.pow(10,d).toFixed(0); }
      }
    }
  });

  //500ms apres, on actualise le graph => pb de width qui ne se corrige qu'apres un update
  setTimeout(function () {
    chart.load({
      xs: {
        endLevel: 'endLevel_x',
      },
      columns: [
        data_test,
        endLevel_y,        ]
      });
    }, 500);
  });
