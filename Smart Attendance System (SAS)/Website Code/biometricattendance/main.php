<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="css/userslog.css">
<script>

</script>
</head>
<body>
<?php include'loginpageheader.php'; ?> 
<main>
  <section>
  <!--User table-->
  <h1 class="slideInDown animated">WELCOME TO THE SMART ATTENDANCE SYSTEM WEBSITE</h1>
  	<div class="form-style-5 slideInDown animated">
  		<form method="POST" action="login.php">
  		
       
  			<input type="submit" name="To_Excel" value="ADMIN / LECTURER">
       
  		</form>
      <div class="form-style-5 slideInDown animated">
      <form method="POST" action="login.php">
      
      
        <input type="submit" name="To_Excel" value="STUDENT">
      </form>
  	
  </div>
</section>
</main>
</body>
</html>