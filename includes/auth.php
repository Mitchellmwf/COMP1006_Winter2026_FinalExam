<?php
// check if the session is not already started, start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Prevent standard browser/proxy caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


// access the current session & check to see whether the user is logged in

if (empty($_SESSION["user_id"])) {
    echo "You must be logged in to view this page.";
    header('Location:index.php');
    exit();
}
//require auth.php on all pages that are restricted to registered users only 