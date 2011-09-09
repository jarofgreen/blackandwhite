<?php
/**
 * Licensed under the GNU Affero General Public License
 * http://www.gnu.org/licenses/agpl.html
 */
require 'config.php';


if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
	header('Location: /');
	die();
}

$lat = floatval($_GET['lat']);
$lng = floatval($_GET['lng']);


$latMin = max($lat - BOX_SIZE_LAT,-90);
$latMax = min($lat + BOX_SIZE_LAT,90);
$lngMin = max($lng - BOX_SIZE_LNG,-180);
$lngMax = min($lng + BOX_SIZE_LNG,180);

$url = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=".API_KEY.
	"&bbox=".$lngMin.",".$latMin.",".$lngMax.",".$latMax.
	"&safe_search=1&per_page=".LOAD_THIS_MANY_PHOTOS."&extras=geo&min_upload_date=".(time()-200*24*60*60);
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
	$id = $x->attributes->getNamedItem('id')->nodeValue;
	$farm = $x->attributes->getNamedItem('farm')->nodeValue;
	$server = $x->attributes->getNamedItem('server')->nodeValue;
	$secret = $x->attributes->getNamedItem('secret')->nodeValue;
	$owner = $x->attributes->getNamedItem('owner')->nodeValue;
	$out['lng'] = $x->attributes->getNamedItem('longitude')->nodeValue;
	$out['lat'] = $x->attributes->getNamedItem('latitude')->nodeValue;
	$out['thumb'] = "http://farm".$farm.".static.flickr.com/".$server."/".$id."_".$secret."_t.jpg";
	$out['image'] = "http://farm".$farm.".static.flickr.com/".$server."/".$id."_".$secret."_z.jpg";
	$out['page'] = "http://www.flickr.com/photos/".$owner."/".$id;
	$photos[$pos] = $out;
}


header('Content-type: application/json');
print json_encode($photos);