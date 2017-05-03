
function couleurStatut(el) {
    if (el.attr("color-statut") >= 100) el.css('background-color', 'rgb(37, 204, 134)');
    if (el.attr("color-statut") < 100) el.css('background-color', 'rgb(37, 204, 134)');
    if (el.attr("color-statut") < 90) el.css('background-color', 'rgb(37, 204, 99)');
    if (el.attr("color-statut") < 80) el.css('background-color', 'rgb(45, 204, 37)');
    if (el.attr("color-statut") < 70) el.css('background-color', 'rgb(95, 204, 37)');
    if (el.attr("color-statut") < 60) el.css('background-color', 'rgb(166, 204, 37)');
    if (el.attr("color-statut") < 50) el.css('background-color', 'rgb(181, 204, 37)');
    if (el.attr("color-statut") < 40) el.css('background-color', 'rgb(204, 157, 37)');
    if (el.attr("color-statut") < 30) el.css('background-color', 'rgb(204, 112, 37)');
    if (el.attr("color-statut") < 30) el.css('background-color', 'rgb(204, 112, 37)');
    if (el.attr("color-statut") < 20) el.css('background-color', 'rgb(204, 72, 37)');
    if (el.attr("color-statut") <= 10) el.css('background-color', 'rgb(255, 15, 0)');
    if (el.attr("class") == "active") el.css('background-color', 'rgb(68, 84, 106)');
    if (el.attr("class") == "active") el.css('color', 'white');
  }
  $('ul.statut li,ul.tab a, button').each(function()  {
    couleurStatut($(this))
  });
