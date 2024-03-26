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
  // Check if delete button is clicked
  if (isset($_POST['delete'])) {
      // Include your database connection file
      include 'connectDB.php';

      // Get the ID of the row to delete
      $id = $_POST['delete'];

      try {
          // SQL to delete a record
          $sql = "DELETE FROM test WHERE busID=?"; // assuming 'id' is the primary key

          // Prepare the statement
          $stmt = $savienojums->prepare($sql);

          // Bind parameters
          $stmt->bind_param('i', $id);

          // Execute the statement
          $stmt->execute();

         // echo "Record with ID $id deleted successfully";
         header("refresh:0");
      } catch(PDOException $e) {
        //  echo "Error: " . $e->getMessage();
      }

      // Close the connection
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
        while ($row = mysqli_fetch_array($result)){
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if delete button is clicked
  if (isset($_POST['delete'])) {
      // Include your database connection file
      include 'connectDB.php';

      // Get the ID of the row to delete
      $id = $_POST['delete'];

      try {
          // SQL to delete a record
          $sql = "DELETE FROM izmainas WHERE izmainasID=?"; // assuming 'id' is the primary key

          // Prepare the statement
          $stmt = $savienojums->prepare($sql);

          // Bind parameters
          $stmt->bind_param('i', $id);

          // Execute the statement
          $stmt->execute();

         // echo "Record with ID $id deleted successfully";
         header("refresh:0");
      } catch(PDOException $e) {
        //  echo "Error: " . $e->getMessage();
      }

      // Close the connection
      $savienojums = null;
  }
}



        echo "<tr>";
        echo "<td>{$row['teksts']}</td>";
        echo "<td>
                <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                    <input type='hidden' name='delete' value='{$row['izmainasID']}'>
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
