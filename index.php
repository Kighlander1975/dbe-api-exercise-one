<?php
// Include flight data to get available cities for autocomplete
include "api/data/flights.inc.php";

// Extract unique cities for autocomplete
function getUniqueCities($flights) {
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
    return $cities;
}

$cities = getUniqueCities($flights);
$citiesJson = json_encode($cities);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flug-Informationssystem</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Flug-Informationssystem</h1>
            <p class="description">Finden Sie Flugverbindungen und Preise für Ihre Reise</p>
        </header>

        <form id="flightForm" action="javascript:void(0);" onsubmit="submitForm()">
            <div class="form-group">
                <label for="start">Abflugort:</label>
                <div class="autocomplete-container">
                    <input type="text" id="start" name="start" placeholder="z.B. Berlin" required autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label for="ziel">Zielort:</label>
                <div class="autocomplete-container">
                    <input type="text" id="ziel" name="ziel" placeholder="z.B. London" required autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label for="datetime">Datum und Uhrzeit:</label>
                <input type="datetime-local" id="datetime" name="datetime" required>
            </div>

            <div class="form-group">
                <label>Ausgabeformat:</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="format" value="json" checked> JSON-Format
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="format" value="text"> Text-Format
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>URL-Format:</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="urlFormat" value="clean" checked> Saubere URL
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="urlFormat" value="cleanWithDate"> Saubere URL mit Datum im Pfad
                    </label>
                </div>
            </div>

            <button type="submit" class="btn">Flug suchen</button>
        </form>

        <div id="result" class="mt-20"></div>

        <footer class="mt-20">
            <p>Flug-Informationssystem &copy; <?php echo date('Y'); ?> <a href="https://kighlander.de/" target="_blank">Kai Akkermann</a> | 
            <a href="https://github.com/Kighlander1975/dbe-api-exercise-one" target="_blank">GitHub-Dokumentation</a></p>
        </footer>
    </div>

    <script>
        // Store cities from PHP for autocomplete
        const cities = <?php echo $citiesJson; ?>;
        
        // Current date and time for the datetime input default value
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            now.setMilliseconds(null);
            now.setSeconds(null);
            document.getElementById('datetime').value = now.toISOString().slice(0, 16);
        });

        // Autocomplete function
        function setupAutocomplete(inputId) {
            const input = document.getElementById(inputId);
            
            // Execute function when someone writes in the text field
            input.addEventListener("input", function() {
                let val = this.value;
                closeAllLists();
                
                if (!val) { return false; }
                
                const autocompleteList = document.createElement("div");
                autocompleteList.setAttribute("id", this.id + "-autocomplete-list");
                autocompleteList.setAttribute("class", "autocomplete-items");
                this.parentNode.appendChild(autocompleteList);
                
                // For each item in the array...
                for (let i = 0; i < cities.length; i++) {
                    // Check if the item starts with the same letters as the text field value
                    if (cities[i].substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                        const itemElement = document.createElement("div");
                        itemElement.innerHTML = "<strong>" + cities[i].substr(0, val.length) + "</strong>";
                        itemElement.innerHTML += cities[i].substr(val.length);
                        itemElement.innerHTML += "<input type='hidden' value='" + cities[i] + "'>";
                        
                        itemElement.addEventListener("click", function() {
                            input.value = this.getElementsByTagName("input")[0].value;
                            closeAllLists();
                        });
                        
                        autocompleteList.appendChild(itemElement);
                    }
                }
            });
            
            // Execute when someone clicks in the document
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });
        }

        // Close all autocomplete lists except the one passed as an argument
        function closeAllLists(elmnt) {
            const x = document.getElementsByClassName("autocomplete-items");
            for (let i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != document.getElementById("start") && elmnt != document.getElementById("ziel")) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }

        // Set up autocomplete for both input fields
        setupAutocomplete("start");
        setupAutocomplete("ziel");

        // Form submission function
        function submitForm() {
            const start = document.getElementById('start').value;
            const ziel = document.getElementById('ziel').value;
            const datetimeInput = document.getElementById('datetime').value;
            const format = document.querySelector('input[name="format"]:checked').value;
            const urlFormat = document.querySelector('input[name="urlFormat"]:checked').value;
            
            // Parse the datetime value
            const dateObj = new Date(datetimeInput);
            const date = dateObj.toISOString().split('T')[0]; // YYYY-MM-DD
            const time = dateObj.toTimeString().split(' ')[0].substring(0, 5); // HH:MM
            
            let url;
            
            if (urlFormat === 'cleanWithDate') {
                // Use clean URL with date in path: /flights/json/Berlin/London/2025-11-15/14:30
                url = `flights/${format}/${encodeURIComponent(start)}/${encodeURIComponent(ziel)}/${date}/${time}`;
            } else {
                // Use clean URL with date as query parameter: /flights/json/Berlin/London?datetime=2025-11-15 14:30
                const datetime = datetimeInput.replace('T', ' ');
                url = `flights/${format}/${encodeURIComponent(start)}/${encodeURIComponent(ziel)}?datetime=${encodeURIComponent(datetime)}`;
            }
            
            // Redirect to the API endpoint
            window.open(url, '_blank');
        }
    </script>
</body>
</html>