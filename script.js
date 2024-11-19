let map;
let userMarker;
let markers = [];

// Function to initialize the map
function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            (position) => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                if (!map) {
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: userLocation,
                        zoom: 15
                    });

                    userMarker = new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "Your Location",
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 8,
                            fillColor: "#4285F4",
                            fillOpacity: 1,
                            strokeWeight: 2,
                            strokeColor: "#ffffff"
                        }
                    });

                    // Only fetch and display other users' locations (don't send current device location)
                    fetchAndDisplayLocations();
                } else {
                    userMarker.setPosition(userLocation);
                    map.setCenter(userLocation);
                    // Only fetch and display other users' locations
                    fetchAndDisplayLocations();
                }
            },
            (error) => console.error("Geolocation error:", error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: 5000 }
        );
    } else {
        console.error("Geolocation not supported.");
    }
}

// Fetch and display other users' locations (No sending current location data)
async function fetchAndDisplayLocations() {
    try {
        const response = await fetch("index.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: "update_location" })
        });

        const locations = await response.json();

        // Clear old markers and set new ones
        markers.forEach(marker => marker.setMap(null));
        markers = [];

        locations.forEach(friend => {
            const marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(friend.latitude),
                    lng: parseFloat(friend.longitude)
                },
                map: map,
                title: friend.username,
                icon: {
                    path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
                    scale: 6,
                    fillColor: "#34A853",
                    fillOpacity: 1,
                    strokeWeight: 1,
                    strokeColor: "#ffffff"
                }
            });
            markers.push(marker);
        });
    } catch (error) {
        console.error("Error fetching locations:", error);
    }
}

// Initialize map when the DOM is loaded
document.addEventListener("DOMContentLoaded", initMap);
