<?php

// Connect to your database
$databaseConnection = new mysqli('localhost', 'root', '', 'kustiba');

// Check if the connection was successful
if ($databaseConnection->connect_error) {
    die("Connection failed: " . $databaseConnection->connect_error);
}

// Enable LOAD DATA LOCAL INFILE for the current session
if (!$databaseConnection->query("SET GLOBAL local_infile = 1")) {
    die("Error setting local_infile: " . $databaseConnection->error);
}

// Enable LOAD DATA LOCAL INFILE for the connection
if (!$databaseConnection->options(MYSQLI_OPT_LOCAL_INFILE, true)) {
    die("Error setting MYSQLI_OPT_LOCAL_INFILE: " . $databaseConnection->error);
}

// Call the function with the zip URL and database connection
$zipUrl = 'https://www.atd.lv/sites/default/files/GTFS/gtfs-latvia-lv.zip';
processFolderFromZip($zipUrl, $databaseConnection);

// Fetch and display route_long_name from routes and departure_time from stop_times
$query = "
    SELECT r.route_long_name, s.departure_time
    FROM routes r
    JOIN stop_times s ON r.route_id = s.route_id
    LIMIT 10";

if ($result = $databaseConnection->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo "Route: " . $row['route_long_name'] . " - Departure Time: " . $row['departure_time'] . "\n";
    }
    $result->free();
} else {
    echo "Error fetching data: " . $databaseConnection->error . "\n";
}

// Close the database connection
$databaseConnection->close();

?>
