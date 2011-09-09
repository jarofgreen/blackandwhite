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

	markerLayer = new OpenLayers.Layer.Markers( "Markers" );
	map.addLayer(markerLayer);

	for(i in data) {
		var pos = new OpenLayers.LonLat(data[i].lng,data[i].lat).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
		var marker = new OpenLayers.Marker(pos,icon.clone());
		eval("f = function(evt) { markerClicked("+data[i].lat+","+data[i].lng+");  OpenLayers.Event.stop(evt); }");
		marker.events.register('mousedown', marker, f);
		markerLayer.addMarker(marker);
	}

	map.zoomToExtent(markerLayer.getDataExtent());

});

function markerClicked(lat,lng) {
	$('#MapPage').hide();

	var html = '';
	var firstID = -1;
	var count = 0;
	for(i in data) {
		// flickr api terms say only 30 thumbnails per results page
		if (count < 30 && roundLatLng(data[i].lat) == roundLatLng(lat) && roundLatLng(data[i].lng) == roundLatLng(lng)) {
			html += '<li><a href="#" onclick="photoClicked('+i+'); return false;"><img src="'+data[i].thumb+'"></li>';
			if (firstID == -1) firstID = i;
			count++;
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

function roundLatLng(num) {
	// flickr API seems to return lat & lng rounded to 6 so we'll use that to.
	return Math.round(num*Math.pow(10,6))/Math.pow(10,6);
}