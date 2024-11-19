<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'recipe_app');

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Mencari pengguna berdasarkan email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header("Location: home.php");
    } else {
        echo "Email atau kata sandi salah!";
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
    <title>Login Pengguna</title>
    <link rel="stylesheet" href="/Food-Recipe-Website/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label>Email:</label><input type="email" name="email" required><br>
            <label>Kata Sandi:</label><input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
