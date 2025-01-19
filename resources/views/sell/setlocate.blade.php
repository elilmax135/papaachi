<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps - Select Multiple Locations and Calculate Distance</title>
    <link rel="stylesheet" href="{{ asset('css/locate_css.css') }}">
    <script src="https://maps.gomaps.pro/maps/api/js?key=&libraries=places,geometry&callback=initMap" async defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script>
    let geocoder, map, service, markers = [], selectedLocations = { from: null, to: null, waypoints: [] }, autocomplete;

// Initialize Google Map
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 7.8731, lng: 80.7718 },  // Default center (Sri Lanka)
        zoom: 7,
    });

    geocoder = new google.maps.Geocoder();
    service = new google.maps.DistanceMatrixService();

    // Initialize the Autocomplete feature for the address input box
    const input = document.getElementById('address');
    autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'LK' }, // Restrict to Sri Lanka
        fields: ['place_id', 'geometry', 'name'],
    });

    // Add listener for place selection
    autocomplete.addListener('place_changed', onPlaceChanged);

    // Add a click event listener to get coordinates when clicked on the map
    google.maps.event.addListener(map, 'click', function (event) {
        addLocation(event.latLng);
    });
}

// Function to handle place selection from autocomplete
function onPlaceChanged() {
    const place = autocomplete.getPlace();
    if (place.geometry) {
        const type = document.getElementById('location-type').value;

        // Add location based on selected type
        const location = {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng(),
            name: place.name,
        };

        if (type === 'from') {
            updateFromLocation(location);
        } else if (type === 'to') {
            updateToLocation(location);
        } else if (type === 'waypoint') {
            addWaypoint(location);
        }

        map.setCenter(place.geometry.location);
        calculateTotalDistance();
    } else {
        alert("No details available for input: '" + place.name + "'");
    }
}

// Add location when clicking on the map
function addLocation(latLng) {
    const type = document.getElementById('location-type').value;

    const location = {
        lat: latLng.lat(),
        lng: latLng.lng(),
        name: "Clicked Location",
    };

    if (type === 'from') {
        updateFromLocation(location);
    } else if (type === 'to') {
        updateToLocation(location);
    } else if (type === 'waypoint') {
        addWaypoint(location);
    }

    // Recalculate distance whenever a new location is added
    calculateTotalDistance();
}

// Update "From" location
function updateFromLocation(location) {
    if (selectedLocations.from?.marker) selectedLocations.from.marker.setMap(null);

    const marker = new google.maps.Marker({
        map: map,
        position: { lat: location.lat, lng: location.lng },
        label: 'A',
    });

    selectedLocations.from = { ...location, marker };
    updateSelectedLocations();
}

// Update "To" location
function updateToLocation(location) {
    if (selectedLocations.to?.marker) selectedLocations.to.marker.setMap(null);

    const marker = new google.maps.Marker({
        map: map,
        position: { lat: location.lat, lng: location.lng },
        label: 'B',
    });

    selectedLocations.to = { ...location, marker };
    updateSelectedLocations();
}

// Add a waypoint
function addWaypoint(location) {
    const marker = new google.maps.Marker({
        map: map,
        position: { lat: location.lat, lng: location.lng },
        label: `${selectedLocations.waypoints.length + 1}`,
    });

    selectedLocations.waypoints.push({ ...location, marker });
    updateSelectedLocations();
}

// Function to update the displayed list of selected locations
function updateSelectedLocations() {
    const locationsList = document.getElementById("selected-locations");
    locationsList.innerHTML = "";

    // Add "From" location
    if (selectedLocations.from) {
        const li = document.createElement("li");
        li.textContent = `From: ${selectedLocations.from.name} (${selectedLocations.from.lat.toFixed(4)}, ${selectedLocations.from.lng.toFixed(4)})`;
        locationsList.appendChild(li);
    }

    // Add "To" location
    if (selectedLocations.to) {
        const li = document.createElement("li");
        li.textContent = `To: ${selectedLocations.to.name} (${selectedLocations.to.lat.toFixed(4)}, ${selectedLocations.to.lng.toFixed(4)})`;
        locationsList.appendChild(li);
    }

    // Add waypoints
    selectedLocations.waypoints.forEach((wp, index) => {
        const li = document.createElement("li");
        li.textContent = `Waypoint ${index + 1}: ${wp.name} (${wp.lat.toFixed(4)}, ${wp.lng.toFixed(4)})`;
        locationsList.appendChild(li);
    });
}

// Function to calculate the total distance
function calculateTotalDistance() {
    if (!selectedLocations.from || !selectedLocations.to) {
        document.getElementById("total-distance").textContent = "Total Distance: 0 km";
        document.querySelector("input#total-distance").value = 0; // Update input field
        return;
    }

    const waypoints = selectedLocations.waypoints.map(wp => new google.maps.LatLng(wp.lat, wp.lng));
    const origin = new google.maps.LatLng(selectedLocations.from.lat, selectedLocations.from.lng);
    const destination = new google.maps.LatLng(selectedLocations.to.lat, selectedLocations.to.lng);

    const path = [origin, ...waypoints, destination];

    let totalDistance = 0;
    for (let i = 0; i < path.length - 1; i++) {
        totalDistance += google.maps.geometry.spherical.computeDistanceBetween(path[i], path[i + 1]);
    }

    const totalDistanceKm = (totalDistance / 1000).toFixed(2); // Convert to kilometers and format
    document.getElementById("total-distance").textContent = `Total Distance: ${totalDistanceKm} km`;
    document.querySelector("input#total-distance").value = totalDistanceKm; // Update input field
}


// Reset the map and locations
function resetMap() {
    Object.values(selectedLocations).flat().forEach(loc => loc.marker?.setMap(null));
    selectedLocations = { from: null, to: null, waypoints: [] };
    markers = [];
    updateSelectedLocations();
    document.getElementById("total-distance").textContent = "Total Distance: 0 km";
}

    </script>
</head>
<body onload="initMap()">
    <h1>Select Locations (From, To, and Waypoints)</h1>
    <div>
        <select id="location-type" style="padding: 10px; margin-bottom: 10px;">
            <option value="from">From</option>
            <option value="to">To</option>
            <option value="waypoint">Waypoint</option>
        </select>
        <input
            type="text"
            id="address"
            placeholder="Enter a location"
            style="padding: 10px; width: 60%;"
        />
        <button onclick="resetMap()" style="padding: 10px; background: red; color: white;">Reset</button>
    </div>
    <ul id="selected-locations"></ul>
    <form action="{{url('/locate')}}" method="POST">
        @csrf
        @if ($lastRecord)
        <input type="number" id="sell_id" name="sell_id" value="{{ $lastRecord->id }}" class="form-control" style="padding: 18px; background: rgb(99, 39, 39); color: white;" readonly>
        @endif
        <label for="total-distance">Total Distance (km):</label>
        <input type="number" id="total-distance" name="total_distance" class="form-control" step="0.01" min="0"  style="padding: 18px; background: rgb(80, 38, 38); color: white;" readonly required>

    <div id="map" style="height: 500px; width: 100%;"></div>
    <button class="button">Save Location</button>

    </form>
    <a href="{{ url('/addSell') }}" class="btn-back">Back</a>
    <script>
    function goBack() {
        window.history.back(); // Navigate to the previous page
    }
    </script>
</body>
</html>
