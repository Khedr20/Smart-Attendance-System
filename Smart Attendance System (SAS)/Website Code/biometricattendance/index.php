<?php 
  require_once "connectDB.php";
  include 'header.php';
  if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    header("location: main.php");
    exit;}
  else if ($_SESSION["username"] == "admin")
  {
    //header("location: UsersLog.php");
    //exit;
  }
  
  else {
    header("location:StudentInfo.php");
  }

?> 

<!DOCTYPE html>
<html>
<head>
  <title>Users</title>
<link rel="stylesheet" type="text/css" href="css/Users.css">
<script>
  $(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();
</script>
</head>
<body>

<main>
  <section>
  <!--User table-->
  <h1 class="slideInDown animated">Here are all the Users</h1>
  <div class="tbl-header slideInRight animated">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>ID | Name</th>
          <th>Matric Number</th>
          <th>Gender</th>
          <th>Finger ID</th>
          <th>Date</th>
          <th>Time In</th>
          <th>%Attendance</th>
          <th>Email</th>          
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content slideInRight animated">
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody>
        <?php
          //Connect to database
            $sql = "SELECT * FROM users WHERE NOT username='' ORDER BY id DESC";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo '<p class="error">SQL Error</p>';
            }
            else{
              mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
              if (mysqli_num_rows($resultl) > 0){
                  while ($row = mysqli_fetch_assoc($resultl)){
          ?>
                      <TR>
                      <TD><?php echo $row['id']; echo" | "; echo $row['username'];?></TD>
                      <TD><?php echo $row['serialnumber'];?></TD>
                      <TD><?php echo $row['gender'];?></TD>
                      <TD><?php echo $row['fingerprint_id'];?></TD>
                      <TD><?php echo $row['user_date'];?></TD>
                      <TD><?php echo $row['time_in'];?></TD>
                      <TD>
                        <?php
                          if ($row['percentage_attendance'] < 80) {
                            echo '<strong style="color:red">' . $row['percentage_attendance'] . '</strong>';
                          } else {
                            echo $row['percentage_attendance'];
                          }
                        ?>
                      </TD>
                      <TD>
                        <span style="font-size: xx-small;">
                          <?php echo $row['email']; ?>
                        </span>
                      </TD>
                      </TR>
        <?php
                }   
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</section>
</main>
</body>
</html>