<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $conn = new mysqli('localhost', 'root', '', 'food_recipe');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Resep berhasil dihapus!";
}
?>
