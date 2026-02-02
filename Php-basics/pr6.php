<?php

setcookie("user_preference", "dark_mode", time() + 3600, "/");

if (isset($_COOKIE["user_preference"])) {
    echo "User Preference: " . $_COOKIE["user_preference"];
} else {
    echo "Cookie not set yet. Refresh the page.";
}

//task2 
session_start();

if (!isset($_SESSION["username"])) {
    $_SESSION["username"] = "InternName";
}

echo "<br><br>Session User: ";

if (isset($_SESSION["username"])) {
    echo $_SESSION["username"];
} else {
    echo "No active session.";
}

?>

<form method="post">
    <br>
    <button type="submit" name="logout">Logout</button>
</form>

<?php

if (isset($_POST["logout"])) {
    session_destroy();
    echo "<br>Session destroyed successfully.";
}

?>
