<?php

// Start a session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Connect to the database (if not already connected)
if (!isset($conn)) {
  require_once "connectDB.php";
}

// Prepare a SELECT statement to get the email of the logged-in user
$sqlemail = "SELECT email FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sqlemail);
mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);

// Execute the SELECT statement
mysqli_stmt_execute($stmt);

// Bind the result variable
mysqli_stmt_bind_result($stmt, $email);

// Fetch the result
mysqli_stmt_fetch($stmt);

// Close the statement
mysqli_stmt_close($stmt);

// Set the recipient email address
$to = $email;

// Set the email subject and message
$subject = 'Automatic Email';
$message = 'This is an automatic email.';

// Set the email headers
$headers = "From: sas.noreply1@gmail.com\r\n";

// Send the email
if (mail($to, $subject, $message, $headers)) {
  echo 'Message sent successfully';
} else {
  echo 'Message could not be sent';
}

?>
