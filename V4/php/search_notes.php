<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require_once('dp.php'); // Include database connection (make sure this is correct)
session_start();

// Read the JSON input
$json = file_get_contents("php://input");
$data = json_decode($json, true);

// Check for errors in the input
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["success" => false, "message" => "Invalid JSON format"]);
    exit;
}

$query = $data['query'] ?? '';

// If query is empty, return an empty response
if (empty($query)) {
    echo json_encode(["success" => false, "message" => "No search query provided"]);
    exit;
}

try {
    // Search for notes where title or content contains the search query
    $stmt = $pdo->prepare("SELECT * FROM notes_taking WHERE title LIKE ? OR content LIKE ?");
    $stmt->execute(["%$query%", "%$query%"]);

    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return notes as JSON response
    echo json_encode(["success" => true, "notes" => $notes]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>