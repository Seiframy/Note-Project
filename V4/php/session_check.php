<?php
/* session_check.php
   Include this ONCE at the top of every protected page. */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /html/form.html");   // login page
    exit;
}

/* If code reaches here, user IS logged in.
   Expose $user_id so the calling page can use it. */
$user_id = $_SESSION['user_id'];
?>