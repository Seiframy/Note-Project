<?php
// Create note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_note"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];
    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO notes_taking (title, content) VALUES (:title, :content)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        if ($stmt->execute()) {
            echo "<script>alert('Note created successfully!');</script>";
        } else {
            echo "<script>alert('Error creating note.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<?php
// Fetch all notes
$stmt = $conn->prepare("SELECT * FROM notes_taking ORDER BY created_at DESC");
$stmt->execute();
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php
// Update note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_note"])) {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $content = $_POST["content"];
    if (!empty($id) && !empty($title) && !empty($content)) {
        $stmt = $conn->prepare("UPDATE notes_taking SET title = :title, content = :content WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "<script>alert('Note updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating note.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>


<?php
// Delete note
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_note"])) {
    $id = $_POST["id"];
    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM notes_taking WHERE id = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "<script>alert('Note deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error deleting note.');</script>";
        }
    } else {
        echo "<script>alert('Please enter a valid note ID.');</script>";
    }
}
?>
