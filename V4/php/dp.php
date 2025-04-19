<?php

// Put this ONCE in dp.php  (or a config file loaded everywhere)
error_reporting(E_ALL);

/* ‑‑‑ While TESTING ‑‑‑ */
ini_set('display_errors', 1);   // show errors

/* ‑‑‑ When you go LIVE for real users ‑‑‑ */
// ini_set('display_errors', 0); // hide errors


$host = "sql109.infinityfree.com"; // use your real host name
$dbname = "if0_38781438_notes_app_db";   // your real DB name
$username = "if0_38781438";      // your real username
$password = "Blush12345";
$port = 3306; // default MySQL port

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
