<?php
session_start();
include 'config/db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    // Prepare statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Store user role in session

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        } else {
            header("Location: user_dashboard.php"); // Redirect to user dashboard
        }
        exit;
    } else {
        echo "Invalid username or password.";
    }
}
?>

<form method="POST" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
</form>