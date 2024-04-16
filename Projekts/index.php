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

</head>
<body>
    <header>
    <div>
        <button id="loginButton">Login</button>
    </div>
        <div id="loginPanel">
            <form id="loginForm">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="Submit">
            </form>
        </div>

        <div class="container">
            <div class="logo">
                <img src="https://www.kuldiga.lv/images/Faili/Novads/kuldigas_novada_gerbonis.png" alt="Logo" class="logo">
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
        $query = "SELECT busID, marsruts, laiks FROM test LIMIT 7";
        $result = mysqli_query($savienojums, $query);
        while ($row = mysqli_fetch_array($result)){


echo "<div class='show'>";
        echo "<tr>";
        echo "<td>{$row['marsruts']}</td>";
        echo "<td>  </td>";
        echo "<td>{$row['laiks']}</td>";
        echo "</tr>";
        echo "</div>";

        }
        ?>
        <?php

        $querys = "SELECT teksts FROM izmainas LIMIT 1";
        $results = mysqli_query($savienojums, $querys);
        while ($rowi = mysqli_fetch_array($results)){

            echo "</section>";
            echo "<footer>";
       echo " <h1>{$rowi['teksts']}</h1>";
           echo " </footer>";


        }
    ?>
  </section>







</body>
</html>