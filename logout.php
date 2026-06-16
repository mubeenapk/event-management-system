<?php
session_start();

// If admin is logged in
if (isset($_SESSION['admin_id'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    session_regenerate_id(true); // security
    header("Location: home.php");
    exit();
}

// If normal user is logged in
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']); // if you set this in auth.php
    session_regenerate_id(true);
    header("Location: auth.php");
    exit();
}

// Default fallback → go to homepage
header("Location: home.php");
exit();
