<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['username'])) {
    echo "🚫 Access denied. <a href='login.php'>Login</a>";
    exit();
}

// Get user data
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "❌ User not found.";
    exit();
}

$user_id = $user['id'];
$role = $user['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        echo "❗ Both title and content are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $user_id);

        if ($stmt->execute()) {
            echo "✅ Post created successfully! <a href='index.php'>View all posts</a>";
        } else {
            echo "❌ Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right,rgba(31, 160, 196, 0.85),rgba(27, 23, 21, 0.79));
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            color: #333;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color:rgba(63, 20, 182, 0.94);
        }

        label {
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #0b5394;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #073763;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #0b5394;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #0b5394;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="logo">📝 MiniBlog</div>
    <h2>Create New Blog Post</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" required></textarea><br>

        <input type="submit" value="Post">
    </form>

    <a href="index.php">← Back to Posts</a>
</body>
</html>