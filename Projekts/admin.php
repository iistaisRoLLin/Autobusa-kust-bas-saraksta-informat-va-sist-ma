<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, redirect to the login page
    header("Location: login_form.php");
    exit;
}
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
  </head>
<body>
<?php 
  require("connectDB.php")
  ?>
<table >
  <thead>
    <tr>
      <th>Marsruts</th>
      <th>Laiks</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $query = "SELECT busID, marsruts, laiks FROM test";
        $result = mysqli_query($savienojums, $query);
        while ($row = mysqli_fetch_array($result)){
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  if (isset($_POST['delete'])) {
      
      include 'connectDB.php';

     
      $id = $_POST['delete'];

      try {
         
          $sql = "DELETE FROM test WHERE busID=?"; 
          $stmt = $savienojums->prepare($sql);
          $stmt->bind_param('i', $id);

          
          $stmt->execute();

         header("refresh:0");
      } catch(PDOException $e) {
        
      }

     
      $savienojums = null;
  }
}





echo "<div class='container'>";
        echo "<tr>";
        echo "<td>{$row['marsruts']}</td>";
        echo "<td>{$row['laiks']}</td>";
        echo "<td>
                <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                    <input type='hidden' name='delete' value='{$row['busID']}'>
                    <button type='submit'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
        echo "</div>";
    
    ?>
  

    
    <?php
    }
    
    ?>
  
  </tbody>
</table>


<?php // Izmainu dala -----------------------------------------------------------------------------------------------------------------  ?>



<table>
  <thead>
    <tr>
      <th>Jaunumi</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $query = "SELECT izmainasID, teksts FROM izmainas";
        $result = mysqli_query($savienojums, $query);
        while ($rowi = mysqli_fetch_array($result)){
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  if (isset($_POST['delete'])) {
     
      include 'connectDB.php';

      $id = $_POST['delete'];

      try {
        
          $sql = "DELETE FROM izmainas WHERE izmainasID=?"; 

        
          $stmt = $savienojums->prepare($sql);

         
          $stmt->bind_param('i', $id);

        
          $stmt->execute();

        
         header("refresh:0");
      } catch(PDOException $e) {
       
      }

      
      $savienojums = null;
  }
}


        echo "<div class='container'>";
        echo "<tr>";
        echo "<td>{$rowi['teksts']}</td>";
        echo "<td>
                <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                    <input type='hidden' name='delete' value='{$rowi['izmainasID']}'>
                    <button type='submit'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
        echo "</div>";
    ?>
  

 
    
    <?php
    }
    
    ?>
  
  </tbody>
</table>



</body>
</html>
