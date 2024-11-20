<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$user_id = $_SESSION['user_id'];
$recipe_id = $_POST['recipe_id'];

// Hapus resep dari bookmark
$sql = "DELETE FROM bookmarks WHERE user_id = ? AND recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();

// Redirect kembali ke halaman bookmark
header("Location: bookmark.php");
exit();
?>
