<?php
// Set UTF-8 encoding for the script
mb_internal_encoding('UTF-8');

// Determine the requested format based on the query parameter or Accept header
$format = isset($_GET['format']) ? strtolower($_GET['format']) : '';

// If no format parameter, check Accept header
if (empty($format)) {
    $acceptHeader = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
    if (strpos($acceptHeader, 'application/json') !== false) {
        $format = 'json';
    } elseif (strpos($acceptHeader, 'text/plain') !== false) {
        $format = 'text';
    } else {
        $format = 'html'; // Default format
    }
}

// Set appropriate Content-Type header
switch ($format) {
    case 'json':
        header('Content-Type: application/json; charset=UTF-8');
        break;
    case 'text':
        header('Content-Type: text/plain; charset=UTF-8');
        break;
    default:
        header('Content-Type: text/html; charset=UTF-8');
        $format = 'html';
        break;
}

// Base URL for examples
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$basePath = dirname($_SERVER['REQUEST_URI']);
if (substr($basePath, -5) === '/help') {
    $basePath = dirname($basePath);
}
$baseUrl .= $basePath;

// API endpoint information
$endpoints = [
    [
        'path' => '/flights/json/{start}/{ziel}/{date}/{time}',
        'method' => 'GET',
        'description' => 'Get flight information in JSON format with date in URL path',
        'parameters' => [
            'start' => 'Departure city (e.g., Berlin)',
            'ziel' => 'Destination city (e.g., London)',
            'date' => 'Flight date in YYYY-MM-DD format',
            'time' => 'Flight time in HH:MM format'
        ],
        'example' => "$baseUrl/json/Berlin/London/2025-11-15/14:30"
    ],
    [
        'path' => '/flights/json/{start}/{ziel}?datetime={datetime}',
        'method' => 'GET',
        'description' => 'Get flight information in JSON format with datetime as query parameter',
        'parameters' => [
            'start' => 'Departure city (e.g., Berlin)',
            'ziel' => 'Destination city (e.g., London)',
            'datetime' => 'Flight date and time (e.g., "15.11.2025 14:30" or "2025-11-15 14:30")'
        ],
        'example' => "$baseUrl/json/Berlin/London?datetime=2025-11-15%2014:30"
    ],
    [
        'path' => '/flights/text/{start}/{ziel}/{date}/{time}',
        'method' => 'GET',
        'description' => 'Get flight information in plain text format with date in URL path',
        'parameters' => [
            'start' => 'Departure city (e.g., Berlin)',
            'ziel' => 'Destination city (e.g., London)',
            'date' => 'Flight date in YYYY-MM-DD format',
            'time' => 'Flight time in HH:MM format'
        ],
        'example' => "$baseUrl/text/Berlin/London/2025-11-15/14:30"
    ],
    [
        'path' => '/flights/text/{start}/{ziel}?datetime={datetime}',
        'method' => 'GET',
        'description' => 'Get flight information in plain text format with datetime as query parameter',
        'parameters' => [
            'start' => 'Departure city (e.g., Berlin)',
            'ziel' => 'Destination city (e.g., London)',
            'datetime' => 'Flight date and time (e.g., "15.11.2025 14:30" or "2025-11-15 14:30")'
        ],
        'example' => "$baseUrl/text/Berlin/London?datetime=2025-11-15%2014:30"
    ],
    [
        'path' => '/flights/help',
        'method' => 'GET',
        'description' => 'Get API help information in various formats',
        'parameters' => [
            'format' => 'Optional: Output format (html, json, text)'
        ],
        'example' => "$baseUrl/help?format=json"
    ]
];

// Available cities from the database
include "data/flights.inc.php";
$cities = [];
foreach ($flights as $flight) {
    if (!in_array($flight['start'], $cities)) {
        $cities[] = $flight['start'];
    }
    if (!in_array($flight['ziel'], $cities)) {
        $cities[] = $flight['ziel'];
    }
}
sort($cities);

