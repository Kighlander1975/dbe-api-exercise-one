# Flight Information API

## Changelog

- **Version 1.4.0** (Current)
  - Added `/flights/help` endpoint for API self-documentation
  - Support for multiple output formats (HTML, JSON, Text) for help information
  - Added list of available cities to help endpoint

- **Version 1.3.1**
  - Added even cleaner URL format with date/time in path
  - Updated interface to allow selection of URL format
  - Enhanced documentation with new URL format examples

- **Version 1.3**
  - Added clean URL support via .htaccess
  - Updated interface to use RESTful URL structure
  - Enhanced documentation with new URL format examples

- **Version 1.2**
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
├── flights-txt.php           # Plain text API endpoint
└── flights-help.php          # API help documentation endpoint
index.php                     # User interface
styles.css                    # Styling for the user interface
.htaccess                     # URL rewriting rules
```

## API Endpoints

### 1. JSON Flight Information

**Legacy Endpoint:** `/api/flights-json.php`  
**Clean URL Endpoint:** `/flights/json/[start]/[ziel]`  
**Clean URL with Date:** `/flights/json/[start]/[ziel]/[date]/[time]`

**Parameters:**
- `start`: Departure city (e.g., "Berlin")
- `ziel`: Destination city (e.g., "London")
- `datetime`: Date and time in various formats (e.g., "15.11.2025 14:30" or "2025-11-15 14:30")
- Alternatively: `date` and `time` as separate parameters (e.g., "2025-11-15" and "14:30")

**Response Format:** JSON

### 2. Plain Text Flight Information

**Legacy Endpoint:** `/api/flights-txt.php`  
**Clean URL Endpoint:** `/flights/text/[start]/[ziel]`  
**Clean URL with Date:** `/flights/text/[start]/[ziel]/[date]/[time]`

**Parameters:**
- Same parameters as JSON endpoint

**Response Format:** Formatted plain text in German

### 3. API Help Documentation

**Endpoint:** `/flights/help`

**Parameters:**
- Optional: `format` - Output format (html, json, text)

**Response Format:** HTML (default), JSON, or plain text

## Features

- Case-insensitive city name matching
- Multiple date/time format support
- Flight alternatives (earlier and later options)
- Dynamic flight time calculation based on number of stops
- Price variations for alternative flights
- Multiple output formats (JSON and plain text)
- User-friendly interface with autocomplete
- Clean, RESTful URL structure with multiple format options
- Self-documenting API with help endpoint

## REST Principles Analysis

This API implements several key REST principles while leaving room for future improvements. Here's an analysis of how the API aligns with REST principles:

### Implemented REST Principles

1. **Client-Server Architecture** ✅
   - Clear separation between client (frontend) and server (backend)
   - Well-defined interfaces for communication

2. **Statelessness** ✅
   - Each request contains all necessary information
   - No session state is stored on the server
   - Requests can be processed independently
   - Identical requests yield identical responses

3. **Layered System** ✅
   - API could be deployed behind proxies, gateways, or load balancers
   - Client doesn't need to know if it's communicating directly with the end server

4. **Uniform Interface (Partial)** ⚠️
   - **Resource Identification**: Implemented through clean URLs
   - **Resource Manipulation through Representations**: Implemented for read operations

### Areas for REST Improvement

1. **Cacheability** ❌
   - No explicit cache control headers implemented
   - Could add `Cache-Control`, `ETag`, or `Last-Modified` headers
   - Support for conditional requests could be added

2. **HATEOAS (Hypermedia as the Engine of Application State)** ❌
   - API responses don't include links to related resources
   - Clients can't "navigate" the API through provided links

3. **Complete HTTP Method Usage** ❌
   - Currently only uses GET method
   - Could implement POST, PUT, DELETE for a complete RESTful API

4. **Content Negotiation** ⚠️
   - Format is specified in URL path rather than through Accept headers
   - Could implement proper content negotiation using HTTP headers

### REST Compliance Assessment

The API is "REST-like" or "REST-inspired" rather than fully REST-compliant. It implements the core principles of statelessness and client-server architecture while using clean, resource-oriented URLs. For many applications, this level of REST compliance is sufficient and practical.

Full REST compliance would require implementing HATEOAS, proper caching mechanisms, and more sophisticated content negotiation, which could be considered for future versions.

## Code Explanation

- `flights.inc.php`: Contains the flight data array with routes, prices, and other details
- `flight_functions.php`: Contains shared functions for:
  - Date/time validation and parsing
  - Flight search
  - Flight time calculation
  - Response formatting
- `flights-json.php`: API endpoint that processes requests and returns JSON responses
- `flights-txt.php`: API endpoint that processes requests and returns formatted text responses
- `flights-help.php`: API help documentation endpoint with multiple output formats
- `index.php`: User interface with form and autocomplete functionality
- `styles.css`: Styling for the user interface
- `.htaccess`: URL rewriting rules for clean URLs

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

### API Help

- <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/help" target="_blank">API Help (HTML)</a>  
  `localhost/DBE-exercises/dbe-api-exercises/flights/help`

- <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/help?format=json" target="_blank">API Help (JSON)</a>  
  `localhost/DBE-exercises/dbe-api-exercises/flights/help?format=json`

- <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/help?format=text" target="_blank">API Help (Text)</a>  
  `localhost/DBE-exercises/dbe-api-exercises/flights/help?format=text`

### Clean URL Format Examples

#### With Date/Time in Path (Most Readable)

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/json/Berlin/London/2025-11-15/14:30" target="_blank">Berlin to London (Date in Path)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/flights/json/Berlin/London/2025-11-15/14:30`

