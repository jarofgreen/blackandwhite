/**
 * Licensed under the GNU Affero General Public License
 * http://www.gnu.org/licenses/agpl.html
 */
var map, layer, markerLayer, marker;

var size = new OpenLayers.Size(21,25);
var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png',size,offset);


$(document).ready(function() {

	map = new OpenLayers.Map( 'Map');
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
		eval("f = function(evt) { markerClicked("+data[i].lat+","+data[i].lng+");  OpenLayers.Event.stop(evt); }");
		marker.events.register('mousedown', marker, f);
		markerLayer.addMarker(marker);
	}

});

function markerClicked(lat,lng) {
	$('#MapPage').hide();

	var html = '';
	var firstID = -1;
	for(i in data) {
		if (data[i].lat == lat && data[i].lng == lng) {
			html += '<li><a href="#" onclick="photoClicked('+i+'); return false;"><img src="'+data[i].thumb+'"></li>';
			if (firstID == -1) firstID = i;
		}
	}

	$('ul#Photos').html(html);

	$('#PhotoPage').show();

	photoClicked(firstID);
}

function photoClicked(idx) {
	$('#PhotoPreview').html('<img src="'+data[idx].image+'">');
	$('#PhotoText').html('<a href="'+data[idx].page+'" target="_new">'+data[idx].page+'</a>');
}