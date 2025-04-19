<?php

require_once __DIR__ . '/session_check.php';  // guard AND $user_id
require_once __DIR__ . '/dp.php';             // DB connection

// Now you’re safe to use $pdo and $user_id

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_event"])) {

    // Get the form data
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    $day = isset($_POST["day"]) ? intval($_POST["day"]) : 0;

    // Validate inputs
    if (!empty($title) && $day >= 1 && $day <= 30) {
        try {
            // Prepare SQL query
            $stmt = $pdo->prepare("INSERT INTO calendar_events (title, day, user_id) VALUES (:title, :day, :user_id)");
            $stmt->bindParam(':user_id', $_SESSION['user_id']); // Assuming you have user_id in session
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':day', $day);

            // Execute and check
            if ($stmt->execute()) {
                echo "<script>alert('Event added successfully!'); window.location.href = '/php/calendar.php';</script>";
            } else {
                echo "<script>alert('Failed to add event.'); window.location.href = '/php/calendar.php';</script>";
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Please fill in all fields with valid data.'); window.location.href = '/php/calendar.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '/php/calendar.php';</script>";
}
