<?php 
session_start();
//hentikan session "username"
$_SESSION['username'] = '';
unset($_SESSION['username']);
//hentikan session "password"
$_SESSION['password'] = '';
unset($_SESSION['password']);
session_unset();
session_destroy();
echo "<script>
	alert ('Logout From Integrated Central Service System');
	</script>";

echo"<meta http-equiv=refresh content=0;url=login.php>";
?>