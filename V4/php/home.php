<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
</head>

<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h1>
    <a href="logout.php">Logout</a>
</body>

</html>