
<?php
require_once "db.php";

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $birth_date = $_POST["birth_date"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (
        $first_name === "" ||
        $last_name === "" ||
        $username === "" ||
        $birth_date === "" ||
        $email === "" ||
        $password === "" ||
        $confirm_password === ""
    ) {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        try {
            $check = $pdo->prepare(
                "SELECT id FROM user WHERE username = ? OR email = ?"
            );

            $check->execute([$username, $email]);

            if ($check->fetch()) {
                $message = "Username or email already exists.";
            } else {
                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $sql = "INSERT INTO user
                    (first_name, last_name, username, birth_date, email, password)
                    VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    $first_name,
                    $last_name,
                    $username,
                    $birth_date,
                    $email,
                    $hashed_password
                ]);

                $message = "Account created successfully!";
                $success = true;
            }
      } catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage();
}   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="/welcome_site/style.css?v=3">
</head>
<body>

<div class="container">
    <h1>Create Account</h1>

    <?php if ($message !== ""): ?>
        <p role="status">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form action="register.php" method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="date" name="birth_date" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Create Account</button>
        </form>
    <?php endif; ?>

    <p>
        <a href="login.php">Already have an account? Login</a>
    </p>
</div>

</body>
</html>
