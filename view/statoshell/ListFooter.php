</li>
</ul>
<?php if ($datishell->INPASSO == "") { ?><BR><BR><?php }
                                                if ($datishell->DA_RETITWS == "") {
                                                  ?>
  <input type="hidden" id="CambioPag" value="">
  <table id="PageTab" class="si" style="width:<?php echo count($AllPage) * 30; ?>px">
    <tr><?php
                                                  foreach ($AllPage as $Page) {
        ?><td onclick="ChangePage(<?php echo $Page; ?>)" <?php
                                                              echo (($Page == $datishell->SelNumPage) ? 'class="SelPage"' : 'class="NoSelPage"');

                                                              ?>><?php echo $Page; ?>
        </td><?php
                                                  }
              ?></tr>
  </table><?php
                                                }
                                                //  db2_close();
          ?>



</div>

</div>
<div id="ShowDataElab" style="position:fixed; left:200px; top:10px; color:white; font-size: 18px;; z-index:9999;" >DataElab: <?php echo $DataElab; ?></div>
<script src="./view/statoshell/JS/statoshell.js?p=<?php echo rand(1000, 9999); ?>"></script>

<script>

  <?php
  if ($datishell->SelShell != "") {
  ?>//  $('#SelInDate').val(<?= statoshell_dati::ALL_DAY ?>).prop('selected', true); 
  //alert($('#SelShell').val());
   // $('#NumLast').val(0);
  <?php
  }
  ?>

  <?php
  if ($FirstIdRunSh != "") {
  ?>$('#PreIdRun').val('<?php echo $FirstIdRunSh; ?>');
  <?php
  }
  ?>

var countvFound= {};


function OpenLink(vIdRunSh){
   
    console.log(' RETI OpenLink');
   var vListStep=$('#ListOpenStep').val();  
    
  var formData={
                    'DAOPENLINK':'1',                    
                    'IDSEL':      vIdRunSh
            };

            console.log(formData);
 
     $('#Waiting').show();
 $.ajax({
          type: "POST",
		   data: formData,
		   encode: true,	  
           
          // specifico la URL della risorsa da contattare
           url: "index.php?sito="+getParameterByName('sito')+"&controller=statoshell&action=contentList",  
          // imposto l'azione in caso di successo
          success: function(risposta){
          //visualizzo il contenuto del file nel div htmlm
           var oldElement = $('#p'+vIdRunSh);

    // Crea il nuovo elemento <li> che lo sostituirà
    var newElement = $(risposta);

    // Sostituisci l'elemento vecchio con quello nuovo
    oldElement.replaceWith(newElement);
         //  $('#ShowDett'+vIdRunSh+vPasso).html(risposta).show();
		  $('#Waiting').hide();
				
          },
          //imposto l'azione in caso di insuccesso
        error: function (stato) {
            errorMessage("OpenLink: Qualcosa è andato storto!", stato);
            $('#Waiting').hide();
            }
        });
		 
		
     

   $('#ListOpenStep').val(vListStep);    
}

