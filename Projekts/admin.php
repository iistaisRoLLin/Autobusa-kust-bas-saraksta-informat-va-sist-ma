<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_form.php");
    exit;
}

require("connectDB.php");

// Database connection
$savienojums = new mysqli('localhost', 'root', '', 'jaunie', '3307');

if ($savienojums->connect_error) {
    die("Connection failed: " . $savienojums->connect_error);
}

// Handle deletion of routes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete1'])) {
    $id = $_POST['delete1'];
    try {
        $stmt = $savienojums->prepare("DELETE FROM routes WHERE route_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        echo "Record deleted successfully.";
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to check and update expired records
function checkAndUpdateExpiredRecords($savienojums) {
    $expiryTime = '86400 SECOND'; // Set expiry time to 30 seconds for testing purposes
    $defaultText = 'Jautājumu gadījumā aicinām sazināties ar pasažieru pārvadātājiem! Kuldīgas autoosta: ☎️63322061.'; // Set the default value to replace expired records

    // Select records that have expired
    $query = "SELECT izmainasID FROM izmainas WHERE timestamp < NOW() - INTERVAL $expiryTime";
    $result = mysqli_query($savienojums, $query);
    if (!$result) {
        die("Error executing query: " . mysqli_error($savienojums));
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['izmainasID'];
        try {
            // Update expired records with the default value and current timestamp
            $stmt = $savienojums->prepare("UPDATE izmainas SET teksts = ?, timestamp = CURRENT_TIMESTAMP WHERE izmainasID = ?");
            $stmt->bind_param('si', $defaultText, $id);
            if (!$stmt->execute()) {
                echo "Error updating record: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Check and update expired records before any other operations
checkAndUpdateExpiredRecords($savienojums);

// Handle deletion of records
// Handle deletion of records
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete2'])) {
    $id = $_POST['delete2'];
    try {
        $stmt = $savienojums->prepare("DELETE FROM izmainas WHERE izmainasID = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo "Record deleted successfully.";
            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        } else {
            echo "Error deleting record: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle addition of new 'izmainas' record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $new_entry = $_POST['new_entry'];
    // Check if the entry already exists
    $query = "SELECT COUNT(*) FROM izmainas WHERE teksts = ?";
    $stmt = $savienojums->prepare($query);
    $stmt->bind_param('s', $new_entry);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Error: This entry already exists.";
    } else {
        try {
            $stmt = $savienojums->prepare("INSERT INTO izmainas (teksts) VALUES (?)");
            $stmt->bind_param('s', $new_entry);
            if ($stmt->execute()) {
                echo "New record created successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Handle addition of new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Check if the username already exists
    $query = "SELECT COUNT(*) FROM user WHERE username = ?";
    $stmt = $savienojums->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "Error: This username already exists.";
    } else {
        try {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $savienojums->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
            $stmt->bind_param('ss', $username, $hashed_password);
            if ($stmt->execute()) {
                echo "New user created successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Handle deletion of user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    try {
        $stmt = $savienojums->prepare("DELETE FROM user WHERE userID = ?");
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute()) {
            echo "User deleted successfully.";
            // Redirect to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        } else {
            echo "Error deleting user: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


// Display routes records with "Kuldīga"
$query = "SELECT ` route_long_name`, route_id FROM routes WHERE ` route_long_name` LIKE '%Kuldīga%'";
$result = mysqli_query($savienojums, $query);
if (!$result) {
    die("Error executing query: " . mysqli_error($savienojums));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrator sadaļa</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f9;
        margin: 0;
        padding: 0;
    }
    .header {
        background: #f44336;
        color: white;
        padding: 15px 0;
        text-align: center;
    }
    .container {
        display: inline-block;
        vertical-align: top;
        margin: 20px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tbody tr:hover {
        background-color: #f1f1f1;
    }
    button {
        background-color: #f44336;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #d32f2f;
    }
    .form-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
    .form-container input, .form-container button {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        box-sizing: border-box;
    }
</style>
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }
</script>
</head>
<body>
<div class="header">
<h1>Administrator sadaļa</h1>
</div>
<div class="container">
    <h2>Maršruti</h2>
    <table>
        <thead>
            <tr>
                <th>Maršruts</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row[' route_long_name']); ?></td>
                    <td>
                        <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' onsubmit='return confirmDelete()'>
                            <input type='hidden' name='delete1' value='<?php echo htmlspecialchars($row['route_id']); ?>'>
                            <button type='submit'>Dzēst</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div class="container">
    <h2>Jaunumi</h2>
    <table>
        <thead>
            <tr>
                <th>Jaunumi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display records
            $query = "SELECT izmainasID, teksts FROM izmainas";
            $result = mysqli_query($savienojums, $query);
            if (!$result) {
                die("Error executing query: " . mysqli_error($savienojums));
            }
            while ($rowi = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($rowi['teksts']); ?></td>
                    <td>
                        <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' onsubmit='return confirmDelete()'>
                            <input type='hidden' name='delete2' value='<?php echo htmlspecialchars($rowi['izmainasID']); ?>'>
                            <button type='submit'>Dzēst</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-container">
        <input type="text" name="new_entry" placeholder="Jauna ieraksta teksts" required>
        <button type="submit" name="submit">Pievienot</button>
    </form>
</div>
<div class="container">

    <h2>Administrēt lietotājus</h2>
    <h3>Lietotāju saraksts:</h3>
    <table>
        <thead>
            <tr>
                <th>Lietotāja ID</th>
                <th>Lietotājvārds</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch user data
            $userQuery = "SELECT userID, username FROM user";
            $userResult = mysqli_query($savienojums, $userQuery);
            if (!$userResult) {
                die("Error executing user query: " . mysqli_error($savienojums));
            }
            while ($userRow = mysqli_fetch_assoc($userResult)):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($userRow['userID']); ?></td>
                    <td><?php echo htmlspecialchars($userRow['username']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-container">
        <h3>Pievienot lietotāju</h3>
        <input type="text" name="username" placeholder="Lietotājvārds" required>
        <input type="password" name="password" placeholder="Parole" required>
        <button type="submit" name="add_user">Pievienot lietotāju</button>
    </form>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-container">
        <h3>Dzēst lietotāju</h3>
        <input type="number" name="user_id" placeholder="Lietotāja ID" required>
        <button type="submit" name="delete_user">Dzēst lietotāju</button>
    </form>
</div>
<div class="container">
        <h2>Atjaunināšana</h2>
        <button type="button" onclick="startFile()">Atjaunināt maršrutus</button>
    </div>

    <script>
        function startFile() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "file.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log("file.php executed successfully");
                } else if (xhr.readyState == 4) {
                    console.log("Error: " + xhr.status);
                }
            };

            xhr.send();
        }
    </script>
</body>
</html>

