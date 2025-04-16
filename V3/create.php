<?php
include 'db.php';

session_start();

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

file_put_contents("log.txt", print_r($data, true)); // ✍️ logs to check if JSON arrived


// ✅ NEW: JSON validation
if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid or missing JSON"]);
    exit;
}

// Assign variables
$first_name = $data["first_name"];
$last_name  = $data["last_name"];
$username   = $data["username"];
$password   = password_hash($data["password"], PASSWORD_DEFAULT); // hashed!
$email      = $data["email"];
$phone      = $data["phone"];
$location   = isset($data["location"]) ? $data["location"] : "";

// Prevent duplicate username or email (OPTIONAL BUT NICE)
$check = $conn->prepare("SELECT id FROM v3_create_new WHERE username = ? OR email = ?");
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username or email already exists."]);
    $check->close();
    exit;
}
$check->close();

// Insert into DB
$stmt = $conn->prepare("
    INSERT INTO v3_create_new (first_name, last_name, username, password, email, phone, location)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("sssssss", $first_name, $last_name, $username, $password, $email, $phone, $location);

if ($stmt->execute()) {
    session_start();
    setcookie("user", $username, time() + 3600); // cookie for 1 hour
    $_SESSION["user"] = $username;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
