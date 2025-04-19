<?php
session_start();

// Clear session data
$_SESSION = [];
session_unset();
session_destroy();

// Clear cookie
setcookie("user", "", time() - 3600, "/");

// Redirect to login or landing page
header("Location: /html/form.html"); // or wherever your login form is
exit;
?>