2. Berlin to Paris as text format:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/text/Berlin/Paris/2025-12-20/08:15" target="_blank">Berlin to Paris (Text, Date in Path)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/flights/text/Berlin/Paris/2025-12-20/08:15`

#### With Date/Time as Query Parameter

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/json/Berlin/London?datetime=15.11.2025%2014:30" target="_blank">Berlin to London (Query Parameter)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/flights/json/Berlin/London?datetime=15.11.2025 14:30`

2. Berlin to Paris on December 20, 2025 at 8:15 AM (ISO format):  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/flights/json/Berlin/Paris?datetime=2025-12-20%2008:15" target="_blank">Berlin to Paris (Query Parameter, ISO format)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/flights/json/Berlin/Paris?datetime=2025-12-20 08:15`

### Legacy Format Examples (Still Supported)

#### JSON Format

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025%2014:30" target="_blank">Berlin to London (Legacy)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025 14:30`

#### Text Format

1. Berlin to London on November 15, 2025 at 2:30 PM:  
   <a href="http://localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=London&datetime=15.11.2025%2014:30" target="_blank">Berlin to London (Legacy, Text)</a>  
   `localhost/DBE-exercises/dbe-api-exercises/api/flights-txt.php?start=Berlin&ziel=London&datetime=15.11.2025 14:30`

## URL Structure Explanation

The system now supports three different URL formats:

1. **Most Readable Format** (introduced in v1.3.1):  
   `/flights/[format]/[start]/[ziel]/[date]/[time]`  
   Example: `/flights/json/Berlin/London/2025-11-15/14:30`

2. **Clean URL with Query Parameter** (introduced in v1.3):  
   `/flights/[format]/[start]/[ziel]?datetime=[date time]`  
   Example: `/flights/json/Berlin/London?datetime=15.11.2025 14:30`

3. **Legacy Format** (original):  
   `/api/flights-[format].php?start=[start]&ziel=[ziel]&datetime=[date time]`  
   Example: `/api/flights-json.php?start=Berlin&ziel=London&datetime=15.11.2025 14:30`

## User Interface

The project includes a user-friendly interface (`index.php`) that allows users to:

- Enter departure and destination cities with autocomplete suggestions
- Select date and time for the flight
- Choose between JSON and text output formats
- Select preferred URL format (clean URL or clean URL with date in path)
- Submit the search and view results in a new tab

## Self-Documentation

The API now includes a self-documentation feature via the `/flights/help` endpoint:

- **HTML format** (default): Provides a user-friendly web interface with all API details
- **JSON format**: Machine-readable API documentation for automated tools
- **Text format**: Simple text documentation for command-line usage

This makes the API more discoverable and easier to use for developers.

## Future Development

The API is designed with a modular structure to allow for easy extension. Future planned features include:
- More advanced search options
- Booking functionality
- Support for more departure cities
- Additional output formats
- Enhanced RESTful API features including:
  - HATEOAS implementation
  - Proper caching mechanisms
  - Full HTTP method support
  - Content negotiation via HTTP headers
- API versioning and authentication

## Credits

- Exercise developed as part of the [DBE Academy](https://www.dbe.academy/) curriculum
- Implementation by [Kai Akkermann](https://kighlander.de/)
- Project documentation: [GitHub Repository](https://github.com/Kighlander1975/dbe-api-exercise-one)