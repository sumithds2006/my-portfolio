<?php
require_once "auth.php"; require_once "db.php";
$id=intval($_GET['id']??0);
if($id<1){header("Location: index.php");exit;}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['confirm_delete'])){
$stmt=$conn->prepare("DELETE FROM recipes WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
header("Location: index.php");exit;
}
$stmt=$conn->prepare("SELECT recipe_name FROM recipes WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute(); $recipe=$stmt->get_result()->fetch_assoc();
if(!$recipe){die("Recipe not found. <a href='index.php'>Go home</a>");}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Delete Recipe</title><link rel="stylesheet" href="style.css"></head><body><header><h1>Delete Recipe</h1><a href="index.php">Home</a></header>
<div class="container"><h2>Confirm deletion</h2><p>Are you sure you want to delete <strong><?php echo htmlspecialchars($recipe['recipe_name']); ?></strong>?</p>
<form method="post"><button type="submit" name="confirm_delete" value="1" class="danger">Yes, Delete</button> <a href="index.php">Cancel</a></form></div></body></html>