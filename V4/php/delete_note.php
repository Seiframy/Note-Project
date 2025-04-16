<?php
require 'db.php'; // Adjust this if your DB file is named differently

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $noteId = intval($data['id']);
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->bind_param("i", $noteId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$pdo->close();
?>
