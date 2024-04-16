<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Add CSS styles here for the login panel -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .login-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        
        .login-container h2 {
            margin-bottom: 20px;
            color: #333333;
        }
        
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .login-container input[type="submit"] {
            background-color: #4caf50; /* Green */
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .login-container p {
            margin-top: 15px;
            font-size: 14px;
            color: #777777;
        }
        
        .login-container a {
            color: #2196F3; /* Blue */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-container a:hover {
            color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>