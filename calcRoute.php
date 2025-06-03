<?php
require_once "./config.php";
include_once 'db.php'; 

function getEndpoint(): string
{
    $config = getConfiguration("./config.txt");
    $key = $config['graphhopper_key'];
    return "https://graphhopper.com/api/1/route?key=$key"; 
}

if($_SERVER['REQUEST_METHOD'] != 'GET') {
    die(405);
}


$comuneArrivo =  ottieniComune($_GET['comuneArrivoId']);
$comunePartenza =  ottieniComune(comune_id: $_GET['comunePartenzaId']);
    
$pointA = $comuneArrivo['latitudine'].','.$comuneArrivo['longitudine'];
$pointB = $comunePartenza['latitudine'].','.$comunePartenza['longitudine'];

$url = getEndpoint();

$url .= '&point=' . urlencode($pointA);
$url .= '&point=' . urlencode($pointB);

$params = [
    'profile' => 'car',
    'vehicle' => 'car',
    'locale' => 'en',
    'calc_points' => 'true',
    'points_encoded' => 'true',
    'details' => ['road_class', 'surface'],
    'snap_preventions' => ['motorway', 'ferry', 'tunnel'],
    'instructions' => 'false',
    'avoid_highways' => 'false',   
    'avoid_tolls' => 'false'
];

foreach ($params as $key => $value) {
    if (is_array($value)) {
        foreach ($value as $item) {
            $url .= '&' . urlencode($key) . '=' . urlencode($item);
        }
    } else {
        $url .= '&' . urlencode($key) . '=' . urlencode($value);
    }
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
curl_close($curl);

header('Content-Type: application/json');
echo $response;

?>
