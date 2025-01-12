<<<<<<< HEAD
<?php
session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-3600, '/');
}
session_destroy();
header("Location: login.php");  // Redirect to login.php instead of index.php
exit;
?>
=======
<?php
session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-3600, '/');
}
session_destroy();
header("Location: login.php");  // Redirect to login.php instead of index.php
exit;
?>
>>>>>>> 228c558 (updated)
