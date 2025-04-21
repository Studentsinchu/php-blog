
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db.php';

// Check if ID is provided
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Delete post query
    $sql = "DELETE FROM posts WHERE id = $post_id";

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
    } else {
        echo "Error deleting post: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request!";
}
?>