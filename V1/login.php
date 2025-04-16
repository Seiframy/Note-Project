<?php

// Get the raw POST data
$inputData = file_get_contents("php://input");

// Decode the JSON data into a PHP array
$data = json_decode($inputData, true);

// Check if the username and password exist
if (isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    // Check if username and password are correct
    if ($username == 'test' && $password == '1') {
        // Send success response
        echo json_encode(["success" => true, "message" => "Login successful!"]);
    } else {
        // Send error response
        echo json_encode(["success" => false, "message" => "Invalid username or password."]);
    }
} else {
    // If missing username or password, send an error
    echo json_encode(["success" => false, "message" => "Missing username or password."]);
}

?>
