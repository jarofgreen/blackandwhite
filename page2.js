var map, layer, markerLayer, marker;

var size = new OpenLayers.Size(21,25);
var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png',size,offset);


$(document).ready(function() {

	map = new OpenLayers.Map( 'smallMap');
	layer = new OpenLayers.Layer.OSM( "Simple OSM Map");
	map.addLayer(layer);
	map.setCenter(
		new OpenLayers.LonLat(lng, lat).transform(
			new OpenLayers.Projection("EPSG:4326"),
			map.getProjectionObject()
		), 2
	);

	markerLayer = new OpenLayers.Layer.Markers( "Markers" );
	map.addLayer(markerLayer);

	for(i in data) {
		var pos = new OpenLayers.LonLat(data[i].lng,data[i].lat).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
		var marker = new OpenLayers.Marker(pos,icon.clone());
		markerLayer.addMarker(marker);
	}

});
