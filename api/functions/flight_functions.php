<?php
/**
 * Flight Functions Library
 * Contains all functions for flight data processing and validation
 */

/**
 * Validates the provided datetime format and converts it to a timestamp
 * Time component (HH:MM) is required, seconds are optional
 * 
 * @param string $datetime The datetime to validate
 * @return int|false Timestamp on success, false on invalid format
 */
function validateDatetime($datetime) {
    // Array of possible date formats - all include time (seconds optional)
    $formats = [
        // Date formats with time (hours and minutes)
        'd.m.Y H:i',   // 01.01.2023 14:30
        'd.m.y H:i',   // 01.01.23 14:30
        'Y-m-d H:i',   // 2023-01-01 14:30
        'Y/m/d H:i',   // 2023/01/01 14:30
        'm/d/Y H:i',   // 01/01/2023 14:30
        'd-m-Y H:i',   // 01-01-2023 14:30
        'Y-m-d\TH:i',  // 2023-01-01T14:30
        
        // Date formats with time including seconds
        'd.m.Y H:i:s', // 01.01.2023 14:30:00
        'd.m.y H:i:s', // 01.01.23 14:30:00
        'Y-m-d H:i:s', // 2023-01-01 14:30:00
        'Y/m/d H:i:s', // 2023/01/01 14:30:00
        'm/d/Y H:i:s', // 01/01/2023 14:30:00
        'd-m-Y H:i:s', // 01-01-2023 14:30:00
        'Y-m-d\TH:i:s' // 2023-01-01T14:30:00
    ];
    
    $timestamp = false;
    
    // Try each format
    foreach ($formats as $format) {
        $date = DateTime::createFromFormat($format, $datetime);
        if ($date !== false && $date->format($format) === $datetime) {
            $timestamp = $date->getTimestamp();
            break;
        }
    }
    
    return $timestamp;
}

/**
 * Finds a flight in the flights array based on start and destination
 * Case-insensitive comparison is used to handle different writing styles
 * 
 * @param array $flights The array of flights to search in
 * @param string $start The departure city
 * @param string $ziel The destination city
 * @return array|false The found flight or false if no match
 */
function findFlight($flights, $start, $ziel) {
    // Convert search parameters to lowercase for case-insensitive comparison
    $startLower = strtolower($start);
    $zielLower = strtolower($ziel);
    
    foreach ($flights as $flight) {
        // Convert flight data to lowercase and compare
        if (strtolower($flight['start']) === $startLower && 
            strtolower($flight['ziel']) === $zielLower) {
            return $flight;
        }
    }
    
    return false;
}

/**
 * Parses flight duration string in format "Xh Ym" to minutes
 * 
 * @param string $duration Duration string in format "Xh Ym"
 * @return int Total minutes
 */
function parseDuration($duration) {
    preg_match('/(\d+)h\s+(\d+)m/', $duration, $matches);
    $hours = (int)$matches[1];
    $minutes = (int)$matches[2];
    return ($hours * 60) + $minutes;
}

/**
 * Generates flight times based on stops and requested datetime
 * 
 * @param array $flight Flight data
 * @param int $timestamp Requested datetime timestamp
 * @return array Flight times information
 */
