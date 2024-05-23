<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$log_file = 'chatbot.log';

function log_message($message) {
    global $log_file;
    file_put_contents($log_file, $message . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    log_message("Received data: " . json_encode($data));

    $location = $data['What is your location?'];
    $cuisine = $data['What type of cuisine are you in the mood for?'];
    $diet = $data['Are you looking for vegetarian or non-vegetarian options?'];

    log_message("Location: $location, Cuisine: $cuisine, Diet: $diet");

    // Google Maps Places API URL
    $apiKey = 'AIzaSyCCZydLThH1j7AcsncDfttbcf4B0pyxL1I';
    $query = urlencode($cuisine . ' restaurants in ' . $location);
    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=$apiKey";

    log_message("Fetching URL: $url");

    // Fetch data from Google Maps API
    $response = file_get_contents($url);
    if ($response === FALSE) {
        log_message("Error fetching data from API");
        echo json_encode(['error' => 'Error fetching data from API']);
        exit;
    }

    $results = json_decode($response, true);
    log_message("API Response: " . json_encode($results));

    $recommendations = [];

    if ($results && isset($results['results'])) {
        foreach ($results['results'] as $result) {
            $types = $result['types'];
            $isVegetarian = in_array('vegetarian', $types);
            if (($diet === 'vegetarian' && $isVegetarian) || ($diet !== 'vegetarian' && !$isVegetarian)) {
                $recommendations[] = [
                    'name' => $result['name'],
                    'address' => $result['formatted_address']
                ];
            }
        }
    }

    log_message("Recommendations: " . json_encode($recommendations));

    echo json_encode(['recommendations' => $recommendations]);
}
