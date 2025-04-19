<?php

/* 1. Start the session first (no output has been sent yet) */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 2. Tell the browser we’ll return JSON */
header("Content-Type: application/json");

/* 3. Bring in the DB connection */
require_once __DIR__ . '/dp.php';

/* Now you’re safe to use $_SESSION and $pdo */

// Debug: Log raw input
$json = file_get_contents("php://input");
file_put_contents("debug_log.txt", "Raw input: " . $json . "\n", FILE_APPEND);

$data = json_decode($json, true);

// Debug: Log decoding issues
if (json_last_error() !== JSON_ERROR_NONE) {
    file_put_contents("debug_log.txt", "JSON Decode Error: " . json_last_error_msg() . "\n", FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Invalid JSON format"]);
    exit;
}

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM users_new WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Set cookie and session
        setcookie("user", $username, time() + 3600, "/"); // 1 hour
        $_SESSION['user_id'] = $user['id']; // Store the actual user ID
        $_SESSION['user'] = $username; // Optional, keep for reference


        
        echo json_encode(["success" => true]);
    } else {
        
        echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    }
} catch (PDOException $e) {
    file_put_contents("debug_log.txt", "Login DB error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(["success" => false, "message" => "A database error occurred."]);
}
?>