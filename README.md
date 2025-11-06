# Flight Information API

## Changelog

- **Version 1.2** (Current)
  - Added user-friendly interface with autocomplete functionality
  - Added CSS styling for the interface
  - Updated documentation with complete examples and credits

- **Version 1.1**
  - Added plain text output format (flights-txt.php)
  - Implemented proper UTF-8 support for special characters
  - Enhanced error handling with descriptive messages

- **Version 1.0**
  - Initial release with JSON output (flights-json.php)
  - Implemented flight search functionality
  - Added support for multiple date/time formats
  - Created flight alternatives generation

## Project Description

This project was developed as part of the [DBE Academy](https://www.dbe.academy/) API development exercises. It demonstrates the implementation of a RESTful API with multiple output formats and a user-friendly frontend.

## Project Structure

```
api/
├── data/
│   └── flights.inc.php       # Flight data array
├── functions/
│   └── flight_functions.php  # Shared functions for flight processing
├── flights-json.php          # JSON API endpoint
└── flights-txt.php           # Plain text API endpoint
index.php                     # User interface
styles.css                    # Styling for the user interface
```

## API Endpoints

### 1. JSON Flight Information

**Endpoint:** `/api/flights-json.php`

**Parameters:**
- `start`: Departure city (e.g., "Berlin")
- `ziel`: Destination city (e.g., "London")
- `datetime`: Date and time in various formats (e.g., "15.11.2025 14:30" or "2025-11-15 14:30")

**Response Format:** JSON

### 2. Plain Text Flight Information

**Endpoint:** `/api/flights-txt.php`

**Parameters:**
- Same parameters as JSON endpoint: `start`, `ziel`, `datetime`

**Response Format:** Formatted plain text in German

## Features

- Case-insensitive city name matching
- Multiple date/time format support
- Flight alternatives (earlier and later options)
- Dynamic flight time calculation based on number of stops
- Price variations for alternative flights
- Multiple output formats (JSON and plain text)
- User-friendly interface with autocomplete

## Code Explanation

- `flights.inc.php`: Contains the flight data array with routes, prices, and other details
- `flight_functions.php`: Contains shared functions for:
  - Date/time validation and parsing
  - Flight search
  - Flight time calculation
  - Response formatting
- `flights-json.php`: API endpoint that processes requests and returns JSON responses
- `flights-txt.php`: API endpoint that processes requests and returns formatted text responses
- `index.php`: User interface with form and autocomplete functionality
- `styles.css`: Styling for the user interface

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

### JSON Format Examples

#### Valid Examples

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025%2014:30" target="_blank">Berlin to London</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025 14:30`

2. Berlin to Paris on December 20, 2025 at 8:15 AM (ISO format):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Paris&datetime=2025-12-20%2008:15" target="_blank">Berlin to Paris (ISO format)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Paris&datetime=2025-12-20 08:15`

3. Berlin to Rome on January 1, 2026 at 10:45 AM with seconds:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Rom&datetime=01.01.2026%2010:45:00" target="_blank">Berlin to Rome (with seconds)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Rom&datetime=01.01.2026 10:45:00`

4. Berlin to Vienna on February 5, 2026 at 4:20 PM (with T-separator):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Wien&datetime=2026-02-05T16:20" target="_blank">Berlin to Vienna (T-separator)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=Wien&datetime=2026-02-05T16:20`

5. Case-insensitive search for Berlin to Prague:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=berlin&ziel=prag&datetime=10.03.2026%2009:30" target="_blank">Berlin to Prague (case-insensitive)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=berlin&ziel=prag&datetime=10.03.2026 09:30`

#### Invalid Examples

1. Missing parameter (no destination):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&datetime=15.11.2025%2014:30" target="_blank">Missing destination parameter</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&datetime=15.11.2025 14:30`

2. Invalid date format (no time):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025" target="_blank">Invalid date format</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025`

3. Non-existent flight route:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=NewYork&datetime=15.11.2025%2014:30" target="_blank">Non-existent route</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=NewYork&datetime=15.11.2025 14:30`

### Plain Text Format Examples

#### Valid Examples

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=London&datetime=15.11.2025%2014:30" target="_blank">Berlin to London (Text)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=London&datetime=15.11.2025 14:30`

2. Berlin to Paris on December 20, 2025 at 8:15 AM (ISO format):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=Paris&datetime=2025-12-20%2008:15" target="_blank">Berlin to Paris (Text, ISO format)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=Paris&datetime=2025-12-20 08:15`

3. Berlin to Rome on January 1, 2026 at 10:45 AM with seconds:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=Rom&datetime=01.01.2026%2010:45:00" target="_blank">Berlin to Rome (Text, with seconds)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=Rom&datetime=01.01.2026 10:45:00`

4. Berlin to Vienna on February 5, 2026 at 4:20 PM (with T-separator):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=Wien&datetime=2026-02-05T16:20" target="_blank">Berlin to Vienna (Text, T-separator)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=Wien&datetime=2026-02-05T16:20`

5. Case-insensitive search for Berlin to Prague:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=berlin&ziel=prag&datetime=10.03.2026%2009:30" target="_blank">Berlin to Prague (Text, case-insensitive)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=berlin&ziel=prag&datetime=10.03.2026 09:30`

#### Invalid Examples

1. Missing parameter (no destination):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&datetime=15.11.2025%2014:30" target="_blank">Missing destination parameter (Text)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&datetime=15.11.2025 14:30`

2. Invalid date format (no time):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=London&datetime=15.11.2025" target="_blank">Invalid date format (Text)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=London&datetime=15.11.2025`

3. Non-existent flight route:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=NewYork&datetime=15.11.2025%2014:30" target="_blank">Non-existent route (Text)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=NewYork&datetime=15.11.2025 14:30`

## User Interface

The project includes a user-friendly interface (`index.php`) that allows users to:

- Enter departure and destination cities with autocomplete suggestions
- Select date and time for the flight
- Choose between JSON and text output formats
- Submit the search and view results in a new tab

## Future Development

The API is designed with a modular structure to allow for easy extension. Future planned features include:
- More advanced search options
- Booking functionality
- Support for more departure cities
- Additional output formats

## Credits

- Exercise developed as part of the [DBE Academy](https://www.dbe.academy/) curriculum
- Implementation by [Kai Akkermann](https://kighlander.de/)
- Project documentation: [GitHub Repository](https://github.com/Kighlander1975/dbe-api-exercise-one)