function hasSubUlWithLi(liId) {
    // Seleziona l'elemento <li> con l'id specificato
    var liElement = $('#p' + liId);
    
    // Controlla se c'è un elemento <ul> direttamente sotto il <li> che contiene almeno un <li>
    return liElement.children('ul').children('li').length > 0;
}



  function ChangeA<?php echo $InRetePasso; ?>(vIdSel,level) {
    if (typeof countvFound[vIdSel] === 'undefined') {
    countvFound[vIdSel]=0;
     }
   
    console.log('ChangeA<?php echo $InRetePasso; ?> ListFooter');
    console.log('ChangeA');
    var vFound = false;
    var vListId = $('#ListOpenId<?php echo $InRetePasso; ?>').val();
    if (vListId != '') {
      var IdArray = vListId.split(',');
      for (var i = 1; i < IdArray.length; i++) {
        var vTargets = IdArray[i];
        if (vTargets == vIdSel) {
          vFound = true;
        //  countvFound[vIdSel]=false;
        }
      }
    }
    if (!vFound) {
      console.log("!vFound");
      vListId = vListId + ',' + vIdSel;
      countvFound[vIdSel]=countvFound[vIdSel]+1;
      console.log("vIdSel",vIdSel);
    } else {
      for (var i = 1; i < IdArray.length; i++) {
        var vTargets = IdArray[i];
        if (vTargets.startsWith(vIdSel)) {
         vListId = vListId.replace(',' + vTargets, '');
        }
      }
    }
    $('#ListOpenId<?php echo $InRetePasso; ?>').val(vListId);
    console.log('#ListOpenId<?php echo $InRetePasso; ?> ListFooter:' + $('#ListOpenId<?php echo $InRetePasso; ?>').val());
     if (countvFound[vIdSel]==1 && level==1 && !hasSubUlWithLi(vIdSel)) {
     // alert(vIdSel);
        Refresh();
     }
  }

  var livello0 = 0;
  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > a > table > tbody > tr > .openLi').click(function() {
    console.log('openLi livello0');
    livello0 = 1;
  });
  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > a').click(function() {
    //alert(prova);
    console.log('openLi livello0 b');
    if (livello0 == 1) {
      livello0 = 0;
      $('#ListShell<?php echo $InRetePasso; ?> li').removeClass('active');
      $(this).closest('li').addClass('active');
      var checkElement = $(this).next();
      if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        $(this).closest('li').removeClass('active');
        checkElement.slideUp('normal');
      }
      if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        //$('#ListShell<?php echo $InRetePasso; ?> ul ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
      }
      if ($(this).closest('li').find('ul').children().length == 0) {
       console.log('livello0 open');     
       return true;
      } else {
        console.log('livello0 close');      
        return false;
      }
    }

  });


  var livello1 = 0;
  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > a > table > tbody > tr > .openLi').click(function() {
    console.log('openLi livello1');
    livello1 = 1;
  });


  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > a').click(function() {
    console.log('openLi livello1 b');

    if (livello1 == 1) {
      livello1 = 0;
      $('#ListShell<?php echo $InRetePasso; ?> ul li').removeClass('active');
      $(this).closest('li').addClass('active');
      var checkElement = $(this).next();
      if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        $(this).closest('li').removeClass('active');
        checkElement.slideUp('normal');
      }
      if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        //$('#ListShell<?php echo $InRetePasso; ?> ul ul ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
      }
      if ($(this).closest('li').find('ul').children().length == 0) {
        return true;
      } else {
        return false;
      }
    }
  });


  var livello2 = 0;
  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > ul > li > a > table > tbody > tr > .openLi').click(function() {
    console.log('openLi livello2');
    livello2 = 1;
    console.log('#ListOpenId<?php echo $InRetePasso; ?> openLi livello2:' + $('#ListOpenId<?php echo $InRetePasso; ?>').val());
  });



  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > ul > li > a').click(function() {
    console.log('openLi livello2 b');
    if (livello2 == 1) {
      livello2 = 0;
      $('#ListShell<?php echo $InRetePasso; ?> ul ul li').removeClass('active');
      $(this).closest('li').addClass('active');
      var checkElement = $(this).next();
      if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        $(this).closest('li').removeClass('active');
        checkElement.slideUp('normal');
      }
      if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        //$('#ListShell<?php echo $InRetePasso; ?> ul ul ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
      }
      if ($(this).closest('li').find('ul').children().length == 0) {
        return true;
      } else {
        return false;
      }
    }

    console.log('#ListOpenId<?php echo $InRetePasso; ?> openLi livello2 b:' + $('#ListOpenId<?php echo $InRetePasso; ?>').val());
  });

  var livello3 = 0;
  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > ul > li > ul > li > a > table > tbody > tr > .openLi').click(function() {
    console.log('openLi livello3');
    livello3 = 1;
  });

  $('#ListShell<?php echo $InRetePasso; ?> > ul > li > ul > li > ul > li > ul > li > a').click(function() {

    if (livello3 == 1) {
      livello3 = 0;
      $('#ListShell<?php echo $InRetePasso; ?> ul ul ul li').removeClass('active');
      $(this).closest('li').addClass('active');
      var checkElement = $(this).next();
      if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        $(this).closest('li').removeClass('active');
        checkElement.slideUp('normal');
      }
      if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        //$('#ListShell<?php echo $InRetePasso; ?> ul ul ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
      }
      if ($(this).closest('li').find('ul').children().length == 0) {
        return true;
      } else {
        return false;
      }
    }
  });




  $('#FormEserEsame').submit(function() {
    if ($('#CambioPag').val() == '1') {
      $('#TopScrollShell').val('');
      $('#LeftScrollShell').val('');
    } else {
      $('#TopScrollShell').val($('#ListShell<?php echo $InRetePasso; ?>').scrollTop());
      $('#LeftScrollShell').val($('#ListShell<?php echo $InRetePasso; ?>').scrollLeft());
    }
  });


  <?php if ("$INPASSO" == "") { ?>
    $('#ListShell<?php echo $InRetePasso; ?>').scrollTop($('#TopScrollShell').val());
    $('#ListShell<?php echo $InRetePasso; ?>').scrollLeft($('#LeftScrollShell').val());
  <?php } ?>
  /*
  setInterval(function(){ 
  if ( $('#AutoRefresh').is(':checked') || $('#AutoRefresh2').is(':checked') ){
  Refresh(); 
  }
  }, 30000);*/




  function OpenNipote(vIdSh, vIdRunSh) {
    console.log('OpenNipote listfooter');
    if ($("#ShowFiglio" + vIdSh + '_' + vIdRunSh).is(':hidden') && !$(".Figlio" + vIdRunSh).length) {
      ChangeA<?php echo $InRetePasso; ?>(vIdRunSh);
      /*   var input = $("<input>")
         .attr("type", "hidden")
         .attr("name", "OpenNipote")
         .val('OpenNipote'+vIdRunSh);
         $('#FormEserEsame').append($(input));*/
         $('#Waiting').show();
      var formData = {};

      let obj_form = $("form#FormEserEsame").serializeArray();
      obj_form.forEach(function(input) {

        formData[input.name] = input.value;
      });
      formData['OpenNipote'] = 'OpenNipote' + vIdRunSh;
      console.log(formData);

      $.ajax({
        type: "POST",
        data: formData,
        encode: true,

        // specifico la URL della risorsa da contattare
        url: "index.php?controller=statoshell&action=contentList",
        // imposto l'azione in caso di successo
        success: function(risposta) {
          //visualizzo il contenuto del file nel div htmlm
          //  $("#shellContent").html("");
          $("#contenitore").html(risposta);
          $('#Waiting').hide();
          console.log('#ListOpenId<?php echo $InRetePasso; ?> OpenNipote:' + $('#ListOpenId<?php echo $InRetePasso; ?>').val());

        },
        //imposto l'azione in caso di insuccesso
        error: function(stato) {
          alert("Qualcosa è andato storto");
        }
      }).done(function(data) {


      });

      //  Refresh();
    }
  }
</script>