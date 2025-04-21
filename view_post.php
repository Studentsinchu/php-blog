<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db.php';

// Check if post ID is provided
if (!isset($_GET['id'])) {
    echo "Post not found!";
    exit();
}

$post_id = $_GET['id'];

// Fetch the post from the database
$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $post = mysqli_fetch_assoc($result);
} else {
    echo "Post not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
</head>
<body>

<h2><?php echo htmlspecialchars($post['title']); ?></h2>
<p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

<a href="index.php">⬅ Back to Post List</a>

</body>
</html>