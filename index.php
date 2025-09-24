<?php
require 'db.php';

// Proses penambahan data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $sql = "INSERT INTO users (name) VALUES (?)";
    $pdo->prepare($sql)->execute([$_POST['name']]);
    header("Location: index.php"); // Redirect untuk mencegah duplikasi
    exit;
}

// Proses penghapusan data
if (isset($_GET['delete'])) {
    $sql = "DELETE FROM users WHERE id = ?";
    $pdo->prepare($sql)->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

// Ambil semua data
$stmt = $pdo->query("SELECT id, name FROM users");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi CRUD Sederhana</title>
    <style>
        body { font-family: sans-serif; container: centered; max-width: 600px; margin: auto; padding-top: 20px;}
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: red; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Tambah Pengguna Baru</h2>
<form action="index.php" method="post">
    <label for="name">Nama:</label>
    <input type="text" id="name" name="name" required>
    <button type="submit">Tambah</button>
</form>

<h2>Daftar Pengguna</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = $stmt->fetch()): ?>
    <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><a href="index.php?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
