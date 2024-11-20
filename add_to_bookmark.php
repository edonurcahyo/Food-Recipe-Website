<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$user_id = $_SESSION['user_id'];
$recipe_id = $_POST['recipe_id'];

$sql = "SELECT * FROM bookmarks WHERE user_id = ? AND recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    
    $insert_sql = "INSERT INTO bookmarks (user_id, recipe_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $user_id, $recipe_id);
    $insert_stmt->execute();
    echo "Resep berhasil ditambahkan ke bookmark!";
} else {
    echo "Resep sudah ada di bookmark.";
}

header("Location: home.php");
exit();
