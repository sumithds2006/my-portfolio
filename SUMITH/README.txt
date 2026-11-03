RECIPE BOOK HUB - PHP / XAMPP / HeidiSQL / Dreamweaver 8

1. Copy this folder to C:\xampp\htdocs\recipe_book_hub (or your htdocs folder).
2. Check db.php. It currently uses host localhost:3308, username root, password root, database food.
   Change these values if your MySQL settings differ.
3. In HeidiSQL, select database food and make sure these tables exist:
   categories(id, category_name)
   recipes(id, recipe_name, category_id, ingredients, instructions, cooking_time)
   users(id, username, password)
   This project intentionally uses a simple database without foreign keys.
4. If users table doesn't exist, run:
   CREATE TABLE users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(100) NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL
   );
5. Create a login password hash by running this temporary PHP snippet from a PHP page:
   <?php echo password_hash('admin123', PASSWORD_DEFAULT); ?>
   Copy the output hash, then run in HeidiSQL:
   INSERT INTO users (username,password) VALUES ('admin','PASTE_HASH_HERE');
   You may also store a plain password for this mini-project; login accepts both plain and hashed passwords.
6. Open http://localhost/recipe_book_hub/index.php
7. Login at http://localhost/recipe_book_hub/login.php using admin and the password you set.
8. Logged-in admin can add recipes, edit recipes, and delete recipes. Edit/Delete links appear on the home page after login.
9. Ensure your existing add_recipe.php includes auth.php at the top to protect recipe additions. To protect categories too, add require_once "auth.php"; after opening PHP.
