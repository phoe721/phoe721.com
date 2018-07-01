<?php
	session_start();
	if(isset($_SESSION['lid'])) {
		unset($_SESSION['lid']);
	}
?>
<html>
<body>
Goodbye!
<a href="login.php">Go back to login page</a>
</body>
</html>