$(document).ready(function() {



let table = $("#idTabella").DataTable();
//	table.fnPageChange(0, true); 
if($('#mailLength').val())
	{
	console.log('mailLength: '+$('#mailLength').val());
	table.page.len($('#mailLength').val()).draw();
	}
if($('#mailSearch').val())
	{
	console.log('mailSearch: '+$('#mailSearch').val());
	table.search($('#mailSearch').val());
	}
	


if($('#mailPage').val()>0)
	{
	console.log('mailPage: '+$('#mailPage').val());
	table.page(parseInt($('#mailPage').val())).draw('page');
   }	
   

	
	
	table.on('length.dt', function (e, settings, len) {
    console.log('New page length: ' + len);
	
	$('#mailLength').val(len);
	
});
if($('#mailOrdern').val())
	{
	console.log('Order: '+$('#mailOrdern').val()+"("+$('#mailOrdert').val()+")");
	table.order([parseInt($('#mailOrdern').val()), $('#mailOrdert').val()]).draw();
	}
	//table.order([1, 'asc']).draw();

table.on('page', function () {
    var info = table.page.info();
	console.log("info.page: "+info.page);
    $('#mailPage').val(info.page);
});


table.on('search.dt', function () {
    $('#mailSearch').val(table.search());
});

   
   table.on('order', function () {
    // This will show: "Ordering on column 1 (asc)", for example
    var order = table.order();
	 $('#mailOrdern').val(order[0][0]);   
     $('#mailOrdert').val(order[0][1]);  
    console.log(
        'Ordering on column ' + order[0][0] + ' (' + order[0][1] + ')'
    );
});
console.log('Table initialisation start: ' + new Date().getTime());
 


});