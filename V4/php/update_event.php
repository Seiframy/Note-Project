<?php

require_once __DIR__ . '/session_check.php';  // guard AND $user_id
require_once __DIR__ . '/dp.php';             // DB connection

// Now you’re safe to use $pdo and $user_id

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $day = $_POST['day'];

    try {
        $stmt = $pdo->prepare("UPDATE calendar_events SET title = :title, day = :day WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<script>alert('Event updated!'); window.location.href = '../php/calendar.php';</script>";
    } catch (PDOException $e) {
        echo "Error updating: " . $e->getMessage();
    }
}
?>
