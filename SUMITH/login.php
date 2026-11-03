<?php
session_start();
require_once "db.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

if (isset($_GET['registered'])) {
    $success = "Registration successful! Please login.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, username, password FROM users WHERE username = ? LIMIT 1"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $stmt->bind_result($id, $db_username, $db_password);

    if ($stmt->fetch()) {

        if ($password === $db_password) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;

            $stmt->close();

            header("Location: index.php");
            exit;

        } else {

            $error = "Invalid username or password.";
        }

    } else {

        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Recipe Book Hub</h1>
    <a href="index.php">Home</a>
</header>

<div class="container login-box">

    <h2>User Login</h2>

    <?php if ($success) { ?>
        <p class="success">
            <?php echo htmlspecialchars($success); ?>
        </p>
    <?php } ?>

    <?php if ($error) { ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <form method="post">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register Here</a>
    </p>

</div>

</body>
</html>