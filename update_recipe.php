<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');

$id = $_POST['id'];
$title = $_POST['title'];
$ingredients = $_POST['ingredients'];
$instructions = $_POST['instructions'];

$image_url = "";
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $upload_dir = 'uploads/';
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_url = $upload_dir . basename($image_name);
    
    if (!move_uploaded_file($image_tmp_name, $image_url)) {
        echo "Terjadi kesalahan saat mengupload gambar!";
        exit();
    }
}

if ($image_url == "") {
    $sql = "UPDATE recipes SET title = ?, ingredients = ?, instructions = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $ingredients, $instructions, $id);
} else {
    $sql = "UPDATE recipes SET title = ?, ingredients = ?, instructions = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $ingredients, $instructions, $image_url, $id);
}

$stmt->execute();

echo "Resep berhasil diperbarui!";
header("Location: home.php"); 

$stmt->close();
$conn->close();
?>
