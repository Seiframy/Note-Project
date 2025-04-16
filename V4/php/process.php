<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
require_once('dp.php');

// Read the raw POST data
$json = file_get_contents("php://input");
error_log("Raw input: " . $json); // Log the raw input for debugging

// Decode the JSON
$data = json_decode($json, true);

// Check if decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON Decode Error: " . json_last_error_msg());
    echo json_encode(["success" => false, "message" => "Invalid JSON: " . json_last_error_msg()]);
    exit;
}

if ($data) {
    // Extract the form data
    $first_name = $data['first_name'] ?? '';
    $last_name = $data['last_name'] ?? '';
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';  // Adding confirm password
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $location = $data['location'] ?? '';

    // Validate that all required fields are filled
    if (!$first_name || !$last_name || !$username || !$password || !$email || !$phone || !$confirm_password) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit;
    }

    // Check if the username or email already exists in the database
    try {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_new WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(["success" => false, "message" => "Account already exists."]);
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO users_new (first_name, last_name, username, password, email, phone, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $username, $hashedPassword, $email, $phone, $location]);

        echo json_encode([
            "success" => true,
            "message" => "User $username registered successfully."
        ]);
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No valid JSON data received."]);
}
?>