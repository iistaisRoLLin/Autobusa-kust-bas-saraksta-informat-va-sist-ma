<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jaunie";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to join the necessary tables
$sql = "
SELECT r.route_long_name, st.arrival_time 
FROM routes r
JOIN trips t ON r.route_id = t.route_id
JOIN stop_times st ON t.trip_id = st.trip_id
ORDER BY r.route_long_name ASC, st.arrival_time ASC";

// Execute query and check for results
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Route Name: " . $row["route_long_name"]. " - Arrival Time: " . $row["arrival_time"]. "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
