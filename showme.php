<?php
require 'config.php';

if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
	header('Location: /');
	die();
}

$lat = -1 * floatval($_GET['lat']);
$lng = floatval($_GET['lng']);
$lng = $lng > 0 ? $lng - 180 : $lng + 180;

$latMin = max($lat - BOX_SIZE,-90);
$latMax = min($lat + BOX_SIZE,90);
$lngMin = max($lng - BOX_SIZE,-180);
$lngMax = min($lng + BOX_SIZE,180);

$url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".API_KEY.
	"&bbox=".$lngMin.",".$latMin.",".$lngMax.",".$latMax.
	"&safe_search=1&per_page=30&min_upload_date=".(time()-200*24*60*60);
//print $url; die();
print $url;


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
	$out['url_thumb'] = "http://farm".$out['farm'].".static.flickr.com/".$out['server']."/".$out['id']."_".$out['secret']."_t.jpg";
	$out['url_page'] = "http://www.flickr.com/photos/".$out['owner']."/".$out['id'];
	$photos[] = $out;
}

?>
<html>
	<head>
		<link rel="stylesheet" href="main.css" type="text/css" />
	</head>
	<body>
		<div id="BodyWrapper">

			<ul class="photos">
				<?php foreach($photos as $photo) { ?>
					<li><a href="<?php print $photo['url_page'] ?>"><img src="<?php print $photo['url_thumb'] ?>"></a></li>
				<?php } ?>
			</ul>


			<div id="Footer">
				<a href="http://www.maxmind.com">Geoip by MaxMind</a>.
				This product uses the Flickr API but is not endorsed or certified by Flickr.
			</div>

		</div>
	</body>
</html>
