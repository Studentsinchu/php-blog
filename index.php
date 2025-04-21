<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db.php';

// Fetch all posts from database
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post List</title>
</head>
<body>
    <h2>All Posts</h2>

    <a href="create_post.php">+ Create New Post</a>
    <a href="logout.php" style="float:right;">Logout</a>

    <table border="1" cellpadding="10" cellspacing="0" style="margin-top:10px;">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Actions</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td>
                        <a href="view_post.php?id=<?php echo $row['id']; ?>">View</a> |
                        <a href="edit_post.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="delete_post.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No posts found.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>