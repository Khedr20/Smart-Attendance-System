<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
unset($_SESSION['Username']);
unset($_SESSION['Password']);

// Destroy the session
session_unset();
session_destroy();
 
// Redirect to login page
header("location: main.php");
exit;
?>
