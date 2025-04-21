<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "php-blog";  // Make sure this is your exact database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>