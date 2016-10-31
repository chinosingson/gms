(function ($) {
	
	Drupal.behaviors.projectMap = {
		attach: function (context, settings) {
		jQuery('html:has(body.page-node-edit.node-type-project), html:has(body.page-node-add-project)', context).once('ready',function(){
			var map;
			var googleRoad = new L.Google('ROADMAP');
			
			//console.log(context);
			//console.log(settings);
			//console.log(Drupal.settings.mapMarker.imagePath);

			map = new L.Map("map-canvas", {
					zoom : 6,
					center : [17.180,104.124], // provisional center of GMS program locations
					layers : [googleRoad],
					zoomControl : true,
					drawControl : false,
					attributionControl : true,
					scrollWheelZoom : false,
			});
			
			var defaultMarkerIcon = {
				iconUrl : Drupal.settings.mapMarker.imagePath,
				iconSize : [25, 29],
				iconAnchor : [12, 28],
				popupAnchor : [0, -25]
			}	

			var drawnItems = L.geoJson(null, {
				pointToLayer : function (feature, latlng) {
					return L.marker(latlng, {
						icon : L.icon(defaultMarkerIcon),
						title : 'new marker', //feature.properties.coordinates,
						riseOnHover : true
					});
				},
				onEachFeature : function (feature, layer){
					//console.log('layer');
					//console.log(layer);
					layer.on({
						click : function(e) {
							console.log('drawnItems - onEachFeature - onClick');
							console.log(feature);
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
			//var currentProjectLayer = L.geoJson(null);
			var tempMarkers = [];
			
			//console.log(drawnItems);
			
			//console.log(Drupal.settings);
			var markerCount = 0;
			//if(typeof Drupal.settings.locations != 'undefined'){ //drawnItems.addData(Drupal.settings.locations.markers);
			if(Drupal.settings.locations.markers!=null){ //drawnItems.addData(Drupal.settings.locations.markers);
				jQuery.each(Drupal.settings.locations.markers, function(index, marker){
					//console.log('marker');
					//console.log(marker);
					tempMarkers.push(marker);
					drawnItems.addData(marker);
					markerCount++;
				});
				var projectBounds = drawnItems.getBounds();
				if (markerCount > 1) {
					var origZoom = map.getBoundsZoom(projectBounds);
					map.fitBounds(projectBounds,{padding:[50,50],maxZoom:origZoom});
				} else {
					map.setView(projectBounds.getCenter(),7);
					//console.log('fitboundszoom: '+map.getBoundsZoom(projectBounds));
				}
			} else {
				Drupal.settings.locations.markers = [];
			}
			drawnItems.addTo(map);
			
			//console.log(drawnItems);
			
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
								"id": 'marker_'+markerCount,
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
					drawnItems.addData(marker);
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
			
			map.on('draw:deleted', function (e) {
				console.log('draw:deleted > ' + e.layer);
			});

			map.on('draw:deletestop', function (e) {
				console.log('draw:deletestop > ');
				jQuery.each(drawnItems._layers, function (index, layer) {
					//$('#loc-info-'+layer.feature.properties.id).show();
					//$('#temp-markers').val(JSON.stringify(tempMarkers));
					//drawnItems.removeLayer(layer);
					console.log(layer);
				});
				var locJson = jsonifyLocations(drawnItems);
				// set the string as the value of the Body field
				$('#edit-body-und-0-value').val(locJson);
			});
			
			function jsonifyLocations(itemCollection){
				console.log('jsonifyLocations > itemCollection >');
				//console.log(itemCollection);
				// create a JSON-encoded string from the marker data in drawnItems
				var tempString = [];
				jQuery.each(itemCollection._layers,function(index,layer){
					tempString.push(layer.feature);
					console.log(layer.feature.properties.id+': '+layer.feature.geometry.coordinates);
				});
				//console.log(JSON.stringify(tempString));
				return JSON.stringify(tempString);
			}
			
			map.on('draw:edited', function(e){
				var layers = e.layers;
				//drawnItems._layers = e.layers;
				//drawnItems.clearLayers();
				//console.log(drawnItems);
				layers.eachLayer(function(layer){
					console.log(layer.feature.properties.id + ': ' + layer.getLatLng().toString());
					//layer.update();
					//newLatLng = layer.getLatLng();
					//dil = drawnItems.getLayer(layer._leaflet_id);
					layer.feature.geometry.coordinates = [drawnItems.getLayer(layer._leaflet_id).getLatLng().lng, drawnItems.getLayer(layer._leaflet_id).getLatLng().lat];
					//console.log(layer.feature.properties.id + ': ' + drawnItems.getLayer(layer._leaflet_id).getLatLng());
					//drawnItems.addLayer(layer);
					//dil.update();
					//dil.setLatLng(newLatLng);
					//console.log(drawnItems.length);
					//drawnItems.addLayer(layer);
					//createPoint(layer);
				});
				console.log(drawnItems);
			});
			
			// when the 'Save' button is clicked
			$('#edit-submit').click(function(e){
			//$('#test-btn').click(function(e){
				var locJson = jsonifyLocations(drawnItems);
				//console.log('locJson:'+locJson);
				// set the string as the value of the Body field
				//$('#edit-body-und-0-value').val(locJson);
				$('#edit-field-location-temp-und-0-value').val(locJson);
				//console.log($('#edit-body-und-0-value')[0].value);
			});
			var mydata = 'test';
			});
		}
	}
}(jQuery));