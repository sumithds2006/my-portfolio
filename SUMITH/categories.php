<?php
require_once "auth.php";
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["category_name"]);

    if ($name != "") {
        $stmt = $conn->prepare(
            "INSERT INTO categories (category_name)
             VALUES (?)"
        );

        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            header("Location: categories.php");
            exit;
        } else {
            $message = "Could not add category.";
        }
    } else {
        $message = "Enter a category name.";
    }
}

$result = $conn->query(
    "SELECT * FROM categories ORDER BY category_name"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Food Categories</h1>
    <a href="index.php">Home</a>
    <a href="add_recipe.php">Add Recipe</a>
</header>

<div class="container">
    <h2>Add Category</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="post" action="categories.php">
        <label>Category Name</label>
        <input type="text" name="category_name"
            maxlength="100" required>
        <button type="submit">Add Category</button>
    </form>

    <h2>Available Categories</h2>

    <ul>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <li><?php echo htmlspecialchars(
            $row['category_name']); ?></li>
    <?php } ?>
    </ul>
</div>

</body>
</html>