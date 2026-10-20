
<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "Please enter your username and password.";
    } else {
        try {
            $stmt = $pdo->prepare(
                "SELECT id, first_name, username, password
                 FROM user
                 WHERE username = ?"
            );

            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user["password"])) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["first_name"] = $user["first_name"];
                $_SESSION["username"] = $user["username"];

                header("Location: home.php");
                exit;
            } else {
                $message = "Incorrect username or password.";
            }
        } catch (PDOException $e) {
            $message = "Unable to log in. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Welcome Website</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        .login-form input {
            width: 100%;
            padding: 14px;
            border: 1px solid #edb1ca;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.85);
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
        }

        .login-form input:focus {
            border-color: #d63384;
            box-shadow: 0 0 8px rgba(214, 51, 132, 0.2);
        }

        .login-form button {
            padding: 14px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #ff70a6, #d63384);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-form button:hover {
            opacity: 0.9;
        }

        .back-link {
            display: inline-block;
            margin-top: 22px;
            color: #b52f70;
            text-decoration: none;
        }

        .login-message {
            color: #b52f70;
            margin: 12px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome Back! ♡</h1>
        <p>Login to continue your journey.</p>

        <?php if ($message !== ""): ?>
            <p class="login-message" role="alert">
                <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <form class="login-form" action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Enter your password" required>

            <button type="submit">Login ♡</button>
        </form>

        <p style="margin-top: 22px; margin-bottom: 0;">
            Don't have an account?
            <a href="register.php" style="color: #b52f70;">Create Account</a>
        </p>

        <a href="index.html" class="back-link">← Back to Welcome</a>
    </div>
</body>
</html>