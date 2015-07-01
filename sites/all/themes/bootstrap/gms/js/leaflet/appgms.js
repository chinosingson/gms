jQuery(document).ready(function(){	
	//console.log(Drupal.settings.pathToTheme.pathToTheme);

	var map;
	var googleRoad = new L.Google('ROADMAP');
	/* Basemap Layers */
	/*var mapquestOSM = L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png", {
		maxZoom : 19,
		subdomains : ["otile1", "otile2", "otile3", "otile4"],
		attribution : 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA.'
	});
	var mapquestOAM = L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg", {
		maxZoom : 18,
		subdomains : ["oatile1", "oatile2", "oatile3", "oatile4"],
		attribution : 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a>. Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency'
	});
	var mapquestHYB = L.layerGroup([L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg", {
				maxZoom : 18,
				subdomains : ["oatile1", "oatile2", "oatile3", "oatile4"]
			}), L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/hyb/{z}/{x}/{y}.png", {
				maxZoom : 19,
				subdomains : ["oatile1", "oatile2", "oatile3", "oatile4"],
				attribution : 'Labels courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA. Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency'
			})]);
			
	var Esri_WorldStreetMap = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
		attribution: 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'
	});	*/

	var HERE_normalDay = L.tileLayer('http://{s}.{base}.maps.cit.api.here.com/maptile/2.1/maptile/{mapID}/normal.day/{z}/{x}/{y}/256/png8?app_id=Fw3lzrFk4fHWP8VnBsGF&app_code=J2HoQBUXj_bIuNbI9rQ-oQ', {
		attribution: 'Map &copy; 1987-2014 <a href="http://developer.here.com">HERE</a>',
		subdomains: '1234',
		mapID: 'newest',
		app_id: 'Fw3lzrFk4fHWP8VnBsGF',
		app_code: 'J2HoQBUXj_bIuNbI9rQ-oQ',
		base: 'base',
		minZoom: 0,
		maxZoom: 20
	});

	map = new L.Map("map-canvas", {
			zoom : 5,
			center : [17.180,104.124],
			//layers : [HERE_normalDay],
			layers : [googleRoad],
			zoomControl : true,
			drawControl : false,
			attributionControl : true,
			scrollWheelZoom : true,
	});
	
	var defaultMarkerIcon = {
		iconUrl : Drupal.settings.pathToTheme.pathToTheme+"/js/leaflet/images/marker-icon.png",
		//iconSize : [25, 41],
		iconAnchor : [15, 50],
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
	
	//console.log(Drupal.settings.locations.markers);
	if(Drupal.settings.locations.markers){ //drawnItems.addData(Drupal.settings.locations.markers);
		jQuery.each(Drupal.settings.locations.markers, function(index, marker){
			drawnItems.addData(marker);
			var projectBounds = drawnItems.getBounds();
			map.setView(projectBounds.getCenter(),5);
		});
	}
	
	//console.log(drawnItems);
	//drawnItems.addData(currentProjectLayer);
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