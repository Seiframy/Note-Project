<?php

require_once __DIR__ . '/session_check.php';  // guard AND $user_id
require_once __DIR__ . '/dp.php';             // DB connection

// Now you’re safe to use $pdo and $user_id

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_note"])) {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $content = $_POST["content"];

    try {
        $stmt = $pdo->prepare("UPDATE notes_taking SET title = :title, content = :content WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<script>alert('Note updated successfully!'); window.location.href = '/php/note_take.php';</script>";
    } catch (PDOException $e) {
        echo "Error updating note: " . $e->getMessage();
    }
}
?>