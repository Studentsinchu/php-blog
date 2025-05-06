<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
    <meta http-equiv="refresh" content="2;url=home.php">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right,rgba(39, 194, 194, 0.79), #ff6ec4); /* Matching login page */
            color: #fff;
            text-align: center;
            padding-top: 100px;
        }

        .message {
            display: inline-block;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .message h2 {
            color: #4a00e0;
        }

        .message p {
            margin-top: 10px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="message">
    <h2>You've been logged out.</h2>
    <p>Redirecting to the homepage...</p>
</div>

</body>
</html>