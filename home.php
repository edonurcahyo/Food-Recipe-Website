<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);

echo "<h1>Daftar Resep</h1>";

while ($recipe = $result->fetch_assoc()) {
    echo "<h2><a href='recipe_detail.php?id={$recipe['id']}'>{$recipe['title']}</a></h2>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link rel="stylesheet" href="/Food-Recipe-Website/css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="search.php">Pencarian Resep</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
