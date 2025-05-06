<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get user's role
$role = '';
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSql = '';
$params = [];
$types = '';

if (!empty($search)) {
    $searchSql = "WHERE title LIKE ? OR content LIKE ?";
    $searchTerm = '%' . $search . '%';
    $params = [$searchTerm, $searchTerm];
    $types = 'ss';
}

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Count total posts
$countQuery = "SELECT COUNT(*) AS total FROM posts " . ($searchSql ? $searchSql : "");
$countStmt = $conn->prepare($countQuery);
if ($searchSql) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalPosts = $countResult->fetch_assoc()['total'];
$countStmt->close();
$totalPages = ceil($totalPosts / $limit);

// Fetch posts
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ";
if ($searchSql) $sql .= $searchSql . " ";
$sql .= "ORDER BY created_at DESC LIMIT ?, ?";
$fetchStmt = $conn->prepare($sql);

// Bind parameters with limit/offset
if ($searchSql) {
    $types .= 'ii';
    $params[] = $offset;
    $params[] = $limit;
    $fetchStmt->bind_param($types, ...$params);
} else {
    $fetchStmt->bind_param("ii", $offset, $limit);
}

$fetchStmt->execute();
$result = $fetchStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #8e2de2, #ff6ec4);
            color: #fff;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        h2, h3 {
            color: #ffffff;
        }

        a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .post a {
            color: black;
            font-weight: normal;
        }

        .post a:hover {
            text-decoration: underline;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 60%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 14px;
            background-color: #ffffff;
            border: none;
            color: #4a00e0;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e0d3f7;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            padding: 6px 12px;
            margin: 0 3px;
            background: #e0e0e0;
            color: #333;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #ffffff;
            color: #4a00e0;
            font-weight: bold;
        }

        .post {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>

<h2>👋 Welcome, <?= htmlspecialchars($username) ?>!</h2>
<p>
    <a href="create_post.php">✍️ Create New Post</a> |
    <a href="logout.php">🚪 Logout</a>
</p>

<!-- Search Form -->
<form method="GET" action="index.php">
    <input type="text" name="search" placeholder="🔍 Search posts..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<h3>🗂️ All Posts</h3>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post">
            <h4><?= htmlspecialchars($row['title']) ?></h4>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
            <small>📅 <?= $row['created_at'] ?> | 👤 <?= htmlspecialchars($row['username']) ?></small><br>
            <?php if ($role === 'admin' || $row['user_id'] == $user_id): ?>
                <a href="edit_post.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                <a href="delete_post.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this post?');">🗑️ Delete</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<!-- Pagination Links -->
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): 
        $link = "index.php?page=$i";
        if (!empty($search)) $link .= "&search=" . urlencode($search);
    ?>
        <a href="<?= $link ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

</body>
</html>