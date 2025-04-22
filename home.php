<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'crud_app';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle Add/Edit/Delete
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $conn->query("INSERT INTO items (name, description) VALUES ('$name', '$desc')");
}
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $conn->query("UPDATE items SET name='$name', description='$desc' WHERE id=$id");
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM items WHERE id=$id");
}

$items = $conn->query("SELECT * FROM items");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container p-5">
    <h2>Welcome, <?= $_SESSION['username'] ?> | <a href="logout.php">Logout</a></h2>
    <h3>Add Item</h3>
    <form method="post">
        <input type="text" name="name" placeholder="Name" required class="form-control mb-2">
        <textarea name="description" placeholder="Description" class="form-control mb-2"></textarea>
        <button name="add" class="btn btn-success">Add</button>
    </form>
    <h3 class="mt-4">Items</h3>
    <table class="table">
        <tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>
        <?php while ($row = $items->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td>
                <form method="post" style="display:inline-block">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="name" value="<?= $row['name'] ?>" required class="form-control mb-1">
                    <textarea name="description" class="form-control mb-1"><?= $row['description'] ?></textarea>
                    <button name="edit" class="btn btn-warning btn-sm">Edit</button>
                </form>
                <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>