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
		new OpenLayers.LonLat(geoip_longitude(), geoip_latitude()).transform(
			new OpenLayers.Projection("EPSG:4326"),
			map.getProjectionObject()
		), 4
	);

	markerLayer = new OpenLayers.Layer.Markers( "Markers" );
	map.addLayer(markerLayer);

	setLocation(geoip_latitude(),geoip_longitude());

	OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
		defaultHandlerOptions: {
			'single': true,
			'double': false,
			'pixelTolerance': 0,
			'stopSingle': false,
			'stopDouble': false
		},

		initialize: function(options) {
			this.handlerOptions = OpenLayers.Util.extend(
				{}, this.defaultHandlerOptions
			);
			OpenLayers.Control.prototype.initialize.apply(
				this, arguments
			);
			this.handler = new OpenLayers.Handler.Click(
				this, {
					'click': this.trigger
				}, this.handlerOptions
			);
		},

		trigger: function(e) {
			var lonlat = map.getLonLatFromViewPortPx(e.xy).transform(map.getProjectionObject(),new OpenLayers.Projection("EPSG:4326"));
			setLocation(lonlat.lat,lonlat.lon);
		}

	});

	var click = new OpenLayers.Control.Click();
	map.addControl(click);
	click.activate();

});

function setLocation(lat,lng) {
	$('#LatVal').val(lat);
	$('#LngVal').val(lng);

	if (marker) {
		markerLayer.removeMarker(marker);
	}

	var pos = new OpenLayers.LonLat(lng,lat).transform(new OpenLayers.Projection("EPSG:4326"),map.getProjectionObject());
	marker = new OpenLayers.Marker(pos,icon);
	markerLayer.addMarker(marker);

}


