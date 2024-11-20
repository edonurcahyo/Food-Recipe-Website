<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];

    // Proses upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "Gambar berhasil di-upload.";
            } else {
                echo "Terjadi kesalahan saat mengupload gambar.";
            }
        } else {
            echo "Hanya file gambar yang diizinkan.";
        }
    } else {
        $target_file = null; 
    }

    $conn = new mysqli('localhost', 'root', '', 'food_recipe');

    // Simpan data resep ke database
    $stmt = $conn->prepare("INSERT INTO recipes (title, description, ingredients, instructions, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $ingredients, $instructions, $target_file);
    $stmt->execute();

    echo "Resep berhasil ditambahkan!";
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep</title>
    <link rel="stylesheet" href="/css/add.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="form-container">
        <h1>Tambah Resep Baru</h1>
        <form method="POST" enctype="multipart/form-data" class="recipe-form">
            <div class="form-group">
                <label for="title">Judul Resep</label>
                <input type="text" id="title" name="title" placeholder="Masukkan judul resep" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Resep</label>
                <textarea id="description" name="description" placeholder="Masukkan deskripsi resep" required></textarea>
            </div>

            <div class="form-group">
                <label for="ingredients">Bahan</label>
                <textarea id="ingredients" name="ingredients" placeholder="Masukkan bahan-bahan resep" required></textarea>
            </div>

            <div class="form-group">
                <label for="instructions">Instruksi</label>
                <textarea id="instructions" name="instructions" placeholder="Masukkan instruksi langkah demi langkah" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Pilih Gambar</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">Tambah Resep</button>
        </form>
    </div>
</body>
</html>
