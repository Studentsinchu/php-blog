<?php
session_start();
include 'config/db.php'; // Include your database connection

// Fetch posts from the database
$stmt = $pdo->query("SELECT posts.id, posts.title, posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($posts)) {
    echo "<p>No posts available.</p>";
} else {
    foreach ($posts as $post) {
        echo "<h2>" . htmlspecialchars($post['title']) . "</h2>";
        echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
        echo "<p><em>Posted by " . htmlspecialchars($post['username']) . " on " . $post['created_at'] . "</em></p>";
        
        // Edit and Delete Links
        echo "<p>";
        echo "<a href='edit_post.php?id=" . htmlspecialchars($post['id']) . "'>Edit</a> | ";
        echo "<a href='delete_post.php?id=" . htmlspecialchars($post['id']) . "' onclick='return confirm(\"Are you sure you want to delete this post?\");'>Delete</a>";
        echo "</p>";
        
        echo "<hr>";
    }
}
?>