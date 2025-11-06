<?php

// Set UTF-8 encoding for the script
mb_internal_encoding('UTF-8');

// Set content-header with UTF-8 charset
header('Content-Type: application/json; charset=UTF-8');

// Include data and functions
include "data/flights.inc.php";
include "functions/flight_functions.php";

// Check for clean URL parameters in the path
$requestUri = $_SERVER['REQUEST_URI'];
$pathParts = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));

// Try to extract start and ziel from clean URL structure
// Format: /flights/json/[start]/[ziel]
// or: /flights/json/[start]/[ziel]/[date]/[time]
$start = isset($_GET['start']) ? $_GET['start'] : null;
$ziel = isset($_GET['ziel']) ? $_GET['ziel'] : null;
$date = isset($_GET['date']) ? $_GET['date'] : null;
$time = isset($_GET['time']) ? $_GET['time'] : null;

// If we have a clean URL structure, extract parameters from path
if (count($pathParts) >= 4 && strtolower($pathParts[count($pathParts) - 4]) === 'flights' && 
    strtolower($pathParts[count($pathParts) - 3]) === 'json') {
    $start = urldecode($pathParts[count($pathParts) - 2]);
    $ziel = urldecode($pathParts[count($pathParts) - 1]);
}

// If date and time are provided in the URL path
if (isset($_GET['date']) && isset($_GET['time'])) {
    $datetime = $date . ' ' . $time;
} else {
    $datetime = isset($_GET['datetime']) ? $_GET['datetime'] : null;
}

// check if parameters are set
if ($start === null || $ziel === null || $datetime === null) {
    echo json_encode(["Fehler" => "Parameter 'start', 'ziel' und 'datetime' (oder 'date'/'time') sind erforderlich."], 
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Validate the date
$timestamp = validateDatetime($datetime);

if ($timestamp === false) {
    echo json_encode(["Fehler" => "Ungültiges Datumsformat. Bitte geben Sie Datum und Uhrzeit an (z.B. 31.12.2023 14:30, 2023-12-31 14:30)."], 
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Find the requested flight
$flight = findFlight($flights, $start, $ziel);

if ($flight === false) {
    echo json_encode(["Fehler" => "Kein passender Flug von '$start' nach '$ziel' gefunden."], 
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Generate flight times and alternatives
$flightTimes = generateFlightTimes($flight, $timestamp);

// Prepare the final response
$response = prepareFlightResponse($flight, $flightTimes);

// Output the JSON response and exit
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;