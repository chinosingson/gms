jQuery(document).ready(function(){	
	var map;
	var googleRoad = new L.Google('ROADMAP');

	map = new L.Map("map-canvas", {
			zoom : 5,
			center : [17.180,104.124], // provisional center of GMS program locations
			layers : [googleRoad],
			zoomControl : true,
			drawControl : false,
			attributionControl : true,
			scrollWheelZoom : true,
	});
	
	var defaultMarkerIcon = {
		iconUrl : Drupal.settings.mapMarker.imagePath,
		iconSize : [33, 38],
		iconAnchor : [16, 38],
		popupAnchor : [0, -25]
	}	

	var drawnItems = L.geoJson(null, {
		pointToLayer : function (feature, latlng) {
			return L.marker(latlng, {
				icon : L.icon(defaultMarkerIcon),
				title : feature.properties.coordinates,
				riseOnHover : true
			});
		},
		onEachFeature : function (feature, layer){
			layer.on({
				click : function(e) {
					console.log('drawnItems - onEachFeature - onClick');
					console.log(feature.properties);
				}
			});
		}
	});//.addTo(map);

	var drawControl = new L.Control.Draw({
		position: 'topright',
		draw: {
			circle: false,
			rectangle: false,
			polygon: false,
			polyline: false,
			marker: {
				icon: L.icon(defaultMarkerIcon),
				repeatMode: true
			}
		},
		edit: {
			featureGroup: drawnItems,
			remove: true
		}
	}).addTo(map);

	//var center = [17.180,104.124];	
	//map.setView(center, 5);
	
	/* MAP FUNCTIONS and EVENTS */
	var markerCount = 1;
	var currentProjectLayer = L.geoJson(null);
	
	if(Drupal.settings.locations.markers){ //drawnItems.addData(Drupal.settings.locations.markers);
		jQuery.each(Drupal.settings.locations.markers, function(index, marker){
			drawnItems.addData(marker);
			var projectBounds = drawnItems.getBounds();
			map.setView(projectBounds.getCenter(),5);
		});
	}
	
	drawnItems.addTo(map);
	
	function createPoint(e) {
			var layer = e.layer,
			mLatLng = layer.getLatLng();
			console.log(e.layerType + ' ' + mLatLng);
			// optionally add reverse geocode here
			// - to automatically fill in details
			// new geojsonfeature
			var marker = {
					"type": "Feature",
					"properties": {
						"id": markerCount,
					},
					"geometry": {
						"type": "Point",
						"coordinates": [mLatLng.lng, mLatLng.lat]
					}
			};
			// add geojsonfeature to drawnItems
			var shortLongitude = parseFloat(mLatLng.lat).toFixed(2);
			var shortLatitude = parseFloat(mLatLng.lng).toFixed(2);
			
			layer.bindPopup('<b>Coordinates</b><br/>Latitude: ' + shortLatitude + '<br/>Longitude: ' + shortLongitude);
			//markerCount++;
			console.log(marker);
			drawnItems.addData(marker);
			//console.log(drawnItems);
	}	
	
	/*map.on('draw:drawstart', function (e){
		console.log('drawstart');
		//console.log(e);
		//e.layer.setIcon(L.icon(yellowMarkerIcon));
	});*/
	 
	map.on('draw:created', function (e) {
		//alert(e.layerType);
		//console.log(e.layer);
		createPoint(e);
		//$('#layers').html(drawnItems);
	});

	map.on('draw:drawstop', function (e) {
		console.log('drawstop');
		//$('#map-location-details').append('123456');
		//map.addControl(new locInfoControl());
		//locInfoControlAdded = true;
		markerCount++;
	});

	map.on('draw:deletestop', function (e) {
		jQuery.each(drawnItems._layers, function (index, layer) {
			//$('#loc-info-'+layer.feature.properties.id).show();
			console.log(layer);
		});
	});

	
});