// API version information
$apiInfo = [
    'name' => 'Flight Information API',
    'version' => '1.4.0',
    'description' => 'API for retrieving flight information between cities',
    'availableCities' => $cities,
    'endpoints' => $endpoints
];

// Output the API information in the requested format
switch ($format) {
    case 'json':
        echo json_encode($apiInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        break;
        
    case 'text':
        echo "Flight Information API v1.4.0\n";
        echo "=============================\n\n";
        echo "Description: API for retrieving flight information between cities\n\n";
        
        echo "AVAILABLE CITIES\n";
        echo "----------------\n";
        foreach ($cities as $city) {
            echo "- $city\n";
        }
        echo "\n";
        
        echo "API ENDPOINTS\n";
        echo "------------\n\n";
        
        foreach ($endpoints as $endpoint) {
            echo "{$endpoint['method']} {$endpoint['path']}\n";
            echo "  {$endpoint['description']}\n\n";
            
            if (!empty($endpoint['parameters'])) {
                echo "  Parameters:\n";
                foreach ($endpoint['parameters'] as $param => $desc) {
                    echo "    - $param: $desc\n";
                }
                echo "\n";
            }
            
            echo "  Example: {$endpoint['example']}\n\n";
        }
        
        echo "For more detailed documentation, please visit the API documentation page.\n";
        break;
        
    default:
        // HTML format
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Information API - Help</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #3498db;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        h2 {
            color: #2980b9;
            margin-top: 30px;
        }
        .version {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 5px 10px;
            font-size: 0.9em;
            color: #666;
        }
        .endpoint {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }
        .method {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        .path {
            font-family: monospace;
            font-size: 1.1em;
            margin-left: 10px;
        }
        .description {
            margin: 10px 0;
        }
        .parameters {
            margin-top: 10px;
        }
        .parameters h4 {
            margin-bottom: 5px;
        }
        .parameters table {
            border-collapse: collapse;
            width: 100%;
        }
        .parameters th, .parameters td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .parameters th {
            background-color: #f2f2f2;
        }
        .example {
            margin-top: 10px;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            overflow-x: auto;
        }
        .example a {
            color: #3498db;
            text-decoration: none;
        }
        .example a:hover {
            text-decoration: underline;
        }
        .cities {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .city {
            background-color: #e8f4f8;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.9em;
        }
        .formats {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .formats a {
            margin-right: 15px;
            color: #3498db;
            text-decoration: none;
        }
        .formats a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Flight Information API <span class="version">v<?php echo $apiInfo['version']; ?></span></h1>
    <p><?php echo $apiInfo['description']; ?></p>
    
    <h2>Available Cities</h2>
    <div class="cities">
        <?php foreach ($cities as $city): ?>
            <div class="city"><?php echo htmlspecialchars($city); ?></div>
        <?php endforeach; ?>
    </div>
    
    <h2>API Endpoints</h2>
    <?php foreach ($endpoints as $endpoint): ?>
        <div class="endpoint">
            <div>
                <span class="method"><?php echo htmlspecialchars($endpoint['method']); ?></span>
                <span class="path"><?php echo htmlspecialchars($endpoint['path']); ?></span>
            </div>
            <div class="description"><?php echo htmlspecialchars($endpoint['description']); ?></div>
            
            <?php if (!empty($endpoint['parameters'])): ?>
                <div class="parameters">
                    <h4>Parameters</h4>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                        <?php foreach ($endpoint['parameters'] as $param => $desc): ?>
                            <tr>
                                <td><code><?php echo htmlspecialchars($param); ?></code></td>
                                <td><?php echo htmlspecialchars($desc); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="example">
                Example: <a href="<?php echo htmlspecialchars($endpoint['example']); ?>" target="_blank"><?php echo htmlspecialchars($endpoint['example']); ?></a>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="formats">
        <strong>View this help in other formats:</strong>
        <a href="<?php echo $baseUrl; ?>/help?format=json" target="_blank">JSON</a>
        <a href="<?php echo $baseUrl; ?>/help?format=text" target="_blank">Plain Text</a>
    </div>
</body>
</html>
        <?php
        break;
}