<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'food_recipe');
$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama - Daftar Resep</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <div class="topnav" id="myTopnav">
        <a href="/home.php" class="active">Home</a>
        <a href="/search.php">Pencarian Resep</a>
        <a href="/logout.php">Logout</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
        </a>
    </div>
    <div class="container">
        <h1 class="title">Daftar Resep</h1>

        <div class="add-recipe-btn">
            <a href="add_recipe.php" class="btn-add">Tambah Resep Baru</a>
        </div>
        <div id="recipe-list">
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
                    <button onclick="deleteRecipe(<?php echo $recipe['id']; ?>)" class="btn btn-delete">Hapus</button>
                    <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-edit">Edit</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function deleteRecipe(id) {
            if (confirm("Yakin ingin menghapus resep ini?")) {
                fetch(`delete.php?id=${id}`)
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload(); 
                    });
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
