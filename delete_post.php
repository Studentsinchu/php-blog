<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "php-blog");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$post_id = $_GET['id'] ?? null;

if ($post_id) {
    // Only delete if the post belongs to the logged-in user
    $sql = "DELETE FROM posts WHERE id='$post_id' AND user_id=" . $_SESSION['user_id'];
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting post: " . mysqli_error($conn);
    }
} else {
    echo "Invalid post ID.";
}
?>