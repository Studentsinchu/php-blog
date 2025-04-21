<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db.php';

// If no ID is set, show error
if (!isset($_GET['id'])) {
    echo "Post not found!";
    exit();
}

$post_id = $_GET['id'];

// Fetch post data
$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    echo "Post not found!";
    exit();
}

$post = mysqli_fetch_assoc($result);

// Update post on form submit
if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $update_sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $post_id";

    if (mysqli_query($conn, $update_sql)) {
        header('Location: index.php');
    } else {
        echo "Error updating post: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
</head>
<body>

<h2>Edit Post</h2>
<form method="POST" action="">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="8" cols="50" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>

    <button type="submit" name="update">Update Post</button>
</form>

</body>
</html>