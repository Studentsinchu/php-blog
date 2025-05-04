<?php
session_start();
require_once 'config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch posts (with user join)
$stmt = $conn->prepare("SELECT posts.id, posts.title, posts.content, posts.created_at FROM posts ORDER BY posts.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Blog Posts</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        table {
            margin: 30px auto;
            border-collapse: collapse;
            width: 90%;
        }
        th, td {
            border: 1px solid #999;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        a {
            text-decoration: none;
        }
        .nav {
            margin-top: 20px;
        }
        .nav a {
            margin: 0 10px;
        }
    </style>
</head>
<body>

<div class="nav">
    <a href="create_post.php" style="color:purple; font-weight:bold;">+ Create New Post</a> |
    <a href="logout.php" style="color:red; font-weight:bold;">Logout</a>
</div>

<h2>All Blog Posts</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars(substr($row['content'], 0, 50)) ?>...</td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
                <a href="view_post.php?id=<?= $row['id'] ?>">View</a> |
                <a href="edit_post.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete_post.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
