<head>
  <link rel="stylesheet" type="text/css" href="css/Messages.css">
</head>

<?php
// Include config file
require_once "connectDB.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "<p class='error'>Please enter a username.</p>";
        echo $username_err;
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "<p class='error'>Username can only contain letters, numbers, and underscores.</p>";
        echo $username_err;
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) != 1){
                    $username_err = "<p class='error'>This username is not found.</p>";
                    echo $username_err;
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "<p class='error'>Oops! Something went wrong. Please try again later.</p>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";    
        echo $password_err; 
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
        echo $password_err;
    } else{
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        // Check if the user already has a password set
        $sql = "SELECT password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameter
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if the user already has a password set
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $password_hash);
                    if(mysqli_stmt_fetch($stmt)){
                        if(empty($password_hash)){
                            // Prepare an update statement
                            $sql = "UPDATE users SET password = ? WHERE username = ?";
                            if($stmt = mysqli_prepare($conn, $sql)){
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                                
                                // Set parameters
                                $param_username = $username;
                                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                                
                                // Attempt to execute the prepared statement
                                if(mysqli_stmt_execute($stmt)){
                                    // Redirect to login page
                                    header("location: main.php");
                                } else{
                                    echo "<p class='error'>Oops! Something went wrong. Please try again later.</p>";
                                }
                            }
                        } else {
                            echo "You already have set a password, please contact the admin if you want to change your password.";
                        }
                    }
                }
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    
    // Close connection
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Signup User</title>
<link rel="stylesheet" type="text/css" href="css/userslog.css">

</script>
</head>
<body>
<?php include'loginpageheader.php'; ?> 
<main>
  <section>
  <!--User table-->
  <h1 class="slideInDown animated">STUDENT'S Signup</h1>
  	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <div class= "form-style-5 slideInDown animated">
 <input type="text" name="username" id="search-input" placeholder="Username"> <br><br>
    <input type="password" id="password" name="password" placeholder="Password"><br><br>
    <button type="submit" id="search-button">sign up</button>
  </form>
  <script>
	function navFunction() {
		var x = document.getElementById("myTopnav");
		if (x.className === "topnav") {
			x.className += " responsive";
		} else {
			x.className = "topnav";
		}
	}

	const searchButton = document.getElementById('search-button');
	const searchInput = document.getElementById('search-input');
	const passwordInput = document.getElementById('password');
  </script>
<div style="text-align: center;">
  <span style="background-color: white; color: blue; border-radius: 1em; padding: 0.2em; font-size: 0.8em;">
    <a href="login.php">Click here</a> <span style="color: black;">to login</span>
  </span>
</div>



</body>
</html>


