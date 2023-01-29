<head>
  <link rel="stylesheet" type="text/css" href="css/Messages.css">
</head>

<?php
// Initialize the session
error_reporting(E_ERROR | E_PARSE);

// Check if the user is already logged in, if yes then redirect him to welcome page

 
// Include config file
require_once "connectDB.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "<p class='error'>Please enter your username.</p>";
        //echo $username_err;
    } else{
        $username = trim($_POST["username"]);
    }
    
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "<p class='error'>Please enter your password.</p>";
		//echo $password_err;
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            if($_SESSION["username"]=="admin")
                            {
                                header("location:UsersLog.php");
                            }
                            else{
                                header("location:StudentInfo.php");
                            }
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "<p class='error'>Invalid username or password.</p>";
							echo $login_err;
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "<p class='error'>Invalid username or password.</p>";
					echo $login_err;
                }
            } else{
                echo "<p class='error'>Oops! Something went wrong. Please try again later.</p>";
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
  <title>Users Logs</title>
<link rel="stylesheet" type="text/css" href="css/userslog.css">

</script>
</head>
<body>
<?php include'loginpageheader.php'; ?> 
<main>
  <section>
  <!--User table-->
  <h1 class="slideInDown animated">Login'S PAGE</h1>
  	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <div class="form-style-5 slideInDown animated">
 <input type="text" name="username" id="search-input" placeholder="Username"> <br><br>
    <input type="password" id="password" name="password" placeholder="Password"><br><br>
    <button type="submit" id="search-button">Login</button>
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
    <a href="signup.php">Click here</a> <span style="color: black;">to sign up</span>
  </span>
</div>

</body>
</html>