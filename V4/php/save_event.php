<?php
// Include your database connection
require_once "dp.php";

session_start(); // Make sure this is at the top

// Show errors while testing (you can turn these off later)
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
                echo "<script>alert('Event added successfully!'); window.location.href = '../php/calendar.php';</script>";
            } else {
                echo "<script>alert('Failed to add event.'); window.location.href = '../php/calendar.php';</script>";
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Please fill in all fields with valid data.'); window.location.href = '../php/calendar.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '../php/calendar.php';</script>";
}
