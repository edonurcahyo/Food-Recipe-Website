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
    <script>
        function deleteRecipe(id) {
            if (confirm("Yakin ingin menghapus resep ini?")) {
                fetch(`delete.php?id=${id}`)
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload(); // Refresh halaman
                    });
            }
        }

        function editRecipe(id, title, description) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-form').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="search.php">Pencarian Resep</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
        <h1 class="title">Daftar Resep</h1>
        <div id="recipe-list">
            <?php while ($recipe = $result->fetch_assoc()) { ?>
                <div class="recipe-card">
                    <h2>
                        <a href="recipe_detail.php?id=<?php echo $recipe['id']; ?>"><?php echo htmlspecialchars($recipe['title']); ?></a>
                    </h2>
                    <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                    <button onclick="deleteRecipe(<?php echo $recipe['id']; ?>)">Hapus</button>
                    <button onclick="editRecipe(<?php echo $recipe['id']; ?>, '<?php echo addslashes($recipe['title']); ?>', '<?php echo addslashes($recipe['description']); ?>')">Edit</button>
                </div>
            <?php } ?>
        </div>

        <!-- Form Tambah Resep -->
        <div id="add-form">
            <h3>Tambah Resep</h3>
            <form method="POST" action="add.php">
                <input type="text" name="title" placeholder="Judul Resep" required>
                <textarea name="description" placeholder="Deskripsi Resep" required></textarea>
                <button type="submit">Tambah</button>
            </form>
        </div>

        <!-- Form Edit Resep -->
        <div id="edit-form" style="display:none;">
            <h3>Edit Resep</h3>
            <form method="POST" action="edit.php">
                <input type="hidden" id="edit-id" name="id">
                <input type="text" id="edit-title" name="title" required>
                <textarea id="edit-description" name="description" required></textarea>
                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
