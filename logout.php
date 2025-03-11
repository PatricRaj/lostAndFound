<?php
session_start();

// Destroying session
session_unset();
session_destroy();

// go to login page
header('Location: login.php');
exit();
?>
