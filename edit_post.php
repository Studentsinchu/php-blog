<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid post ID.";
    exit;
}

$post_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Get user role
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$role = $user['role'] ?? '';

// Check post ownership for editors
if ($role === 'editor') {
    $stmt = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        echo "Access denied. Editors can only edit their own posts.";
        exit;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        echo "All fields are required.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $post_id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// Get current post data
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($title, $content);
if (!$stmt->fetch()) {
    echo "Post not found.";
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right,rgba(20, 14, 26, 0.77),rgba(27, 117, 207, 0.79));
            padding: 40px;
            text-align: center;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            display: inline-block;
            width: 60%;
        }

        h2 {
            color: #6a1b9a;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4a0072;
        }

        a {
            color: #0077cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Edit Post</h2>

        <form method="POST">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>

            <label>Content:</label>
            <textarea name="content" rows="6" required><?= htmlspecialchars($content) ?></textarea>

            <button type="submit">Update</button>
        </form>

        <br>
        <a href="index.php">← Back to Home</a>
    </div>
</body>
</html>