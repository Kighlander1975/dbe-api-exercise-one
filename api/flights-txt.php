<?php

// Set UTF-8 encoding for the script
mb_internal_encoding('UTF-8');

// Set content-header with UTF-8 charset for plain text
header('Content-Type: text/plain; charset=UTF-8');

// Include data and functions
include "data/flights.inc.php";
include "functions/flight_functions.php";

// Check for clean URL parameters in the path
$requestUri = $_SERVER['REQUEST_URI'];
$pathParts = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));

// Try to extract start and ziel from clean URL structure
// Format: /flights/text/[start]/[ziel]
// or: /flights/text/[start]/[ziel]/[date]/[time]
$start = isset($_GET['start']) ? $_GET['start'] : null;
$ziel = isset($_GET['ziel']) ? $_GET['ziel'] : null;
$date = isset($_GET['date']) ? $_GET['date'] : null;
$time = isset($_GET['time']) ? $_GET['time'] : null;

// If we have a clean URL structure, extract parameters from path
if (count($pathParts) >= 4 && strtolower($pathParts[count($pathParts) - 4]) === 'flights' && 
    strtolower($pathParts[count($pathParts) - 3]) === 'text') {
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
    echo "Fehler: Parameter 'start', 'ziel' und 'datetime' (oder 'date'/'time') sind erforderlich.";
    exit;
}

// Validate the date
$timestamp = validateDatetime($datetime);

if ($timestamp === false) {
    echo "Fehler: Ungültiges Datumsformat. Bitte geben Sie Datum und Uhrzeit an (z.B. 31.12.2023 14:30, 2023-12-31 14:30).";
    exit;
}

// Find the requested flight
$flight = findFlight($flights, $start, $ziel);

if ($flight === false) {
    echo "Fehler: Kein passender Flug von '$start' nach '$ziel' gefunden.";
    exit;
}

// Generate flight times and alternatives
$flightTimes = generateFlightTimes($flight, $timestamp);

// Format the flight information as text
function formatFlightAsText($flight, $flightTimes) {
    // Helper function to format prices
    $formatPrice = function($price) {
        return number_format($price, 2, ',', '.') . ' €';
    };
    
    // Main flight information
    $text = "FLUGDETAILS\n";
    $text .= "==========\n\n";
    $text .= "Flug von {$flight['start']} nach {$flight['ziel']}\n";
    $text .= "Abflug:    {$flightTimes['main']['departure']} Uhr, Terminal {$flight['terminal']}\n";
    $text .= "Ankunft:   {$flightTimes['main']['arrival']} Uhr\n";
    $text .= "Dauer:     {$flight['flugdauer']}\n";
    $text .= "Stops:     " . ($flight['stops'] == 0 ? "Direktflug" : "{$flight['stops']} Zwischenstopp" . ($flight['stops'] > 1 ? "s" : "")) . "\n";
    $text .= "Preise:    Business Class: " . $formatPrice($flightTimes['main']['prices']['business']) . "\n";
    $text .= "           Economy Class:  " . $formatPrice($flightTimes['main']['prices']['economy']) . "\n\n";
    
    // Alternative flights
    $text .= "ALTERNATIVE FLÜGE\n";
    $text .= "=================\n\n";
    
    // Earlier flight
    $text .= "Früherer Flug:\n";
    $text .= "  Abflug:  {$flightTimes['alternatives']['earlier']['departure']} Uhr\n";
    $text .= "  Ankunft: {$flightTimes['alternatives']['earlier']['arrival']} Uhr\n";
    $text .= "  Preise:  Business Class: " . $formatPrice($flightTimes['alternatives']['earlier']['prices']['business']) . "\n";
    $text .= "           Economy Class:  " . $formatPrice($flightTimes['alternatives']['earlier']['prices']['economy']) . "\n\n";
    
    // Later flight
    $text .= "Späterer Flug:\n";
    $text .= "  Abflug:  {$flightTimes['alternatives']['later']['departure']} Uhr\n";
    $text .= "  Ankunft: {$flightTimes['alternatives']['later']['arrival']} Uhr\n";
    $text .= "  Preise:  Business Class: " . $formatPrice($flightTimes['alternatives']['later']['prices']['business']) . "\n";
    $text .= "           Economy Class:  " . $formatPrice($flightTimes['alternatives']['later']['prices']['economy']) . "\n\n";
    
    // Booking information
    $text .= "BUCHUNGSINFORMATIONEN\n";
    $text .= "====================\n\n";
    $text .= "Um diesen Flug zu buchen, wenden Sie sich bitte an unser Servicecenter\n";
    $text .= "oder besuchen Sie unsere Website für eine Online-Buchung.\n\n";
    $text .= "Vielen Dank, dass Sie sich für unseren Service interessieren!\n";
    
    return $text;
}

// Output the text response and exit
echo formatFlightAsText($flight, $flightTimes);
exit;