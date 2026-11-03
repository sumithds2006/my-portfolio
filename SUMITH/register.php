<?php
session_start();
require_once "db.php";

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password != $confirm_password) {

        $error = "Passwords do not match.";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $error = "Username already exists.";

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO users (username, password) VALUES (?, ?)"
            );

            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {

                header("Location: login.php?registered=1");
                exit;

            } else {

                $error = "Registration failed.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>Recipe Book Hub</h1>
    <a href="index.php">Home</a>
</header>

<div class="container login-box">

    <h2>Create Account</h2>

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

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login Here</a>
    </p>

</div>

</body>
</html>