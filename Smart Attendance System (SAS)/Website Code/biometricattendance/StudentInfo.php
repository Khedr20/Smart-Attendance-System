<?php
  // Connect to the database
  require_once "connectDB.php";
  include './loginpageheader.php';

  // Start a session
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  

  // Check if the user is logged in
  if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    header("location:logout.php");
    exit;
  }

  // Check if the user is "admin"
  if ($_SESSION["username"] == "admin") {
    header("location:logout.php");
    exit;
  }

  // Prepare a SELECT statement to get the serialnumber of the logged-in user
  $sql = "SELECT serialnumber FROM users WHERE username = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);

  // Execute the SELECT statement
  mysqli_stmt_execute($stmt);

  // Bind the result variable
  mysqli_stmt_bind_result($stmt, $serialnumber);

  // Fetch the result
  mysqli_stmt_fetch($stmt);

  // Close the statement
  mysqli_stmt_close($stmt);

  // Get the current date
  $today = date("Y-m-d");

  // Prepare a SELECT statement to count the number of days that the logged-in user has checked in
  $sql = "SELECT COUNT(*) FROM users_logs WHERE username = ? AND checkindate >= '2022-10-01'";
  $stmt = mysqli_prepare($conn, $sql);

  // Bind the parameters
  mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);

  // Execute the SELECT statement
  mysqli_stmt_execute($stmt);

  // Bind the result to a variable
  mysqli_stmt_bind_result($stmt, $attended_days);

  // Fetch the result
  mysqli_stmt_fetch($stmt);

  // Close the statement
  mysqli_stmt_close($stmt);

  // Prepare a SELECT statement to count the number of days that the "admin" user has checked in
  $sqladmin = "SELECT COUNT(*) FROM users_logs WHERE username = 'admin' AND checkindate >= '2022-10-01'";
  $adminattend = mysqli_prepare($conn, $sqladmin);

  // Execute the statement
  mysqli_stmt_execute($adminattend);

  // Bind the result to a variable
  mysqli_stmt_bind_result($adminattend, $admin_attended_days);

  // Fetch the result
  mysqli_stmt_fetch($adminattend);

  // Close the statement
  mysqli_stmt_close($adminattend);

  // Calculate the absent days as the total number of days in the month minus the number of days attended
  $absent_days = $admin_attended_days - $attended_days;

  // Calculate the percentage of attendance
  if ($admin_attended_days == 0) {
    $percentage_attendance = 0;
  } else {
    $percentage_attendance = ($attended_days / $admin_attended_days) * 100;
  }

  // Set the maximum number of days that the user can attend
  $max_days = 30;

  // Calculate the remaining days that the user can attend
  $remaining_days = $max_days - $admin_attended_days;

  // Prepare the update statement
  $sql = "UPDATE users SET percentage_attendance = ? WHERE username = ?";
  $stmt = mysqli_prepare($conn, $sql);

  // Bind the parameters
  mysqli_stmt_bind_param($stmt, "ds", $percentage_attendance, $_SESSION["username"]);

  // Execute the update statement
  mysqli_stmt_execute($stmt);

  // Close the statement
  mysqli_stmt_close($stmt);

?>



<!DOCTYPE html>
<html>
<head>
  <style>
    .attendance-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 20px;
    }

    .student-info {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      margin-bottom: 20px;
    }

    .student-info h2 {
      margin: 0;
    }

    .student-info p {
      margin: 0;
      font-weight: bold;
    }

    .attendance-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #dddddd;
      padding: 20px;
      width: 500px;
      margin-bottom: 20px;
    }

    .attendance-card h2 {
      margin: 0;
    }

    .attendance-card p {
      margin: 10px 0;
    }

    .attendance-card p:nth-child(odd) {
      font-weight: bold;
    }

    .attendance-container {
      position: relative;
    }

    .student-info a {
      position: absolute;
      top: -50px;
      right: 0;
      /* add some padding and styling to make the button look good */
      padding: 10px 20px;
      background-color: #187881;
      color: #ffffff;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="attendance-container">
    <div class="student-info">
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
      <p>Matric Number: <?php echo htmlspecialchars($serialnumber); ?></p>
      <a href="logout.php">Logout</a>
    </div>
    <div class="attendance-card">
      <h2>Attendance</h2>
      <p>Attended Days: <?php echo $attended_days; ?></p>
      <p>Absent Days: <?php echo $absent_days; ?></p>
      <p>Percentage Attendance: <?php echo number_format($percentage_attendance, 1); ?>%</p>
      <p>Remaining Days: <?php echo $remaining_days; ?></p>
    </div>
  </div>
</body>
</html>
