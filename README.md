# Flight Information API

A simple PHP-based API that provides flight information based on departure city, destination city, and requested date/time.

## Project Structure

```
api/
├── data/
│   └── flights.inc.php       # Flight data array
├── functions/
│   └── flight_functions.php  # Shared functions for flight processing
└── flights-json.php          # JSON API endpoint
```

## API Endpoints

### 1. JSON Flight Information

**Endpoint:** `/api/flights-json.php`

**Parameters:**
- `start`: Departure city (e.g., "Berlin")
- `ziel`: Destination city (e.g., "London")
- `datetime`: Date and time in various formats (e.g., "15.11.2025 14:30" or "2025-11-15 14:30")

**Response Format:** JSON

## Features

- Case-insensitive city name matching
- Multiple date/time format support
- Flight alternatives (earlier and later options)
- Dynamic flight time calculation based on number of stops
- Price variations for alternative flights

## Code Explanation

- `flights.inc.php`: Contains the flight data array with routes, prices, and other details
- `flight_functions.php`: Contains shared functions for:
  - Date/time validation and parsing
  - Flight search
  - Flight time calculation
  - Response formatting
- `flights-json.php`: Main API endpoint that processes requests and returns JSON responses

## Available Flights

Currently, only **Berlin** is available as a departure city. Additional flight routes from other cities can be added to the `flights.inc.php` file following the existing data structure:

```php
[
    "start"     => "CityName",
    "ziel"      => "Destination",
    "stops"     => 0,  // Number of stops
    "flugdauer" => "1h 30m",  // Duration format
    "preis"     => [
        "business"  => 350,  // Business class price
        "economy"   => 150   // Economy class price
    ],
    "terminal"  => "T1"  // Terminal information
]
```

## Test Links

### Valid Examples

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   [Berlin to London](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025%2014:30)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025 14:30`

2. Berlin to Paris on December 20, 2025 at 8:15 AM (ISO format):  
   [Berlin to Paris (ISO format)](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Paris&datetime=2025-12-20%2008:15)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Paris&datetime=2025-12-20 08:15`

3. Berlin to Rome on January 1, 2026 at 10:45 AM with seconds:  
   [Berlin to Rome (with seconds)](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Rom&datetime=01.01.2026%2010:45:00)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Rom&datetime=01.01.2026 10:45:00`

4. Berlin to Vienna on February 5, 2026 at 4:20 PM (with T-separator):  
   [Berlin to Vienna (T-separator)](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Wien&datetime=2026-02-05T16:20)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Wien&datetime=2026-02-05T16:20`

5. Case-insensitive search for Berlin to Prague:  
   [Berlin to Prague (case-insensitive)](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=berlin&ziel=prag&datetime=10.03.2026%2009:30)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=berlin&ziel=prag&datetime=10.03.2026 09:30`

### Invalid Examples

1. Missing parameter (no destination):  
   [Missing destination parameter](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&datetime=15.11.2025%2014:30)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&datetime=15.11.2025 14:30`

2. Invalid date format (no time):  
   [Invalid date format](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025`

3. Non-existent flight route:  
   [Non-existent route](http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=NewYork&datetime=15.11.2025%2014:30)  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=NewYork&datetime=15.11.2025 14:30`

## Future Development

The API is designed with a modular structure to allow for easy extension. Future planned features include:
- Additional output formats beyond JSON
- More advanced search options
- Booking functionality
- Support for more departure cities