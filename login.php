<?php
session_start();
$conn = new mysqli("localhost", "root", "", "php-blog");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username.";
    }
    $stmt->close();
}
?>

<!-- HTML Form -->
<h2>Login</h2>
<form method="post" action="">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
</form>