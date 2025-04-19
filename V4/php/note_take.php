<?php

require_once __DIR__ . '/session_check.php';  // guard AND $user_id
require_once __DIR__ . '/dp.php';             // DB connection

// Now you’re safe to use $pdo and $user_id


// Handle note creation (in this file, not redirecting)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_note"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];

    if (!empty($title) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes_taking (title, content, user_id) VALUES (:title, :content, :user_id)");
            $stmt->bindParam(':user_id', $_SESSION['user_id']); // Assuming user_id is stored in session
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            echo "<script>alert('Note created successfully!');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error saving note: " . $e->getMessage() . "');</script>";
        }
    }
}

// Fetch all notes for the right column
$notes = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM notes_taking WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);

    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='text-danger'>Error loading notes: " . $e->getMessage() . "</p>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Note Taking</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css" />

    <!-- Updated jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light pink-navbar">
        <a class="navbar-brand" href="../index.html">Note Taking Website</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/calendar.php">Calendar</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/logout.php">Log out</a></li>
            </ul>
        </div>
    </nav>

    <!-- Note-taking Section -->
    <div class="container my-5">
        <div class="row">
            <!-- LEFT COLUMN: Add Note Form -->
            <div class="col-md-6">
                <h2 class="text-center">Take a Note</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="noteTitle">Note Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter note title" required>
                    </div>
                    <div class="form-group">
                        <label for="noteContent">Note Content</label>
                        <textarea class="form-control" id="content" name="content" rows="6" placeholder="Write your note here..." required></textarea>
                    </div>
                    <button type="submit" name="create_note" class="btn btn-primary">Create Note</button>
                </form>
            </div>

            <!-- RIGHT COLUMN: Display All Notes -->
            <div class="col-md-6">
                <h3 class="text-center">All Notes</h3>
                <?php if (!empty($notes)): ?>
                    <?php foreach ($notes as $note): ?>
                        <div class="card pink-card mb-3 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($note['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($note['content']); ?></p>

                                <!-- Edit/Delete buttons -->
                                <form method="post" action="/php/note_edit_or_delete.php" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" name="edit" class="btn btn-sm btn-light">✏</button>
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this note?');">🗑</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No notes available yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- Link to external JavaScript file -->
    <script src="../js/note_script.js"></script>
</body>

</html>