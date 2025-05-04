<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $content, $id]);

    header("Location: view_post.php");
    exit;
}

// Fetch the post to be edited
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

?>
<form method="POST" action="edit_post.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>"><br>
    <label>Content:</label><br>
    <textarea name="content"><?php echo htmlspecialchars($post['content']); ?></textarea><br>
    <button type="submit">Update Post</button>
</form>