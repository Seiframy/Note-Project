<?php
// Include your DB connection
require_once "dp.php"; // Make sure this file sets up $pdo correctly using PDO

// Debug mode
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Create note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_note"])) {
    echo "Create note request received.<br>";

    $title = isset($_POST["title"]) ? $_POST["title"] : null;
    $content = isset($_POST["content"]) ? $_POST["content"] : null;

    if (!empty($title) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes_taking (title, content) VALUES (:title, :content)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);

            if ($stmt->execute()) {
                echo "<script>alert('Note created successfully!');</script>";
            } else {
                echo "<script>alert('Error creating note.');</script>";
            }
        } catch (PDOException $e) {
            echo "DB Error (create): " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Handle Search request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_notes"])) {
    $search_query = $_POST["search_query"];

    try {
        $stmt = $pdo->prepare("SELECT * FROM notes_taking WHERE title LIKE :query OR content LIKE :query ORDER BY created_at DESC");
        $stmt->execute(['query' => "%$search_query%"]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "DB Error (search): " . $e->getMessage();
        $notes = [];
    }
}

?>
