<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        }
    }
    $error = "❌ Invalid login credentials.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(to right, #8e2de2, #ff6ec4);
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
            padding-top: 80px;
        }
 
        form {
            background: rgba(0,0,0,0.5);
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 10px;
            width: 250px;
            border: none;
            border-radius: 5px;
        }
        button {
            padding: 10px 25px;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        a {
            color: #f1c40f;
            display: block;
            margin-top: 10px;
            text-decoration: none;
        }
        .error {
            color: #ff4d4d;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <h2>🔐 Login</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

   
</body>
</html>