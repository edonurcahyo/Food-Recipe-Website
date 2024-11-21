<?php
$conn = new mysqli('localhost', 'root', '', 'food_recipe');

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$searchTerm = htmlspecialchars($searchTerm);

$sql = "SELECT * FROM recipes WHERE title LIKE ? OR ingredients LIKE ?";
$stmt = $conn->prepare($sql);
$searchTermWildcard = "%$searchTerm%"; 
$stmt->bind_param("ss", $searchTermWildcard, $searchTermWildcard);
$stmt->execute();
$result = $stmt->get_result();

$recommendationsSql = "SELECT * FROM recipes ORDER BY RAND() LIMIT 3";
$recommendationsResult = $conn->query($recommendationsSql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Resep</title>
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="shortcut icon" href="/image/icon.png" type="image/x-icon">
</head>
<body>
    <div class="topnav" id="myTopnav">
        <a href="/home.php">Home</a>
        <a href="/search.php" class="active">Pencarian Resep</a>
        <a href="bookmark.php">Bookmark</a>
        <a href="/logout.php">Logout</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>

    <div class="container">
        <div class="search-container">
            <h1>Cari Resep</h1>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Cari resep..." value="<?= htmlspecialchars($searchTerm) ?>" class="search-input">
                <button type="submit" class="search-btn">Cari</button>
            </form>
        </div>

        <?php if (!empty($searchTerm) && $result->num_rows > 0): ?>
            <h2 class="search-results-heading">Hasil Pencarian</h2>
            <div class="recipe-list">
                <?php while ($recipe = $result->fetch_assoc()): ?>
                    <div class="recipe-card">
                        <?php if (!empty($recipe['image_url'])): ?>
                            <img src="<?= htmlspecialchars($recipe['image_url']) ?>" alt="Gambar <?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
                        <?php endif; ?>
                        <h3><a href="recipe_detail.php?id=<?= $recipe['id'] ?>"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                        <p><?= substr($recipe['description'], 0, 100) ?>...</p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php elseif (!empty($searchTerm)): ?>
            <h2 class="search-results-heading">Tidak ada resep yang ditemukan.</h2>
        <?php endif; ?>

        <?php if (empty($searchTerm)): ?>
            <div class="recommendations">
                <h2 class="search-results-heading">Rekomendasi Resep</h2>
                <div class="recipe-list">
                    <?php while ($recipe = $recommendationsResult->fetch_assoc()): ?>
                        <div class="recipe-card">
                            <?php if (!empty($recipe['image_url'])): ?>
                                <img src="<?= htmlspecialchars($recipe['image_url']) ?>" alt="Gambar <?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
                            <?php endif; ?>
                            <h3><a href="recipe_detail.php?id=<?= $recipe['id'] ?>"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                            <p><?= substr($recipe['description'], 0, 100) ?>...</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
