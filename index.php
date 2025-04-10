<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "php-blog");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all posts
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Blog - Home</title>
</head>
<body>
    <h1>PHP Blog</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong> |
            <a href="create_post.php">Create New Post</a> |
            <a href="logout.php">Logout</a>
        </p>
    <?php else: ?>
        <p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>
    <?php endif; ?>

    <hr>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
            <div style="margin-bottom: 25px;">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p><small>Posted by <strong><?php echo htmlspecialchars($post['username']); ?></strong> on <?php echo $post['created_at']; ?></small></p>
                
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                    <p>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a> |
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                    </p>
                <?php endif; ?>
                <hr>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts yet. Be the first to <a href="create_post.php">write one</a>!</p>
    <?php endif; ?>
</body>
</html>