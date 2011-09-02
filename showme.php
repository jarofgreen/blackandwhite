<?php
if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
	header('Location: /');
	die();
}
$lat = floatval($_GET['lat']);
$lng = floatval($_GET['lng']);

# TODO go to the other side of the world here!


require 'config.php';
$url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".
	API_KEY."&lat=".$lat."&lng=".$lng."&radius=1000&safe_search=1&per_page=30&min_upload_date=".(time()-200*24*60*60);
//print $url; die();


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
