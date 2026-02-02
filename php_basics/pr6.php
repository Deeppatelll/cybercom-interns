<?php

// Set cookie (expires in 1 hour)
setcookie("user_preference", "dark_mode", time() + 3600, "/");
// Start session
session_start();

// Set session variable if not already set
if (!isset($_SESSION["username"])) {
    $_SESSION["username"] = "InternName";
}

// Handle logout
if (isset($_POST["logout"])) {
    session_destroy();
    echo "Session destroyed successfully.<br>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sessions & Cookies Practical</title>
</head>
<body>

<h2>Cookie Demo (PHP-011)</h2>

<?php
if (isset($_COOKIE["user_preference"])) {
    echo "User Preference: " . $_COOKIE["user_preference"];
} else {
    echo "Cookie not available yet. Refresh once.";
}
?>

<hr>

<h2>Session Demo (PHP-012)</h2>

<?php
if (isset($_SESSION["username"])) {
    echo "Welcome " . $_SESSION["username"];
} else {
    echo "No active session.";
}
?>

<form method="post">
    <br>
    <button type="submit" name="logout">Logout</button>
</form>

</body>
</html>
