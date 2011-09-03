<?php
require 'config.php';

if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
	header('Location: /');
	die();
}

$lat = -1 * floatval($_GET['lat']);
$lng = floatval($_GET['lng']);
$lng = $lng > 0 ? $lng - 180 : $lng + 180;

$latMin = max($lat - BOX_SIZE_LAT,-90);
$latMax = min($lat + BOX_SIZE_LAT,90);
$lngMin = max($lng - BOX_SIZE_LNG,-180);
$lngMax = min($lng + BOX_SIZE_LNG,180);

$url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".API_KEY.
	"&bbox=".$lngMin.",".$latMin.",".$lngMax.",".$latMax.
	"&safe_search=1&per_page=30&extras=geo&min_upload_date=".(time()-200*24*60*60);
//print $url; die();
//print $url;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);


//var_dump($data);

$xmlDoc = new DOMDocument();
$xmlDoc->loadXML($data);

$photoNodeList = $xmlDoc->getElementsByTagName('photo');
$photoNodeListLength = $photoNodeList->length;  
$photos = array();
for($pos=0; $pos<$photoNodeListLength; $pos++) {
	$x = $photoNodeList->item($pos);
	$out = array();
	$out['id'] = $x->attributes->getNamedItem('id')->nodeValue;
	$out['farm'] = $x->attributes->getNamedItem('farm')->nodeValue;
	$out['server'] = $x->attributes->getNamedItem('server')->nodeValue;
	$out['secret'] = $x->attributes->getNamedItem('secret')->nodeValue;
	$out['owner'] = $x->attributes->getNamedItem('owner')->nodeValue;
	$out['lng'] = $x->attributes->getNamedItem('longitude')->nodeValue;
	$out['lat'] = $x->attributes->getNamedItem('latitude')->nodeValue;
	$out['url_thumb'] = "http://farm".$out['farm'].".static.flickr.com/".$out['server']."/".$out['id']."_".$out['secret']."_t.jpg";
	$out['url_page'] = "http://www.flickr.com/photos/".$out['owner']."/".$out['id'];
	$photos[$pos] = $out;
}

?>
<html>
	<head>
		<link rel="stylesheet" href="main.css" type="text/css" />
	</head>
	<body>
		<div id="BodyWrapper">

			<div id="smallMap"></div>

			<ul class="photos">
				<?php foreach($photos as $idx=>$photo) { ?>
					<li id="Photo<?php print $idx ?>"><a href="<?php print $photo['url_page'] ?>"><img src="<?php print $photo['url_thumb'] ?>"></a></li>
				<?php } ?>
			</ul>

			<form action="/" method="get" class="goFromMap">
				<input type="submit" value="Cool, let me try another location!">
			</form>

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
			var data = new Array();
			<?php $i = 0; foreach($photos as $idx=>$photo) { ?>
			data[<?php print $i ?>] = { idx: <?php print $idx ?>, lat: <?php print $photo['lat'] ?>, lng: <?php print $photo['lng'] ?> };
			<?php  $i++; } ?>
		</script>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://openlayers.org/api/OpenLayers.js"></script>
		<script type="text/javascript" src="page2.js"></script>

	</body>
</html>
