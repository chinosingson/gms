(function ($) {
	Drupal.behaviors.map_json = {
		attach: function(context, settings) {
/*
			 $.post("/gms/gms_misc_map",
				{
					name: "Donald Duck",
					city: "Duckburg"
				},
				function(data, status){
					alert("Data: " + data + "\nStatus: " + status);
				}); 
*//*
				$.ajax({ 
				  type: 'POST', 
				  url: '/gms/gms_misc_map', 
				  dataType: 'json', 
				  data:  {
					name: "Donald Duck",
					city: "Duckburg"
				},
					success:function(data) { 
					alert(data.status); 
					},
					complete: function(data){
					console.log(data + status)  
				}
				}); 
				*/
/*				
			  $.ajax({
				type: 'POST',
				url: '/gms/gms_misc_map',
				dataType: 'json',

			   data: 'js=1&my_module_post_data=2012'
			  });

  */ 
$.post( "/gms/gms_misc_map", { name: "John", time: "2pm" })
  .done(function( data ) {
    console.log( "Data Loaded: " + data );
  });
	
				
		}
	};
})(jQuery);
