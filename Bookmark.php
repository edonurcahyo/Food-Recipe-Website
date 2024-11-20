<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$user_id = $_SESSION['user_id'];

// Ambil daftar bookmark pengguna dari database
$sql = "SELECT r.* FROM recipes r JOIN bookmarks b ON r.id = b.recipe_id WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Bookmark</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="bookmark.php">Bookmark</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1 class="title">Resep yang Ditandai</h1>
        <div id="bookmark-list">
            <?php while ($recipe = $result->fetch_assoc()) { ?>
                <div class="recipe-card">
                    <?php if (!empty($recipe['image_url'])) { ?>
                        <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="Gambar <?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">
                    <?php } else { ?>
                        <p>No image available</p>
                    <?php } ?>
                    <h2>
                        <a href="recipe_detail.php?id=<?php echo $recipe['id']; ?>"><?php echo htmlspecialchars($recipe['title']); ?></a>
                    </h2>
                    <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                    <!-- Form untuk menghapus resep dari bookmark -->
                    <form action="remove_from_bookmark.php" method="POST">
                        <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
                        <button type="submit" class="btn btn-delete">Hapus dari Bookmark</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
