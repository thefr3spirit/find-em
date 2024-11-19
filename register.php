<?php
// Include database connection
include 'db_connection.php';

// Get form data
$user = $_POST['username'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Prepare and execute SQL query to insert data into the locations table
$sql_location = "INSERT INTO locations (username, longitude, latitude) VALUES (?, ?, ?)";
$stmt_location = $conn->prepare($sql_location);
$stmt_location->bind_param("sdd", $user, $longitude, $latitude);

if ($stmt_location->execute()) {
    echo "Registration successful! You can now <a href='index.php'>find friends</a>.";
} else {
    echo "Error: " . $stmt_location->error;
}

$stmt_location->close();
$conn->close();
?>