function generateFlightTimes($flight, $timestamp) {
    // Create DateTime object from timestamp
    $requestedDate = new DateTime();
    $requestedDate->setTimestamp($timestamp);
    
    // Determine flight frequency based on stops
    $frequency = 0;
    switch ($flight['stops']) {
        case 0:
            $frequency = 3 * 60; // 3 hours in minutes
            break;
        case 1:
            $frequency = 4 * 60; // 4 hours in minutes
            break;
        case 2:
            $frequency = 6 * 60; // 6 hours in minutes
            break;
    }
    
    // Find the next flight time after the requested time
    // Start with 6:00 as the first flight of the day
    $flightTime = new DateTime($requestedDate->format('Y-m-d') . ' 06:00:00');
    
    // Add random minutes (in 5-minute increments) to the base hour
    $randomMinutes = rand(0, 11) * 5; // 0, 5, 10, 15, ..., 55
    $flightTime->modify("+$randomMinutes minutes");
    
    // Find the next available flight after the requested time
    while ($flightTime < $requestedDate) {
        $flightTime->modify("+$frequency minutes");
    }
    
    // Parse flight duration
    $durationMinutes = parseDuration($flight['flugdauer']);
    
    // Calculate arrival time
    $arrivalTime = clone $flightTime;
    $arrivalTime->modify("+$durationMinutes minutes");
    
    // Generate alternative flights
    $earlierFlight = clone $flightTime;
    $earlierFlight->modify("-$frequency minutes");
    
    $laterFlight = clone $flightTime;
    $laterFlight->modify("+$frequency minutes");
    
    $earlierArrival = clone $earlierFlight;
    $earlierArrival->modify("+$durationMinutes minutes");
    
    $laterArrival = clone $laterFlight;
    $laterArrival->modify("+$durationMinutes minutes");
    
    // Generate price variations for alternatives (1:20 chance of different prices)
    $mainPrices = $flight['preis'];
    $earlierPrices = $mainPrices;
    $laterPrices = $mainPrices;
    
    // 1:20 chance for price variation
    if (rand(1, 20) == 1) {
        $variation = (rand(0, 1) == 0) ? 0.9 : 1.1; // 10% cheaper or more expensive
        $earlierPrices = [
            'business' => round($mainPrices['business'] * $variation, 2),
            'economy' => round($mainPrices['economy'] * $variation, 2)
        ];
    }
    
    if (rand(1, 20) == 1) {
        $variation = (rand(0, 1) == 0) ? 0.9 : 1.1; // 10% cheaper or more expensive
        $laterPrices = [
            'business' => round($mainPrices['business'] * $variation, 2),
            'economy' => round($mainPrices['economy'] * $variation, 2)
        ];
    }
    
    return [
        'main' => [
            'departure' => $flightTime->format('d.m.Y H:i'),
            'arrival' => $arrivalTime->format('d.m.Y H:i'),
            'prices' => $mainPrices
        ],
        'alternatives' => [
            'earlier' => [
                'departure' => $earlierFlight->format('d.m.Y H:i'),
                'arrival' => $earlierArrival->format('d.m.Y H:i'),
                'prices' => $earlierPrices
            ],
            'later' => [
                'departure' => $laterFlight->format('d.m.Y H:i'),
                'arrival' => $laterArrival->format('d.m.Y H:i'),
                'prices' => $laterPrices
            ]
        ]
    ];
}

/**
 * Prepares the final flight response
 * 
 * @param array $flight Flight data
 * @param array $flightTimes Generated flight times
 * @return array Complete flight response
 */
function prepareFlightResponse($flight, $flightTimes) {
    return [
        'flight' => [
            'from' => $flight['start'],
            'to' => $flight['ziel'],
            'stops' => $flight['stops'],
            'duration' => $flight['flugdauer'],
            'terminal' => $flight['terminal'],
            'departure' => $flightTimes['main']['departure'],
            'arrival' => $flightTimes['main']['arrival'],
            'prices' => [
                'business' => $flightTimes['main']['prices']['business'],
                'economy' => $flightTimes['main']['prices']['economy']
            ]
        ],
        'alternatives' => [
            'earlier' => [
                'departure' => $flightTimes['alternatives']['earlier']['departure'],
                'arrival' => $flightTimes['alternatives']['earlier']['arrival'],
                'prices' => [
                    'business' => $flightTimes['alternatives']['earlier']['prices']['business'],
                    'economy' => $flightTimes['alternatives']['earlier']['prices']['economy']
                ]
            ],
            'later' => [
                'departure' => $flightTimes['alternatives']['later']['departure'],
                'arrival' => $flightTimes['alternatives']['later']['arrival'],
                'prices' => [
                    'business' => $flightTimes['alternatives']['later']['prices']['business'],
                    'economy' => $flightTimes['alternatives']['later']['prices']['economy']
                ]
            ]
        ]
    ];
}