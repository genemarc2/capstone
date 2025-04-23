<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

	<div class="logo-container">
		<i class="bi bi-cup-hot-fill"></i>
		<span class="logo-text">The Brew</span>
	</div>


     <form action="login.php" method="post">
     	<h2>LOGIN</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
     	<label>Username</label>
     	<input type="text" name="uname" placeholder="Enter your username"><br>

     	<label>Password</label>
     	<input type="password" name="password" placeholder="Enter your password"><br>

     	<button type="submit">Login</button>
     </form>
</body>
</html>