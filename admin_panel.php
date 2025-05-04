<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

// Handle delete user
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    if ($delete_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
    header("Location: admin_panel.php");
    exit;
}

// Handle role update
if (isset($_GET['promote']) || isset($_GET['demote'])) {
    $user_id = $_GET['promote'] ?? $_GET['demote'];
    $new_role = isset($_GET['promote']) ? 'admin' : 'user';

    if ($user_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        $stmt->execute();
    }
    header("Location: admin_panel.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<h2>Admin Panel</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th>
    </tr>
    <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['role'] ?></td>
            <td>
                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                    <?php if ($row['role'] === 'user'): ?>
                        <a href="?promote=<?= $row['id'] ?>">Promote to Admin</a> |
                    <?php else: ?>
                        <a href="?demote=<?= $row['id'] ?>">Demote to User</a> |
                    <?php endif; ?>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
                <?php else: ?>
                    (You)
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
