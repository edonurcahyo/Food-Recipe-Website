<?php
session_start();
include 'Food-Recipe-Website\config\conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name'];

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'food_recipe');
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Mengecek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email sudah terdaftar. Silakan coba email lain.";
    } else {
        // Menyimpan data pengguna baru
        $stmt = $conn->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password, $name);
        $stmt->execute();
        
        echo "Pendaftaran berhasil! <a href='login.php'>Login sekarang</a>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Daftar Akun</h2>
        <form method="POST" action="">
            <label>Email:</label><input type="email" name="email" required><br>
            <label>Nama:</label><input type="text" name="name" required><br>
            <label>Kata Sandi:</label><input type="password" name="password" required><br>
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
