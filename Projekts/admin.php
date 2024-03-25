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
<table>
  <thead>
    <tr>
      <th>Marsruts</th>
      <th>Laiks</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $query = "SELECT route_long_name FROM routes";
    $result = mysqli_query($savienojums, $query);
    $querys = "SELECT arrival_time FROM stop_times";
    $results = mysqli_query($savienojums, $querys);
    while( $rows = mysqli_fetch_array($results)){
    while ($row = mysqli_fetch_array($result)){
    ?>
    <tr>
      <td><?php echo $row["route_long_name"]; ?></td>
      <td><?php echo $rows["arrival_time"]; ?></td>
    </tr>
    <?php
    }}
    ?>
  </tbody>
</table>

</body>
</html>
