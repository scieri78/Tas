$(document).ready(function () {
    $('#Waiting').hide();

    $('#idTabella').DataTable({
      //columns: [{ width: '5%' }, { width: '5%' }, { width: '10%' }, { width: '5%' }, { width: '20%' },{ width: '5%' },null],
      language: {
        "url": "./JSON/italian.json"
      },
      "lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]]
      // responsive: true
    
    });

});

$(function() {

    $("#tags").autocomplete({
      source: function(request, response) {
        var formData = {};
        formData['term'] = $("#tags").val();
        console.log(formData);
        $.ajax({
          
          type: "POST",
          url: 'index.php?controller=base&action=getSuggestion',
          dataType: "json",
          encode: true,
          data: formData,
          
          success: function(data) {
            response(data);
          }
        });
      }
    });

  });