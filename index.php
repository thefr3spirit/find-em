<?php
// Include database connection
include 'db_connection.php';

// Handle AJAX POST request to update and fetch locations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data['action'] === 'update_location') {
        // Removed logic for saving user's location (no username or device info)
        // Fetch all friends' locations (this part stays)
        $result = $conn->query("SELECT username, latitude, longitude FROM locations");
        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row;
        }
        echo json_encode($locations);
        $conn->close();
        exit;
    }
}

// Fetch usernames from the `locations` table for the friends list
$sql = "SELECT username FROM locations";
$result = $conn->query($sql);
$usernames = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usernames[] = $row['username'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIS Project - Friends</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0FtZ84MK3EQhSBsGrChuOG8M0sRjbSGY" async defer></script>
    <style>
        /* Inline styles for simplicity; move to styles.css if preferred */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #333; color: white; }
        .logo { font-size: 1.5em; }
        .content { display: flex; flex-direction: column; padding: 20px; gap: 20px; }
        @media(min-width: 768px) { .content { flex-direction: row; justify-content: space-around; } }
        .friends-list, .map-container { background-color: white; border-radius: 10px; padding: 20px; width: 100%; max-width: 400px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .friends-list h2, .map-container h2 { margin-bottom: 10px; }
        .friends-list ul { list-style-type: none; padding: 0; }
        .friends-list li { padding: 8px 0; border-bottom: 1px solid #ddd; }
        .map { width: 100%; height: 300px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="top-bar">
        <span class="logo">GIS Project</span>
        <span>Friends</span>
    </div>

    <div class="content">
        <!-- Friends List Section -->
        <div class="friends-list">
            <h2>Available Friends</h2>
            <ul id="friendsContainer">
                <?php foreach ($usernames as $username): ?>
                    <li><?php echo htmlspecialchars($username); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Map Section -->
        <div class="map-container">
            <h2>Friends' Locations</h2>
            <div id="map" class="map"></div> <!-- Map container for Google Maps -->
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
