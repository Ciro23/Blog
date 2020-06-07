<?php
if (isset($includeCheck)) {
    $dbServername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "website";

    $db = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    session_start();
} else {
    header("Location: /404");
}
?>
