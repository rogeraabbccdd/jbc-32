<?php
header('Content-type: application/json; charset=utf-8');
$jsonData = json_decode(file_get_contents('packList.json', 'UTF-8'));
$from = (isset($_GET['head']) ? (int)$_GET['head'] : 0);
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$list = [];
for ($i = 0; $i < $limit; $i++) {
    if (isset($jsonData->PackList[$from + $i])) {
        array_push($list, $jsonData->PackList[$from + $i]);
    }
}
if ($from === 0) {
    $result = [
        'Version' => '3.9.10',
        'Promotion' => $jsonData->Promotion,
        'Genre' =>  $jsonData->Genre,
        'GenreMetaInfo' => $jsonData->GenreMetaInfo,
        'HasNext' => isset($jsonData->PackList[$limit + $from + 1]),
        'Date' => '202009',
        'PackList' => $list
    ];
} else {
    $result = [
        'Version' =>  '3.9.10',
        'Promotion' => $jsonData->Promotion,
        'Genre' =>  $jsonData->Genre,
        'GenreMetaInfo' => $jsonData->GenreMetaInfo,
        'HasNext' => isset($jsonData->PackList[$limit + $from + 1]),
        'Date' => '202009',
        'PackList' => $list
    ];
}


for ($i = 0; $i < count($result['PackList']); $i++) {
    $result['PackList'][$i]->extData = false;
    $result['PackList'][$i]->IsNew = false;
    if ($result['PackList'][$i]->ID >= 10410) {
        if (file_exists('config/packInfo'.$result['PackList'][$i]->ID.'.json')) {
        $subData = json_decode(file_get_contents('config/packInfo'.$result['PackList'][$i]->ID.'.json'));
            for ($j = 0; $j < count($subData->MusicList); $j++) {
                if (isset($subData->MusicList[$j]->extID)) {
                    $result['PackList'][$i]->extData = true;
                    break;
                }
            }
        }
    } else {
        if (file_exists('config/packInfo.json')) {
            $subList = json_decode(file_get_contents('config/packInfo.json'));
            for ($j = 0; $j < count($subList); $j++) {
                if ($subList[$j]->ID === $result['PackList'][$i]->ID) {
                    for ($k = 0; $k < count($subList[$j]->MusicList); $k++) {
                        if (isset($subList[$j]->MusicList[$k]->extID)) {
                            $result['PackList'][$i]->extData = true;
                            break;
                        }
                    }
                    if ($result['PackList'][$i]->extData) {
                        break;
                    }
                }
            }
        }
    }
}
$output = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$key = 'i3yuYjsZeKQq9zZ7dbm18Buwt6LioKJdfeGD7pMirHuTwfcC2vohdEnBNz9lkkld';
$hash = hash('sha256', $key.$output);
echo $hash.$output;