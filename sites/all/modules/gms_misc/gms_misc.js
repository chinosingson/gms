(function ($) {
	Drupal.behaviors.map_json = {
		attach: function(context, settings) {

/*

  var JsonData = {'color': "red", 'size' : "big" }
				$.ajax({ 
				  type: "POST", 
				  url: "/gms/gms_misc_map", 
				  dataType: "json", 
				  data: JsonData,
					success:function(data) { 
					//alert(JsonData.status); 
					},
					complete: function(data){
					console.log(JsonData)  
				}
				}); 
			
 
/*$.post( "/gms/gms_misc_map", { name: "John", time: "2pm" })
  .done(function( data ) {
    console.log( "Data Loaded: " + data );
  });*/
  
  
	/*
	var myJsonData = { name: "John", time: "2pm", foo: "bar" };
	
	$.ajax({
		'url'     : '/gms/gms_misc_map',
		'type'    : 'POST',
		'data'    : myJsonData,
		'success' : function (data){
			console.log("Data Loaded:" + myJsonData);
		},
	});
	
/*
var myJsonData = { name: "John", time: "2pm", foo: "bar" };


  $.ajax({
       'type': "POST",
       dataType: "json",
       url: "/gms/gms_misc_map",
	   data: myJsonData,
       success : function(data){
         alert(data);
       } 
     }); */
	 
/*$('.form-submit', context).click(function (event) {
//getting the value from textfield
var user_name='richie';
//passing the value using 'POST' method
var post = "&name="+user_name;
$.ajax({
'url': '/gms/gms_misc_map',
'type': 'POST',
'dataType': 'json',
'data': post,
'success':function(data) { 
				console.log('SUCCESS');
				console.log(data); 
					},
'complete':	function(data){
				console.log('COMPLETE');
					console.log(data);
				}
});

		});*/
		/*
'success': function(data)
{

if(data == "success") {

$('#status').attr("innerHTML","Name submitted successfully!");
}
else
{
$('#status').attr("innerHTML","Name submit failed!");
}
},
beforeSend: function()
{
$(document).ready(function () {
$('#status').attr("innerHTML","Loading....");
});
},
'error': function(data)
{

$(document).ready(function () {
$('#status').attr("innerHTML","ERROR OCCURRED!");
});
}
});
*/
	
		}
	};
})(jQuery);


