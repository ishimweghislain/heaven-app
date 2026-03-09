<?php
/**
 * Logout Script
 * Success Microfinance Institution S.C. Website
 */

// Start session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("location: index.php");
exit;
