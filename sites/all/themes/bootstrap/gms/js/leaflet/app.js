jQuery(document).ready(function(){	

		var map;
		//var gmsMapCenter = new google.maps.LatLng(9.622, 111.137);
		var gmsMapCenter = new google.maps.LatLng(17.180,104.124);

		function initialize() {
			var mapDiv = document.getElementById('map-canvas');
			var markers = [];
			var mapOptions = {
				center: gmsMapCenter,
				//center: new google.maps.LatLng(0, 0),
				zoom: 5,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false,
				panControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				overviewMapControlOptions: {
					opened: false,
				},
				mapTypeControl: false,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DEFAULT,
					position: google.maps.ControlPosition.BOTTOM_CENTER
				},

				panControl: false,
				panControlOptions: {
					position: google.maps.ControlPosition.LEFT_BOTTOM
				},
				zoomControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.DEFAULT,
					position: google.maps.ControlPosition.TOP_LEFT
				},				
			};

			map = new google.maps.Map(mapDiv, mapOptions);
			
			// keep map centered on coral triangle on resize
			google.maps.event.addDomListener(window, 'resize', function() {
				var center = map.getCenter();
				google.maps.event.trigger(map, 'resize');
				map.setCenter(center); 
			});
					
			
										
		}
	
		google.maps.event.addDomListener(window, 'load', initialize);

});