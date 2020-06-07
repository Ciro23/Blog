<?php
if (isset($_POST['includeCheck'])) {
    $includeCheck = true;
    require_once "../../general/db_conn.php";
    require_once "../../general/php-functions.php";

    $postCount = $_POST['postCount'];
    $uidOwner = $_POST['uidOwner'];
    $unameOwner = $_POST['unameOwner'];

    include "user-profile-include.php";
} else {
    header("Location: /404");
}
?>
