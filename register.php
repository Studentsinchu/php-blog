<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "php-blog");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user already exists
    $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($checkUser) > 0) {
        echo "Username already taken!";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "Registration successful. <a href='login.php'>Login here</a>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - PHP Blog</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="register" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
