<?php
session_start();
session_unset();
session_destroy();
header("Location: login/login.php"); // Redirect to login page after logging out
exit;
?>