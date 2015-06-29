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

		  // Set CSS for the control interior
		  var controlText = document.createElement('div');
		  controlText.style.fontFamily = 'Arial,sans-serif';
		  controlText.style.fontSize = '12px';
		  controlText.style.paddingLeft = '4px';
		  controlText.style.paddingRight = '4px';
		  controlText.innerHTML = '<b>Center on CTI</b>';
		  controlUI.appendChild(controlText);

		  // Setup the click event listeners: simply set the map to
		  // Chicago
		  google.maps.event.addDomListener(controlUI, 'click', function() {
			map.setCenter(ctiMapCenter)
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
			
			
			

			/*var myParser = new geoXML3.parser({//*** ORIGINAL: only {map: map});
                map: map, singleInfoWindow: true,
                createMarker: function(placemark) {
                    //Constructing marker for each Placemark node, and then add it to the markclusterer
                    var point = new google.maps.LatLng(placemark.point.lat, placemark.point.lng);
                    var marker = new google.maps.Marker({position: point});
                    mc.addMarker(marker);

                    google.maps.event.addListener(marker, 'click', function() {
                        var marker_lat = marker.getPosition().lat();
                        var marker_lng = marker.getPosition().lng();
                        infoWindow.close();
                        infoWindow.setOptions({maxWidth: 800});
                        content = '<strong>' + placemark.name + '</strong><br>' + placemark.description;
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });
                    mc.addMarker(marker);
                }
            });

            myParser.parse('http://www.ctimap.org/locations/kml/cti.kml');*/

			

			/*var mcOptions = {gridSize: 50, maxZoom: 15};
			getJSONP('http://www.ctimap.org/locations/json&format=js&callback=?', function(data){
				console.log(data);
			});  
			for (var i = 0; i < 100; i++) {
			  var locationLocations = location.locations[i];
			  var latLng = new google.maps.LatLng(locationLocations.field_coordinates,
				  locationLocations.field_coordinates_1);
			  var marker = new google.maps.Marker({
				position: latLng
			  });
			  markers.push(marker);
			}
			var mc = new MarkerClusterer(map, markers, mcOptions);*/
			var kmlLayer = new google.maps.KmlLayer('http://www.ctimap.org/locations/kml/cti.kml');
			kmlLayer.setMap(map);
						
		}
	
		google.maps.event.addDomListener(window, 'load', initialize);
});