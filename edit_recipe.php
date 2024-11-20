<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');

$id = $_GET['id'];

$sql = "SELECT * FROM recipes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Resep tidak ditemukan!";
    exit();
}

$recipe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep</title>
    <link rel="stylesheet" href="css/edit.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="shortcut icon" href="/image/icon.png" type="image/x-icon">
</head>
<body>
<div class="topnav" id="myTopnav">
        <a href="/home.php">Home</a>
        <a href="/search.php">Pencarian Resep</a>
        <a href="bookmark.php">Bookmark</a>
        <a href="/logout.php">Logout</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
        </a>
    </div>

    <div class="container">
        <h1 class="title">Edit Resep</h1>
        <form action="update_recipe.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">

            <label for="title">Judul Resep</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>

            <label for="description">Deskripsi</label> 
            <textarea id="description" name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>

            <label for="ingredients">Bahan</label>
            <textarea id="ingredients" name="ingredients" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>

            <label for="instructions">Instruksi</label>
            <textarea id="instructions" name="instructions" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>

            <label for="image">Gambar (optional)</label>
            <input type="file" name="image" id="image">

            <?php if (!empty($recipe['image_url'])) { ?>
                <div class="current-image">
                    <p>Gambar saat ini:</p>
                    <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="Gambar Resep" class="recipe-image">
                </div>
            <?php } ?>
            
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
