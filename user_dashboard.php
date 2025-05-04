<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied.";
    exit;
}

// User dashboard content
echo "Welcome to your User Dashboard!";
?>