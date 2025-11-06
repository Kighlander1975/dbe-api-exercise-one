<?php

// Set UTF-8 encoding for the script
mb_internal_encoding('UTF-8');

// Set content-header with UTF-8 charset
header('Content-Type: application/json; charset=UTF-8');

// Include data and functions
include "data/flights.inc.php";
include "functions/flight_functions.php";

$start = isset($_GET['start']) ? $_GET['start'] : null;
$ziel = isset($_GET['ziel']) ? $_GET['ziel'] : null;
$datetime = isset($_GET['datetime']) ? $_GET['datetime'] : null;

// check if parameters are set
if ($start === null || $ziel === null || $datetime === null) {
    echo json_encode(["Fehler" => "Parameter 'start', 'ziel' und 'datetime' sind erforderlich."], 
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