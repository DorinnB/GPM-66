$(document).ready(function() {



  //Insertion InvoiceLine

  $(".addInvLine").change(function(e){

    var prodCode = $('option:selected', this).attr('data-prodCode');
    var OpnCode = $('option:selected', this).attr('data-OpnCode');
    var pricingList = $('option:selected', this).attr('data-pricingList');
    var price = $('option:selected', this).attr('data-price');

    //on cherche ou placer la nouvelle ligne
    a=$(this).closest('div').find('div.splitInvLine');
    //on clone la ligne vierge, en effacant l'id
    b=$( "#invLineVierge" ).clone().prop('id', '' ).appendTo( a );
    //on affiche la ligne vierge copié et on rempli les champs
    b.css('display','block');

    b.find('.code').find('input').val(prodCode+'-'+OpnCode);
    b.find('.pricingList').find('input').val(pricingList);
    b.find('.priceUnit').find('input').val(price);

    //on reinitialise les select de new invoice line
    $(".addInvLine option[value='No']").prop('selected', true);
  });



  //Copy eprouvette
  var n_newep=0;
  $('#table_ep tbody').on( 'click', 'tr .copy', function () {
    var row_data = [];
    var n_split=0;
    $(this).closest('tr').find('td').each(function() {
      //on prend le tr precedent, et on recopie les valeurs, les inputs en changeant l'id ep.
      if (n_split==1) {
        //on cherche l'input, recupere la valeur
        $(this).find("input").each(function() {
          oldval=this.value;
        });
        row_data.push('<input type="text" value="'+oldval+'" style="width:100px;" name="newEp_'+n_newep+'-prefixe">');
      }
      else if (n_split==2) {
        //on cherche l'input, recupere la valeur et l'incremente
        $(this).find("input").each(function() {
          oldval=this.value;
          //alert(((parseInt(this.value, 36)+1).toString(36)).replace(/0/g,'a'));
        });
        //row_data.push('<input type="text" value="copy-'+oldval+'" style="width:100px;" name="newEp_'+n_newep+'-nom_eprouvette">');
        row_data.push('<input type="text" value="" style="width:100px;" name="newEp_'+n_newep+'-nom_eprouvette">');
      }
      else if (n_split==3) {
        //on cherche l'input, recupere la valeur et l'incremente
        $(this).find("select").each(function() {
          oldDwg=this.value;
        });
        row_data.push($(this).html());

      }
      else {
        //si la valeur est 2 (essai demarré et non reattribuable) on met 1
        if ($(this).html()==2)  {
          row_data.push('1');
        }
        else {
          row_data.push($(this).html());
        }
        //alert($(this).attr('class'));
      }
      n_split=n_split+1;
    });

    console.log( row_data );
    //On insert la ligne
    var rowNode = table.row.add(row_data).draw().node();

    //Pour chaque td inseré, on recupère les infos de la ligne précédente pour les utiliser (nom, class)
    $( rowNode ).find('td').each(function(){
      //ajout de la class
      $(this).addClass($( rowNode ).prev('tr').find('td').eq($(this).index()).attr('class'));

      //On ajoute le choix de dessin précédent
      if ($(this).index()==3){
        //$(this).find('select').val(oldDwg);
        a=$(this).find('select')
        //                a.filter('[value='+oldDwg+']').prop('selected', true)
        //a.eq(oldDwg).prop('selected', true);
        //alert(a.find(":selected").text());
        a.val(oldDwg);
        a.attr('name', 'newEp_'+n_newep+'-id_dwg');
        //alert(a.find(":selected").text());
        a.find(":selected").attr('selected');
      }
      //On ignore les 4 premieres colonnes (gest, pref, id et dwg). On insert un nom sur les td
      if ($(this).index()>=4 && $(this).index()!=20){
        precedentName=$( rowNode ).prev('tr').find('td').eq($(this).index()).attr('name');
        splitName=precedentName.split("-");
        idNewEp = "newEp_"+n_newep;
        splitName = splitName[1];
        newName = idNewEp+"-"+splitName;
        $(this).attr('name',newName); //MODIF DU NOM
        if ($(this).html()==1)  {
          $(this).removeClass('color-0').removeClass('color-2').addClass( "color-1" );
        }
        else {
          $(this).removeClass('color-1').removeClass('color-2').addClass( "color-0" );
        }
      }
    });
    n_newep=n_newep+1;
  });


  //delete eprouvette
  $('#table_ep tbody').on( 'click', 'tr .delete', function () {  //au click du bouton, on cherche le tr
    var allowdelete="";
    $(this).closest('tr').find('td').each(function(){            //pour ce tr, on cherche tous les td
      if ($(this).index()>=4){                                   //on ignore les 4 premiers
        if ($(this).html()==2) {                                 //si la valeur du td est a 2 (eprouvette testé)
          allowdelete = 'NON!';                                  //on refuse la suppression
        }
      }
    });
    //si allowdelete est vide (ne contient pas "NON!") on peut effacer l'eprouvette
    if (allowdelete=="") {
      $(this).closest('tr').fadeOut("slow", function(){
        $(this).remove();
        //envoi de cet element dans le formulaire pour supprimer le master ep
        document.getElementById('deletedEp').value = $(this).attr('id') + "&" + document.getElementById('deletedEp').value;
      })
    }

  } );



  //save attribution des eprouvettes aux splits
  $('#save_selected').click(function () {
    $('td.selected').each(function(){
      if ($(this).html()==0){
        $(this).html(1);
        $(this).removeClass('color-0').addClass( "color-1" );
        //$(this).css('background-color', 'DimGray');
        //$(this).css('color', 'DimGray');
      }
      else if($(this).html()==1){
        $(this).html(0);
        $(this).removeClass('color-1').addClass( "color-0" );
        //$(this).css('background-color', '#1F4E79');
        //$(this).css('color', '#1F4E79');
      }
    });





  });







  //Lors du submit du job, on recupere les information du WORKFLOW avant l'envoi
  $('#submit_ep').click( function() {

    //on recupere (en serialize) la liste des splits et leur type
    var formSplit = $.param($('th').map(function() {
      if ($(this).find('select').length) {
        return{
          name:$(this).find('select').attr('name'),
          value:$(this).find(':selected').val(),
        }
      }
    }));
    //alert(formSplit);
    document.getElementById('dataSplit').value = formSplit;


    //on recupere (en serialize) la liste des splits et leur splitnumber
    var splitNumber = $.param($('.splitNumber').map(function(){
      return{
        name:$(this).attr('name'),
        value:$(this).val(),
      }
    }));
    //alert(splitNumber);
    document.getElementById('dataSplitNumber').value = splitNumber;


    //on recupere (en serialize) la liste des eprouvettes, leur nom et les splits associés
    var formEp = $.param($('td').map(function() {
      if ($(this).attr('name')){
        return {
          name: $(this).attr('name'),
          value: $(this).text().trim()
        };
      }
      else if ($(this).find('input').length) {
        return{
          name:$(this).find('input').attr('name'),
          value:$(this).find('input').val(),
        }
      }
      else if ($(this).find('select').length) {
        return{
          name:$(this).find('select').attr('name'),
          value:$(this).find('select').val(),
        }
      }
    }));
    //alert(formEp)
    document.getElementById('dataEp').value = formEp;

    //On envoi de formulaire
    document.getElementById("updatejob").submit();
  } );




});
