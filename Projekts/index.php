<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_form.php");
    exit;
}
require("connectDB.php");
?>
<!DOCTYPE html>
<html lang="lv">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Autobusu saraksts</title>
<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<script src="script.js" defer></script>
<meta http-equiv="refresh" content="15">
</head>
<body>
<header>
    <div>
        <button id="loginButton">Login</button>
    </div>
    <div class="container">
        <div class="logo">
            <img src="https://kkp.lv/themes/kkp2/images/LogoKKP.jpg" alt="Logo" class="logo">
        </div>
        <?php
// Set locale to Latvian
setlocale(LC_TIME, 'lv_LV.UTF-8');

// Array of Latvian day abbreviations
$dayAbbreviations = array(
    'Pr', // Pirmdiena 
    'Ot', // Otrdiena
    'Tr', // Trešdiena
    'Ce', // Ceturtdiena 
    'Pk', // Piektdiena 
    'Se', // Sestdiena
    'Sv' // Svētdiena 
);

// Get current day abbreviation
$currentDayIndex = date('N') - 1;
$currentDayAbbreviation = $dayAbbreviations[$currentDayIndex];

// Display the current day abbreviation with glowing effect
echo '<span style="font-size: 1.5em;">';
foreach ($dayAbbreviations as $abbreviation) {
    if ($abbreviation == $currentDayAbbreviation) {
        echo '<span style="color: red;">' . $abbreviation . '</span> ';
    } else {
        echo $abbreviation . ' ';
    }
}
echo '</span>';
?>
        <div class="clock">
            <span id="hours"></span>
            <span>:</span>
            <span id="minutes"></span>
            <span>:</span>
            <span id="seconds"></span>
            
        </div>
  
    </div>
    <div class="nav"></div>
</header>
<section>
<?php 
$currentDateTime = time();
$formattedCurrentDateTime = date('Y-m-d H:i:s', $currentDateTime);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jaunie";
$port = "3307"; 
// Create connection
$savienojums = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($savienojums->connect_error) {
    die("Connection failed: " . $savienojums->connect_error);
}

$current_time = date("H:i:s");

// Function to execute query and return result set
function executeQuery($connection, $query) {
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Error executing query: " . mysqli_error($connection));
    }
    return $result;
}

// Query to fetch the next 9 data
$query_table1 = "SELECT r.` route_long_name`, st.` arrival_time`
FROM `routes` r
JOIN `trips` t ON r.`route_id` = t.`route_id`
JOIN `stop_times` st ON t.` trip_id` = st.`trip_id`
WHERE (r.` route_long_name` LIKE '%Kuldīga%' 
    OR r.` route_long_name` LIKE '%Mežvalde%' 
    OR r.` route_long_name` LIKE '%Priedaine%' 
    OR r.` route_long_name` LIKE '%Virka%' 
    OR r.` route_long_name` LIKE '%Ēdas%' 
    OR r.` route_long_name` LIKE '%Novadnieki%')
    AND STR_TO_DATE(st.` arrival_time`, '%H:%i:%s') > NOW() -- Filter out past arrival times
ORDER BY ABS(TIMESTAMPDIFF(SECOND, 
                STR_TO_DATE(st.` arrival_time`, '%H:%i:%s'), 
                NOW())) ASC
LIMIT 7;";

// Execute queries
$result_table4 = executeQuery($savienojums, $query_table1);

// Query to fetch the first 8 data
$query_table2 = "SELECT r.` route_long_name`, st.` arrival_time`
                FROM `routes` r
                JOIN `trips` t ON r.`route_id` = t.`route_id`
                JOIN `stop_times` st ON t.` trip_id` = st.`trip_id`
                WHERE (r.` route_long_name` LIKE '%Kuldīga%' 
                    OR r.` route_long_name` LIKE '%Mežvalde%' 
                    OR r.` route_long_name` LIKE '%Priedaine%' 
                    OR r.` route_long_name` LIKE '%Virka%' 
                    OR r.` route_long_name` LIKE '%Ēdas%' 
                    OR r.` route_long_name` LIKE '%Novadnieki%')
                ORDER BY r.`route_id` ASC
                LIMIT 9 ";

