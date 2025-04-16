<?php
session_start();
session_destroy(); // Poof! You're logged out!
header("Location: form.html");
exit;
?>
