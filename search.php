<?php
$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// SQL Query untuk pencarian resep berdasarkan title atau ingredients
$sql = "SELECT * FROM recipes WHERE title LIKE ? OR ingredients LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$searchTerm%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Resep</title>
    <link rel="stylesheet" href="css/search.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="search.php">Pencarian Resep</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Form Pencarian -->
    <div class="search-container">
        <h1>Cari Resep</h1>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Cari resep..." value="<?= htmlspecialchars($searchTerm) ?>" class="search-input">
            <button type="submit" class="search-btn">Cari</button>
        </form>
    </div>

    <!-- Hasil Pencarian -->
    <div class="recipe-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($recipe = $result->fetch_assoc()): ?>
                <div class="recipe-card">
                    <h3><a href="recipe_detail.php?id=<?= $recipe['id'] ?>"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                    <p><?= substr($recipe['description'], 0, 100) ?>...</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada resep yang ditemukan.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php $conn->close(); ?>
