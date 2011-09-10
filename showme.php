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
				<p id="LoadingPleaseWait"><img src="loading.gif"> Loading photos, please wait ...</p>
				<p id="ClickOnAMarker" style="display: none;">Click on any <img src="http://www.openlayers.org/dev/img/marker.png"> to see some photos!</p>
				<form action="/" method="get" class="goFromMap" id="TryAnotherLocation" style="display: none;">
					<input type="submit" value="Cool, let me try another location!">
				</form>
				<div id="Map"></div>
			</div>
			
			<div id="PhotoPage" style="display: none;">
				<form action="#" onsubmit="returnToMap(); return false;" class="goFromMap">
					<input type="submit" value="Back to map!">
				</form>
				<div id="PhotoPreview"></div>
				<p id="PhotoText"></p>
				<ul id="Photos"></ul>				
			</div>

			<div id="Footer">
				All photos are copyright of their respective owners.<br>
				This product uses the Flickr API but is not endorsed or certified by Flickr.<br>
				Original Idea by <a href="http://oliland.net/">Oli Kingshott</a> + others from The Sultancy team.<br>
				Programming by <a href="http://uk.linkedin.com/in/jamesbaster">James Baster</a>.<br>
				Thanks to <a href="http://jquery.com/">JQuery</a>, <a href="http://www.openstreetmap.org/">OpenStreetMap</a>,
				<a href="http://www.openlayers.org/">OpenLayers</a> and <a href="http://www.maxmind.com">MaxMind</a>.<br>
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
