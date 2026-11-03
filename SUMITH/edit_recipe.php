<?php
require_once "auth.php"; require_once "db.php";
$id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM recipes WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute(); $recipe=$stmt->get_result()->fetch_assoc();
if (!$recipe) { die("Recipe not found. <a href='index.php'>Go home</a>"); }
$message="";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
$name=trim($_POST['recipe_name']??""); $category_id=intval($_POST['category_id']??0);
$ingredients=trim($_POST['ingredients']??""); $instructions=trim($_POST['instructions']??""); $time=intval($_POST['cooking_time']??0);
if($name===""||$ingredients===""||$instructions===""||$time<0||$time>1440){$message="Please enter valid recipe details.";}
else {
$up=$conn->prepare("UPDATE recipes SET recipe_name=?, category_id=?, ingredients=?, instructions=?, cooking_time=? WHERE id=?");
$up->bind_param("sissii",$name,$category_id,$ingredients,$instructions,$time,$id);
if($up->execute()){header("Location: index.php");exit;} else {$message="Unable to update recipe.";}
}
}
$categories=$conn->query("SELECT * FROM categories ORDER BY category_name");
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Edit Recipe</title><link rel="stylesheet" href="style.css"></head><body><header><h1>Edit Recipe</h1><a href="index.php">Home</a><a href="logout.php">Logout</a></header>
<div class="container"><h2>Update Recipe Details</h2><p class="error"><?php echo htmlspecialchars($message); ?></p>
<form method="post"><input type="hidden" name="id" value="<?php echo $id; ?>">
<label>Recipe Name</label><input name="recipe_name" maxlength="150" required value="<?php echo htmlspecialchars($recipe['recipe_name']); ?>">
<label>Food Category</label><select name="category_id" required><?php while($c=$categories->fetch_assoc()){ ?><option value="<?php echo intval($c['id']); ?>" <?php if($recipe['category_id']==$c['id']) echo "selected"; ?>><?php echo htmlspecialchars($c['category_name']); ?></option><?php } ?></select>
<label>Ingredients and Quantities</label><textarea name="ingredients" rows="6" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
<label>Cooking Instructions</label><textarea name="instructions" rows="8" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
<label>Cooking Time (minutes)</label><input type="number" name="cooking_time" min="0" max="1440" required value="<?php echo intval($recipe['cooking_time']); ?>"><button type="submit">Update Recipe</button></form></div></body></html>