<?php
$includeCheck = true;
require "../includes/general/db_conn.php";

if (isset($_SESSION['uname'])) {
    session_destroy();
}
header("Location: /");
?>
