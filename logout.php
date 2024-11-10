<?php
session_start();
session_destroy(); // Destroy the session

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header('Location: login.php'); // Redirect to login page
exit();
