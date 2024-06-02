<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "kustiba";
$port = "3307"; 

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['loggedin'] = true;
            header("Location: admin.php");
            exit;
        } else {
            header("Location: login_form.php?error=invalid_credentials");
            exit;
        }
    } else {
        header("Location: login_form.php?error=invalid_credentials");
        exit;
    }
} else {
    header("Location: login_form.php");
    exit;
}

$stmt->close();
$conn->close();
?>
