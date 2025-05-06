<?php
session_start();

// If the user is already logged in, send them to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - Blog System</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-align: center;
        }

        .container {
            margin-top: 15%;
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            display: inline-block;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 30px;
            color: #ffcc00;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .register-btn {
            background-color: #28a745;
            color: white;
        }

        .login-btn {
            background-color: #007bff;
            color: white;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Blog System</h1>

        <a href="register.php" class="btn register-btn">Register</a>
        <a href="login.php" class="btn login-btn">Login</a>
    </div>
</body>
</html>