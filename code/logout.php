<?php
session_start(); // start the session

// redirect to the login page
header("Location: login.php");
exit;
