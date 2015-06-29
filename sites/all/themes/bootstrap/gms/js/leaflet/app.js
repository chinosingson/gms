jQuery(document).ready(function(){	

		var map;
		var ctiMapCenter = new google.maps.LatLng(9.622, 111.137);

		function HomeControl(controlDiv, map){
			/* code for Center custom control */
		  // Set CSS styles for the DIV containing the control
		  // Setting padding to 5 px will offset the control
		  // from the edge of the map
		  controlDiv.style.padding = '5px';

		  // Set CSS for the control border
		  var controlUI = document.createElement('div');
		  controlUI.style.backgroundColor = 'white';
		  controlUI.style.borderStyle = 'solid';
		  controlUI.style.borderWidth = '1px';
		  controlUI.style.borderColor = '#aaa';
		  controlUI.style.cursor = 'pointer';
		  controlUI.style.textAlign = 'center';
		  controlUI.title = 'Click to set the map to Home';
		  controlDiv.appendChild(controlUI);
		  });
			/* code for Center custom control */
		}

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

			// Create the DIV to hold the control and
			// call the homeControl() constructor passing
			// in this DIV.
			var homeControlDiv = document.createElement('div');
			var homeControl = new HomeControl(homeControlDiv, map);

			homeControlDiv.index = 2;
			map.controls[google.maps.ControlPosition.LEFT_CENTER].push(homeControlDiv);
										
		}
	
		google.maps.event.addDomListener(window, 'load', initialize);

});