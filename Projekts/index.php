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
<footer>
</footer>





<?php

if (isset($_POST['submit'])) {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        $_SESSION['userid'] = $user_data['id'];
        header("location: admin.php");
    } else {

        echo "Invalid username or password.";
    }
}

mysqli_close($conn);
?>
</body>
</html>