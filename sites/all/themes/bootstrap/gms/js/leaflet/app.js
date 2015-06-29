jQuery(document).ready(function(){	

		var map;
		var ctiMapCenter = new google.maps.LatLng(9.622, 111.137);

		function initialize() {
			var mapDiv = document.getElementById('map-canvas');
			var markers = [];
			var mapOptions = {
				center: ctiMapCenter,
				//center: new google.maps.LatLng(0, 0),
				zoom: 5,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: true,
				panControl: true,
				streetViewControl: false,
				overviewMapControl: true,
				overviewMapControlOptions: {
					opened: true,
				},
				mapTypeControl: false,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DEFAULT,
					position: google.maps.ControlPosition.BOTTOM_CENTER
				},

				panControl: true,
				panControlOptions: {
					position: google.maps.ControlPosition.LEFT_BOTTOM
				},
				zoomControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.LARGE,
					position: google.maps.ControlPosition.LEFT_BOTTOM
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