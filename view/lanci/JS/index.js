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



 