<?php
session_start(); require_once "db.php";
$id=intval($_GET['id']??0);
$stmt=$conn->prepare("SELECT recipes.*, categories.category_name FROM recipes LEFT JOIN categories ON recipes.category_id=categories.id WHERE recipes.id=?");
$stmt->bind_param("i",$id);$stmt->execute();$r=$stmt->get_result()->fetch_assoc();
if(!$r){die("Recipe not found. <a href='index.php'>Go home</a>");}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title><?php echo htmlspecialchars($r['recipe_name']); ?></title><link rel="stylesheet" href="style.css"></head><body>
<header><h1><?php echo htmlspecialchars($r['recipe_name']); ?></h1><a href="index.php">Home</a><?php if(isset($_SESSION['user_id'])){ ?><a href="edit_recipe.php?id=<?php echo $id; ?>">Edit Recipe</a><?php } ?></header>
<div class="container"><p><strong>Category:</strong> <?php echo htmlspecialchars($r['category_name']??'Uncategorized'); ?></p><p><strong>Cooking time:</strong> <?php echo intval($r['cooking_time']); ?> minutes</p>
<h2>Ingredients</h2><div class="recipe-text"><?php echo htmlspecialchars($r['ingredients']); ?></div><h2>Cooking Instructions</h2><div class="recipe-text"><?php echo htmlspecialchars($r['instructions']); ?></div></div></body></html>