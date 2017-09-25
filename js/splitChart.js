$("#myChart").click(function(e) {

  endLevel_X=  [];
  endLevel_Y=  [];
  chartFile=  [];
  //pour chaque ligne du tableau eprouvettes
  $('#table_ep').find('tr.chartTR').each( function (i) {
    //on cherche ceux ou il y a un nombre de cycle (en otant les espaces)
    if ($(this).find('.chartCycle').html().replace(/ /g,'')>0) {
      endLevel_X.push($(this).find('.chartCycle').html().replace(/ /g,''));
      endLevel_Y.push($(this).find('.chartNiveau').html().replace(/ /g,''));
      chartFile.push('File : '+$(this).find('.chartFile').html().replace(/ /g,''));
    }
  });

  console.log(endLevel_X);
  console.log(endLevel_Y);
  console.log(chartFile);



    var trace1 = {
      x: endLevel_X,
      y: endLevel_Y,
      text: chartFile,
      mode: 'markers'
    };

    var trace2 = {
      x: [2, 3, 4, 5],
      y: [16, 5, 11, 10],
      mode: 'lines'
    };

    var trace3 = {
      x: [1, 2, 3, 4],
      y: [12, 9, 15, 12],
      mode: 'lines+markers'
    };

  //  var data = [ trace1, trace2, trace3 ];
    var data = [ trace1 ];

    var layout = {
      title:'Cycle vs Requirement Endlevel',
      xaxis: {title: 'Cycle', type: 'log'},
      yaxis: {title: $('#ChartTitreCons').attr('data-titre')},
    };

    Plotly.newPlot('chart2', data, layout);


  });
