<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$firstname = $data['firstname'];
$lastname = $data['lastname'];
$username = $data['username'];
$password = $data['password'];

if (!$firstname || !$lastname || !$username || !$password) {
    echo json_encode(["success" => false, "message" => "Missing fields!"]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$firstname, $lastname, $username, $hashedPassword]);

    setcookie("user", $username, time() + 3600, "/");

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Username already taken."]);
}

?>