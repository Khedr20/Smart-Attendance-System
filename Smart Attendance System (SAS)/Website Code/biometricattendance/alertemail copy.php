<?php

// Connect to the database
require_once "connectDB.php";
include 'header.php';

// Start a session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
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

// Your Gmail address and password
$username = 'sas.noreply1@gmail.com';
$password = 'SmartAttendance';

// Set the recipient email address
$to = 'mahmoudkhedr1200@gmail.com';

// Set the email subject and message
$subject = 'Automatic Email';
$message = 'This is an automatic email.';

// Set the email headers
$headers = array(
  'From' => $username,
  'To' => $to,
  'Subject' => $subject
);

// Send the email using PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/pear/PEAR/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php';
require 'C:/xampp/php/pear/PEAR/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'C:/xampp/php/pear/PEAR/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
  //Server settings
  $mail->SMTPDebug = 0;                      // Enable verbose debug output
  $mail->isSMTP();                                            // Send using SMTP
  $mail->Host       = 'ssl://smtp.gmail.com';                    // Set the SMTP server to send through
  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
  $mail->Username   = $username;                     // SMTP username
  $mail->Password   = $password;                               // SMTP password
  $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
  $mail->Port       = 465; //587                                    // TCP port to connect to

  //Recipients
  $mail->setFrom($username);
  $mail->addAddress($to);     // Add a recipient

//Content
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $subject;
$mail->Body    = $message;

$mail->send();
echo 'Message has been sent';
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>