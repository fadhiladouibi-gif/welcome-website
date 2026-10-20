
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$first_name = $_SESSION["first_name"] ?? "User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Welcome Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($first_name, ENT_QUOTES, "UTF-8") ?>! ♡</h1>
        <p>You have logged in successfully.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>