<?php
if (isset($_POST['includeCheck'])) {
    $includeCheck = true;
    require_once "../../general/db_conn.php";
    require_once "../../general/php-functions.php";

    $postCount = $_POST['postCount'];
    $idPost = $_POST['idPost'];

    include "post-include.php";
} else {
    header("Location: /404");
}
?>
