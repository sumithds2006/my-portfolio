<?php
require_once "auth.php";
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["recipe_name"]);
    $category_id = intval($_POST["category_id"]);
    $ingredients = trim($_POST["ingredients"]);
    $instructions = trim($_POST["instructions"]);
    $time = intval($_POST["cooking_time"]);

    if ($name == "" || $ingredients == "" ||
        $instructions == "" || $time < 0) {
        $message = "Please enter valid recipe details.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO recipes
            (recipe_name, category_id, ingredients,
             instructions, cooking_time)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sissi",
            $name,
            $category_id,
            $ingredients,
            $instructions,
            $time
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $message = "Unable to save recipe.";
        }
    }
}

$categories = $conn->query(
    "SELECT * FROM categories ORDER BY category_name"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Recipe - Recipe Book Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Add a New Recipe</h1>
    <a href="index.php">Home</a>
    <a href="categories.php">Categories</a>
</header>

<div class="container">
    <h2>Recipe Details</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="post" action="add_recipe.php">
        <label>Recipe Name</label>
        <input type="text" name="recipe_name"
            maxlength="150" required>

        <label>Food Category</label>
        <select name="category_id" required>
            <?php while ($c = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $c['id']; ?>">
                    <?php echo htmlspecialchars(
                        $c['category_name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Ingredients and Quantities</label>
        <textarea name="ingredients" rows="6"
            placeholder="2 tomatoes, 1 onion..."
            required></textarea>

        <label>Cooking Instructions</label>
        <textarea name="instructions" rows="8"
            placeholder="1. Wash the vegetables..."
            required></textarea>

        <label>Cooking Time (minutes)</label>
        <input type="number" name="cooking_time"
            min="0" max="1440" required>

        <button type="submit">Save Recipe</button>
    </form>
</div>

</body>
</html>