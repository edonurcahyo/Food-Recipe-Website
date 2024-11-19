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

    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $stmt = $conn->prepare("INSERT INTO recipes (title, description) VALUES (?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $title, $description);
    $stmt->execute();

    if ($stmt->error) {
        die("Error executing statement: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    header("Location: home.php");
    exit();
}
?>
