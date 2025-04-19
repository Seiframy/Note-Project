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
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_new WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(["success" => false, "message" => "Account already exists."]);
            exit;
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users_new (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);

        // Log in the user
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user'] = $username;

        // Single response with redirect
        echo json_encode([
            "success" => true,
            "message" => "Account created!",
            "redirect" => "/php/note_take.php"
        ]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No valid data received."]);
}
?>