<?php
$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$id = $_GET['id'];

$sql = "SELECT * FROM recipes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Resep</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="search.php">Pencarian Resep</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <?php if ($recipe) : ?>
            <h1><?= htmlspecialchars($recipe['title']) ?></h1>
            <?php if (!empty($recipe['image'])) : ?>
                <img src="uploads/<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
            <?php endif; ?>
            <div class="recipe-section">
                <p><strong>Bahan:</strong><br><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
            </div>
            <div class="recipe-section">
                <p><strong>Instruksi:</strong><br><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>
            </div>
        <?php else : ?>
            <p>Resep tidak ditemukan.</p>
        <?php endif; ?>
        <a href="home.php" class="back-link">Kembali ke Daftar Resep</a>
    </div>
</body>
</html>
