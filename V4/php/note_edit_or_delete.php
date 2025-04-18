<?php
require_once "dp.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🗑 Handle Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = $_POST["id"];
    try {
        $stmt = $pdo->prepare("DELETE FROM notes_taking WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "<script>alert('Note deleted successfully.'); window.location.href = '../php/note_take.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "Error deleting note: " . $e->getMessage();
    }
}

// 📝 Handle Edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $id = $_POST["id"];

    try {
        $stmt = $pdo->prepare("SELECT * FROM notes_taking WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $note = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching note: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <?php if (isset($note)): ?>
            <h3 class="text-center mb-4">Edit Note</h3>
            <form method="post" action="note_update.php" class="card p-4 pink-card shadow">
                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                <div class="form-group">
                    <label for="title">Note Title</label>
                    <input type="text" name="title" id="title" class="form-control" required value="<?php echo htmlspecialchars($note['title']); ?>">
                </div>

                <div class="form-group mt-3">
                    <label for="content">Note Content</label>
                    <textarea name="content" id="content" rows="6" class="form-control" required><?php echo htmlspecialchars($note['content']); ?></textarea>
                </div>

                <button type="submit" name="update_note" class="btn btn-primary mt-3">Update</button>
            </form>
        <?php else: ?>
            <p class="text-center text-danger">Note not found. Please go back.</p>
            <div class="text-center mt-3">
                <a href="../php/note_take.php" class="btn btn-secondary">Back to Notes</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>