<?php
$host = "127.0.0.1";
$dbname = "notes_app";
$username = "root";
$password = "1166";
$port = "3307";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>