var map, layer, markerLayer, marker;

var size = new OpenLayers.Size(21,25);
var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png',size,offset);


$(document).ready(function() {

	map = new OpenLayers.Map( 'Map');
	layer = new OpenLayers.Layer.OSM( "Simple OSM Map");
	map.addLayer(layer);
	map.setCenter(
		new OpenLayers.LonLat(geoip_longitude(), geoip_latitude()).transform(
			new OpenLayers.Projection("EPSG:4326"),
			map.getProjectionObject()
		), 4
	);

	markerLayer = new OpenLayers.Layer.Markers( "Markers" );
	map.addLayer(markerLayer);



	setLocation(geoip_latitude(),geoip_longitude());

});

function setLocation(lat,lng) {
	$('#LatVal').val(lat);
	$('#LngVal').val(lng);

	var pos = new OpenLayers.LonLat(lng,lat).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
	marker = new OpenLayers.Marker(pos,icon);
	markerLayer.addMarker(marker);

}


