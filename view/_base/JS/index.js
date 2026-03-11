$(document).ready(function () {
    $('#Waiting').hide();

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