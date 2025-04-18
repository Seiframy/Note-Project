<?php
// Include your DB connection
require_once "dp.php"; // make sure $pdo is set up here

// Initialize notes array
$notes = [];

// Check if search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_notes"])) {
    $search_query = $_POST["search_query"];

    try {
        // Prepared statement to prevent SQL injection clearly and simply
        $stmt = $pdo->prepare("SELECT * FROM notes_taking WHERE title LIKE :query OR content LIKE :query ORDER BY created_at DESC");
        $stmt->execute(['query' => "%$search_query%"]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Oops! Database Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="../css/note_style.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <h3 class="text-center">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
        <hr>

        <!-- Display Notes -->
        <?php if (!empty($notes)): ?>
            <?php foreach ($notes as $note): ?>
                <div class="card pink-card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($note['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($note['content']); ?></p>

                        <!-- Edit Button -->
                        <span>
                            <form action="../php/notes.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <button type="submit" name="edit_note" class="btn btn-sm btn-info">Edit</button>
                            </form>
                        </span>

                        <!-- Delete Button -->
                        <span>
                            <form action="../php/notes.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <button type="submit" name="delete_note" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete?');">
                                    Delete
                                </button>
                            </form>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No notes found matching your search.</p>
        <?php endif; ?>

        <br>
        <a href="../html/note_take.html" class="btn btn-secondary">Back to Notes!</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>