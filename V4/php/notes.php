<?php
// Include your DB connection
require_once "dp.php"; // Make sure this file sets up $conn correctly using PDO

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
            $stmt = $conn->prepare("INSERT INTO notes_taking (title, content) VALUES (:title, :content)");
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

// Fetch all notes
try {
    $stmt = $conn->prepare("SELECT * FROM notes_taking ORDER BY created_at DESC");
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "DB Error (fetch): " . $e->getMessage();
}

// Update note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_note"])) {
    echo "Update note request received.<br>";

    $id = $_POST["id"] ?? null;
    $title = $_POST["title"] ?? null;
    $content = $_POST["content"] ?? null;

    if (!empty($id) && !empty($title) && !empty($content)) {
        try {
            $stmt = $conn->prepare("UPDATE notes_taking SET title = :title, content = :content WHERE id = :id");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo "<script>alert('Note updated successfully!');</script>";
            } else {
                echo "<script>alert('Error updating note.');</script>";
            }
        } catch (PDOException $e) {
            echo "DB Error (update): " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Delete note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_note"])) {
    echo "Delete note request received.<br>";

    $id = $_POST["id"] ?? null;

    if (!empty($id)) {
        try {
            $stmt = $conn->prepare("DELETE FROM notes_taking WHERE id = :id");
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo "<script>alert('Note deleted successfully!');</script>";
            } else {
                echo "<script>alert('Error deleting note.');</script>";
            }
        } catch (PDOException $e) {
            echo "DB Error (delete): " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Please enter a valid note ID.');</script>";
    }
}
?>
