<?php
session_start();
include "db.php";
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$sql = "SELECT recipes.*, categories.category_name FROM recipes LEFT JOIN categories ON recipes.category_id = categories.id WHERE recipes.recipe_name LIKE ?";
if ($category > 0) $sql .= " AND recipes.category_id = ?";
$sql .= " ORDER BY recipes.id DESC";
$stmt = $conn->prepare($sql);
$term = "%" . $search . "%";
if ($category > 0) $stmt->bind_param("si", $term, $category);
else $stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();
$cats = $conn->query("SELECT * FROM categories ORDER BY category_name");
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Recipe Book Hub</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>Recipe Book Hub</h1><p>Discover delicious recipes for every day.</p>
<a href="index.php">Home</a><a href="add_recipe.php">Add Recipe</a><a href="categories.php">Categories</a>
<?php if(isset($_SESSION['user_id'])) { ?><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a><?php } else { ?><a href="login.php">Admin Login</a><?php } ?></header>
<div class="container"><h2>Explore Recipes</h2>
<form method="get" action="index.php"><input type="text" name="search" placeholder="Search recipe name..." value="<?php echo htmlspecialchars($search); ?>">
<select name="category"><option value="0">All Categories</option><?php while($c=$cats->fetch_assoc()){ ?><option value="<?php echo intval($c['id']); ?>" <?php if($category==$c['id']) echo "selected"; ?>><?php echo htmlspecialchars($c['category_name']); ?></option><?php } ?></select><button type="submit">Search</button></form>
<p><a href="add_recipe.php">+ Add a New Recipe</a></p><div class="recipes">
<?php if($result->num_rows>0){ while($r=$result->fetch_assoc()){ ?><div class="recipe-card"><h3><?php echo htmlspecialchars($r['recipe_name']); ?></h3>
<p>Category: <?php echo htmlspecialchars($r['category_name'] ?? 'Uncategorized'); ?></p><p>Cooking time: <?php echo intval($r['cooking_time']); ?> minutes</p>
<a href="view_recipe.php?id=<?php echo intval($r['id']); ?>">View Recipe</a>
<?php if(isset($_SESSION['user_id'])){ ?> | <a href="edit_recipe.php?id=<?php echo intval($r['id']); ?>">Edit</a> | <a href="delete_recipe.php?id=<?php echo intval($r['id']); ?>" onclick="return confirm('Delete this recipe?');">Delete</a><?php } ?></div><?php }} else { ?><p>No recipes found. Try another search.</p><?php } ?></div></div></body></html>