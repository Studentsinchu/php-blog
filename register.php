<?php
include 'config/db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = 'user'; // Default role

    // Check if the user wants to register as admin
    if (isset($_POST['is_admin']) && $_POST['is_admin'] == 'on') {
        $role = 'admin'; // Assign admin role
    }

    // Server-side validation
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare statement to prevent SQL injection
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Registration failed.";
    }
}
?>

<form method="POST" action="">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <label>
        <input type="checkbox" name="is_admin"> Register as Admin
    </label>
    <input type="submit" value="Register">
</form>