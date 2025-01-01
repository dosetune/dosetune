<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // User is logged in; redirect to order.php
    header("Location: order.php");
    exit;
} else {
    // User is not logged in; redirect to login.html
    header("Location: login/login.php");
    exit;
}
?>
