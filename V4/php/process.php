<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
require_once('dp.php');

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["success" => false, "message" => "Invalid JSON"]);
    exit;
}

if ($data) {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';

    if (!$username || !$password || !$confirm_password) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_new WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(["success" => false, "message" => "Account already exists."]);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users_new (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);

        echo json_encode(["success" => true, "message" => "User $username registered successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No valid data received."]);
}
?>