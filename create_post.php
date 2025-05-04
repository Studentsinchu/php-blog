<?php
session_start();
include 'config/db.php'; // Include your database connection

if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Server-side validation
    if (empty($title) || empty($content)) {
        echo "Title and content are required.";
        exit;
    }

    // Prepare statement to prevent SQL injection
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Post created successfully!";
    } else {
        echo "Failed to create post.";
    }
}
?>

<form method="POST" action="">
    Title: <input type="text" name="title" required>
    Content: <textarea name="content" required></textarea>
    <input type="submit" value="Create Post">
</form>