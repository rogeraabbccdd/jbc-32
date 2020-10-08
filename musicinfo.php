<?php
header('Content-type: application/json; charset=utf-8');
include('fixDownload.php');
header('Content-type: application/json; charset=utf-8');
$musicId = isset($_GET['music']) ? (int)$_GET['music'] : 100000101;
$list = json_decode(file_get_contents('config/packInfo.json'));
$output = '';
for ($i = 0; $i < count($list); $i++) {
    for ($j = 0; $j < count($list[$i]->MusicList); $j++) {
        if ($list[$i]->MusicList[$j]->ID === (int)$musicId) {
            $output = $list[$i]->MusicList[$j];
			unset($output->extURL); 
			unset($output->extID); 
			unset($output->playable); 
            break;
        } else if (isset($list[$i]->MusicList[$j]->extID) && $list[$i]->MusicList[$j]->extID === (int)$musicId) {
            $output = $list[$i]->MusicList[$j];
			$output->ItemURL = $output->extURL;
			$output->ID = $output->extID;
			unset($output->extURL); 
			unset($output->extID); 
			unset($output->playable); 
            break;
        }
    }
}
if ($output !== '') {
    $output->ItemURL = fixDL($output->ID, $output->ItemURL);
    echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}
for ($i = 10410; $i < 10424; $i++) {
    if (file_exists('config/packinfo'.$i.'.json')) {
        $data = json_decode(file_get_contents('config/packinfo'.$i.'.json'));
        for ($j = 0; $j < count($data->MusicList); $j++) {
            if ($data->MusicList[$j]->ID === (int)$musicId) {
                $output = $data->MusicList[$j];
                break;
            }
			if ($data->MusicList[$j]->ID === (int)$musicId) {
				$output = $data->MusicList[$j];
				unset($output->extURL); 
				unset($output->extID); 
				unset($output->playable); 
				break;
			} else if (isset($data->MusicList[$j]->extID) && $data->MusicList[$j]->extID === (int)$musicId) {
				$output = $data->MusicList[$j];
				$output->ItemURL = $output->extURL;
				$output->ID = $output->extID;
				unset($output->extURL); 
				unset($output->extID); 
				unset($output->playable); 
				break;
			}
        }
    }
}
if ($output) {
    $output->ItemURL = fixDL($output->ID, $output->ItemURL);
    echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
    echo '{}';
}