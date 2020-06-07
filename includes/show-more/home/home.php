<?php
if (isset($_POST['includeCheck'])) {
    $includeCheck = true;
    require_once "../../general/db_conn.php";
    require_once "../../general/php-functions.php";

    $postCount = $_POST['postCount'];
    $offset = $_POST['offset'];
    $mysqlWhere = $_POST['mysqlWhere'];

    include "home-include.php";
} else {
    header("Location: /404");
}
?>