// Query to fetch the next 8 data
$query_table3 = "SELECT r.` route_long_name`, st.` arrival_time`
                FROM `routes` r
                JOIN `trips` t ON r.`route_id` = t.`route_id`
                JOIN `stop_times` st ON t.` trip_id` = st.`trip_id`
                WHERE (r.` route_long_name` LIKE '%Kuldīga%' 
                    OR r.` route_long_name` LIKE '%Mežvalde%' 
                    OR r.` route_long_name` LIKE '%Priedaine%' 
                    OR r.` route_long_name` LIKE '%Virka%' 
                    OR r.` route_long_name` LIKE '%Ēdas%' 
                    OR r.` route_long_name` LIKE '%Novadnieki%')
                ORDER BY r.`route_id` ASC
                LIMIT 9 OFFSET 9";

// Query to fetch the remaining data
$query_table4 = "SELECT r.` route_long_name`, st.` arrival_time`
                FROM `routes` r
                JOIN `trips` t ON r.`route_id` = t.`route_id`
                JOIN `stop_times` st ON t.` trip_id` = st.`trip_id`
                WHERE (r.` route_long_name` LIKE '%Kuldīga%' 
                    OR r.` route_long_name` LIKE '%Mežvalde%' 
                    OR r.` route_long_name` LIKE '%Priedaine%' 
                    OR r.` route_long_name` LIKE '%Virka%' 
                    OR r.` route_long_name` LIKE '%Ēdas%' 
                    OR r.` route_long_name` LIKE '%Novadnieki%')
                ORDER BY r.`route_id` ASC
                LIMIT 9 OFFSET 18"; // Assuming there are no more than 100000 records

// Execute queries
$result_table1 = executeQuery($savienojums, $query_table1);
$result_table2 = executeQuery($savienojums, $query_table2);
$result_table3 = executeQuery($savienojums, $query_table3);
$result_table4 = executeQuery($savienojums, $query_table4);

echo "<div class='show' id='busTimes'>";


// Display the fourth table with the next 9 data
echo "<div class='div'>";
echo "<table class='custom-table'>";
echo "<tr></tr>"; // Adding table headers for clarity
while ($row = mysqli_fetch_assoc($result_table1)) {
    echo "<tr>";
    echo "<td>{$row[' route_long_name']}</td>";
    echo "<td></td>";
    $hoursMinutes = date("H:i", strtotime($row[' arrival_time']));  
    echo "<td>{$hoursMinutes}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Display the first table with the first 8 data
echo "<div class='div1'>";
echo "<table class='custom-table'>";
echo "<tr></tr>"; // Adding table headers for clarity
while ($row = mysqli_fetch_assoc($result_table2)) {
    echo "<tr>";
    echo "<td>{$row[' route_long_name']}</td>";
    echo "<td></td>";
    $hoursMinutes = date("H:i", strtotime($row[' arrival_time']));  
    echo "<td>{$hoursMinutes}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Display the second table with the next 8 data
echo "<div class='div2'>";
echo "<table class='custom-table'>";
echo "<tr></tr>"; // Adding table headers for clarity
while ($row = mysqli_fetch_assoc($result_table3)) {
    echo "<tr>";
    echo "<td>{$row[' route_long_name']}</td>";
    echo "<td></td>";
    $hoursMinutes = date("H:i", strtotime($row[' arrival_time']));  
    echo "<td>{$hoursMinutes}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Display the third table with the remaining data
echo "<div class='div3'>";
echo "<table class='custom-table'>";
echo "<tr></tr>"; // Adding table headers for clarity
while ($row = mysqli_fetch_assoc($result_table4)) {
    echo "<tr>";
    echo "<td>{$row[' route_long_name']}</td>";
    echo "<td></td>";
    $hoursMinutes = date("H:i", strtotime($row[' arrival_time']));  
    echo "<td>{$hoursMinutes}</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

echo "</div>";
$savienojums->close();
?>
</section>
<footer>
    <div class='slider'>
    <?php
  // Establish a new connection for the footer section
  $savienojums_footer = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($savienojums_footer->connect_error) {
    die("Connection failed: " . $savienojums_footer->connect_error);
}

// Query for the footer section
$querys = "SELECT teksts FROM izmainas LIMIT 1";
$result_slider = mysqli_query($savienojums_footer, $querys);

// Loop through the result set and display the data
while ($rowi = mysqli_fetch_array($result_slider)) {
    echo "<div class='text'>{$rowi['teksts']}</div>";
}

// Close the connection for the footer section
$savienojums_footer->close();
    ?>
    
    </div>
</footer>
</body>
</html>