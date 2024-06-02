<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login_form.php");
    exit;
}
require("connectDB.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrator sadaļa</title>
<style>
    .container {
        display: inline-block;
        vertical-align: top;
        margin-right: 20px;
    }
    table {
        border-collapse: collapse;
        width: 300px;
        border: 1px solid #dddddd;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    th, td {
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tbody tr:hover {
        background-color: #f5f5f5;
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
</style>
</head>
<body>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>Marsruts</th>
                <th>Laiks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete1'])) {
                $id = $_POST['delete1'];
                try {
                    $stmt = $savienojums->prepare("DELETE FROM test WHERE busID = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    echo "Record deleted successfully.";
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
            $query = "SELECT busID, marsruts, laiks FROM test";
            $result = mysqli_query($savienojums, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['marsruts']}</td>";
                echo "<td>{$row['laiks']}</td>";
                echo "<td>
                        <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                            <input type='hidden' name='delete1' value='{$row['busID']}'>
                            <button type='submit'>Dzēst</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>Jaunumi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete2'])) {
                $id = $_POST['delete2'];
                try {
                    $stmt = $savienojums->prepare("DELETE FROM izmainas WHERE izmainasID = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    echo "Record deleted successfully.";
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
            $query = "SELECT izmainasID, teksts FROM izmainas";
            $result = mysqli_query($savienojums, $query);
            while ($rowi = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$rowi['teksts']}</td>";
                echo "<td>
                        <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                            <input type='hidden' name='delete2' value='{$rowi['izmainasID']}'>
                            <button type='submit'>Dzēst</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['submit'])) {
                    $newEntry = $_POST['new_entry'];
                    $query = "SELECT COUNT(*) AS count FROM izmainas WHERE teksts = ?";
                    $stmt = $savienojums->prepare($query);
                    $stmt->bind_param('s', $newEntry);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $count = $row['count'];
                    if ($count == 0) {
                        try {
                            $stmt = $savienojums->prepare("INSERT INTO izmainas (teksts) VALUES (?)");
                            $stmt->bind_param('s', $newEntry);
                            $stmt->execute();
                            echo "New record added successfully.";
                        } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <tr>
            <td><input type="text" name="new_entry" required></td>
            <td><button type="submit" name="submit">Pievienot</button></td>
        </tr>
    </form>
</div>
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }
</script>
</body>
</html>
