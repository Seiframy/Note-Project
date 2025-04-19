<?php
$host = "sqlXXX.infinityfree.com"; // use your real host name
$dbname = "epiz_12345678_mydb";   // your real DB name
$username = "epiz_12345678";      // your real username
$password = "yourHostingPassword";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
