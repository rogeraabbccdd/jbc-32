<?php
include('fixDownload.php');
header('Content-type: application/json; charset=utf-8');
$pack = isset($_GET['pack']) ? (int)$_GET['pack'] : 10000;
$data = [];
if ($pack >= 10410) {
	$data = json_decode(file_get_contents('config/packInfo'.$pack.'.json'));
} else {
	$jsonData = json_decode(file_get_contents('config/packInfo.json'));
	for ($i = 0; $i < count($jsonData); $i++) {
		if ($jsonData[$i]->ID === $pack) {
			$data = $jsonData[$i];
		}
	}
}
if (isset($data->MusicList)) {
	for ($i = 0; $i < count($data->MusicList); $i++) {
		$data->MusicList[$i]->ItemURL = fixDL($data->MusicList[$i]->ID, $data->MusicList[$i]->ItemURL);
		if (isset($data->MusicList[$i]->extURL)) {
			$data->MusicList[$i]->extURL = fixDL($data->MusicList[$i]->extID, $data->MusicList[$i]->extURL);
		}
	}
}
$output = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$key = 'i3yuYjsZeKQq9zZ7dbm18Buwt6LioKJdfeGD7pMirHuTwfcC2vohdEnBNz9lkkld';
$hash = hash('sha256', $key.$output);
echo $hash.$output;