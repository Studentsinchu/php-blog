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

// Fetch the post details
if ($post_id) {
    $sql = "SELECT * FROM posts WHERE id = '$post_id' AND user_id = " . $_SESSION['user_id'];
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $post = mysqli_fetch_assoc($result);
    } else {
        echo "Post not found or permission denied.";
        exit();
    }
}

// Update post
if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    if (!empty($title) && !empty($content)) {
        $sql = "UPDATE posts SET title='$title', content='$content' WHERE id='$post_id' AND user_id = " . $_SESSION['user_id'];
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
    <title>Edit Post</title>
</head>
<body>
    <h2>Edit Post</h2>
    <form method="POST" action="">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>
        <label>Content:</label><br>
        <textarea name="content" rows="6" cols="50" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>
        <input type="submit" name="update" value="Update Post">
    </form>
    <br>
    <a href="index.php">← Back to Posts</a>
</body>
</html>