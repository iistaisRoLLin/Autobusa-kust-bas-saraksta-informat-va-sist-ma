<!DOCTYPE html>
<html lang="lv">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Autobusu saraksts</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <script src="script.js" defer></script>
  <?php 
  require("connectDB.php")
  ?>
    
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
            <div class="clock">
                <span id="hours"></span>
                <span>:</span>
                <span id="minutes"></span>
                <span>:</span>
                <span id="seconds"></span>
            </div>
        </div>
        <div class="nav">
            
        </div>
    </header>
 
    <section>
    <?php 
$currentDateTime = time(); 

// Query for table 1
$query_table1 = "SELECT busID, marsruts, laiks FROM test WHERE marsruts LIKE '%Kuldiga%' AND laiks > NOW() ORDER BY laiks ASC LIMIT 10";

// Query for table 2 (first 15 rows)
$query_table2 = "SELECT busID, marsruts, laiks FROM test LIMIT 15";

$result_table1 = mysqli_query($savienojums, $query_table1);
$result_table2 = mysqli_query($savienojums, $query_table2);

if ($result_table1 && $result_table2) {
    echo "<div class='show' id='busTimes'>";
    
    // Display table 1
    echo "<div class='div1'>"; // Start div1
    echo "<table class='custom-table'>"; // Start table for div1
    
    while ($row = mysqli_fetch_array($result_table1)) {
        $timeFromDB = strtotime($row['laiks']);
        
        if ($timeFromDB >= $currentDateTime) { 
            echo "<tr>";
            echo "<td>{$row['marsruts']}</td>";
            echo "<td></td>";
            
            $hoursMinutes = date("H:i", $timeFromDB);  
            
            echo "<td>{$hoursMinutes}</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>"; // End table for div1
    echo "</div>"; // End div1
    
    // Display table 2 (first 15 rows)
    echo "<div class='div2'>"; // Start div2
    echo "<table class='custom-table'>"; // Start table for div2
    
    $count = 0; // Counter to track the number of rows displayed
    
    while ($row = mysqli_fetch_array($result_table2)) {
        if ($count < 15) {
            echo "<tr>";
            echo "<td>{$row['marsruts']}</td>";
            echo "<td></td>";
            
            $hoursMinutes = date("H:i", strtotime($row['laiks']));  
            
            echo "<td>{$hoursMinutes}</td>";
            echo "</tr>";
            
            $count++;
        }
    }
    
    echo "</table>"; // End table for div2
    echo "</div>"; // End div2

    // Dynamically create and append third div (next 15 rows)
    echo "<div class='div3'>"; // Start div3
    echo "<table class='custom-table'>"; // Start table for div3
    
    // Fetch and display the next 15 rows from the database
    $query_table3 = "SELECT busID, marsruts, laiks FROM test LIMIT 15 OFFSET 15"; // Query for the next 15 rows
    $result_table3 = mysqli_query($savienojums, $query_table3);
    
    while ($row = mysqli_fetch_array($result_table3)) {
        echo "<tr>";
        echo "<td>{$row['marsruts']}</td>";
        echo "<td></td>";
        
        $hoursMinutes = date("H:i", strtotime($row['laiks']));  
        
        echo "<td>{$hoursMinutes}</td>";
        echo "</tr>";
    }
    
    echo "</table>"; // End table for div3
    echo "</div>"; // End div3
    
    // Dynamically create and append fourth div (next 15 rows)
    echo "<div class='div4'>"; // Start div4
    echo "<table class='custom-table'>"; // Start table for div4
    
    // Fetch and display the next 15 rows from the database
    $query_table4 = "SELECT busID, marsruts, laiks FROM test LIMIT 15 OFFSET 30"; // Query for the next 15 rows
    $result_table4 = mysqli_query($savienojums, $query_table4);
    
    while ($row = mysqli_fetch_array($result_table4)) {
        echo "<tr>";
        echo "<td>{$row['marsruts']}</td>";
        echo "<td></td>";
        
        $hoursMinutes = date("H:i", strtotime($row['laiks']));  
        
        echo "<td>{$hoursMinutes}</td>";
        echo "</tr>";
    }
    
    echo "</table>"; // End table for div4
    echo "</div>"; // End div4

    echo "</div>"; // End container for all divs

} else {
    echo "Query failed: " . mysqli_error($savienojums);
}


        ?>
        </section> 
        <?php

        $querys = "SELECT teksts FROM izmainas LIMIT 1";
        $results = mysqli_query($savienojums, $querys);
        while ($rowi = mysqli_fetch_array($results)){
            echo "<footer>";
            echo "<div class='slider'>";
       echo " <div class='text'>{$rowi['teksts']}</div>";
       echo "</div>";
           echo " </footer>";


        }




        
    ?>







</body>
</html>