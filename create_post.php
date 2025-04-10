<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "php-blog");

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO posts (title, content, user_id, created_at) VALUES ('$title', '$content', '$user_id', NOW())";
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Title and Content are required!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>
<body>
    <h1>Create New Post</h1>
    <form method="POST" action="">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>
        <label>Content:</label><br>
        <textarea name="content" rows="6" cols="50" required></textarea><br><br>
        <input type="submit" name="submit" value="Publish">
    </form>
    <br>
    <a href="index.php">← Back to Posts</a>
</body>
</html>