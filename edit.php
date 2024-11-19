<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'food_recipe');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $stmt = $conn->prepare("UPDATE recipes SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: home.php");
}
?>
