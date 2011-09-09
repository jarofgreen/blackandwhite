<?php
/**
 * Licensed under the GNU Affero General Public License
 * http://www.gnu.org/licenses/agpl.html
 */

if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
	header('Location: /');
	die();
}

$lat = -1 * floatval($_GET['lat']);
$lng = floatval($_GET['lng']);
$lng = $lng > 0 ? $lng - 180 : $lng + 180;

?>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8" />
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
		<title>Black and White</title>
		<link rel="stylesheet" href="main.css" type="text/css" />
	</head>
	<body>
		<div id="BodyWrapper">

			<div id="MapPage">
				<div id="Map"></div>
				<p id="LoadingPleaseWait">Loading photos, please wait ...</p>
				<p id="ClickOnAMarker" style="display: none;">Click on any <img src="http://www.openlayers.org/dev/img/marker.png"> to see some photos!</p>
				<form action="/" method="get" class="goFromMap" id="TryAnotherLocation" style="display: none;">
					<input type="submit" value="Cool, let me try another location!">
				</form>
			</div>
			
			<div id="PhotoPage" style="display: none;">
				<div id="PhotoPreview"></div>
				<p id="PhotoText"></p>
				<ul id="Photos"></ul>
				<form action="#" onsubmit="returnToMap(); return false;" class="goFromMap">
					<input type="submit" value="Back to map!">
				</form>
			</div>

			<div id="Footer">
				All photos are copyright of their respective owners.<br>
				<a href="http://www.maxmind.com">Geoip by MaxMind</a>.<br>
				This product uses the Flickr API but is not endorsed or certified by Flickr.<br>
				Original Idea by <a href="http://oliland.net/">Oli Kingshott</a> + others.<br>
				Programming by <a href="http://uk.linkedin.com/in/jamesbaster">James Baster</a>.<br>
				Thanks to <a href="http://jquery.com/">JQuery</a>, <a href="http://www.openstreetmap.org/">OpenStreetMap</a> and <a href="http://www.openlayers.org/">OpenLayers</a>.<br>
				Code on <a href="http://github.com/jarofgreen/blackandwhite">GitHub</a>
			</div>

		</div>

		<script>
			var lat = <?php print $lat ?>;
			var lng = <?php print $lng ?>;
		</script>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://openlayers.org/api/OpenLayers.js"></script>
		<script type="text/javascript" src="page2.js"></script>

	</body>
</html>
