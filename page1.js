var map, layer;

$(document).ready(function() {

	map = new OpenLayers.Map( 'Map');
	layer = new OpenLayers.Layer.OSM( "Simple OSM Map");
	map.addLayer(layer);
	map.setCenter(
		new OpenLayers.LonLat(-71.147, 42.472).transform(
			new OpenLayers.Projection("EPSG:4326"),
			map.getProjectionObject()
		), 4
	);


});



