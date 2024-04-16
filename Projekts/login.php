<?php
// Connect to your database (replace placeholders with actual values)
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "kustiba";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to retrieve user from the database
    $sql = "SELECT * FROM user WHERE username = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Start the session and set a session variable to indicate the user is logged in
            session_start();
            $_SESSION['loggedin'] = true;
            
            // Redirect the user to a restricted page after successful login
            header("Location: admin.php");
            exit;
        } else {
            // Invalid password, redirect back to the login page with an error message
            header("Location: login_form.php?error=invalid_credentials");
            exit;
        }
    } else {
        // User not found, redirect back to the login page with an error message
        header("Location: login_form.php?error=invalid_credentials");
        exit;
    }
} else {
    // If someone tries to access this script directly without submitting the form, redirect to the login page
    header("Location: login_form.php");
    exit;
}

// Close the database connection
$stmt->close();
$conn->close();
?>
