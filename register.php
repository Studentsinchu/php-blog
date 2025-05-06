<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Server-side validation
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "❌ All fields are required!";
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Validate username length
        if (strlen($username) > 50) {
            $error = "❌ Username must be less than 50 characters.";
        }
        // Validate password length
        elseif (strlen($password) < 6) {
            $error = "❌ Password must be at least 6 characters long.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'editor'; // Default role for new users

            // Check if username already exists
            $checkUser = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($checkUser);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "❌ Username already exists. <a href='login.php'>Click here to Login</a>";
            } else {
                // Insert new user
                $insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert);
                $stmt->bind_param("sss", $username, $hashedPassword, $role);
                if ($stmt->execute()) {
                    $success = "✅ Registration successful! <a href='login.php'>Click here to Login</a>";
                } else {
                    $error = "❌ Error: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right,rgb(16, 6, 25),rgba(231, 9, 142, 0.91));
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: rgba(0,0,0,0.5);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        h2 {
            color: white;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            width: 250px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #005999;
        }
        p {
            color: white;
        }
        p a {
            color: white;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
        .msg {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Register</h2>

        <?php if (isset($error)) echo "<p class='msg' style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='msg' style='color:green;'>$success</p>"; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Enter Username" required maxlength="50"><br>
            <input type="password" name="password" placeholder="Enter Password" required minlength="6"><br>
            <input type="submit" value="Register">
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>