<?php
if (isset($_POST['ban-user-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    if (getRole($db) < 2) {
        header("Location: /404");
        exit();
    }

    // user variables
    $uid = $_POST['uid'];
    $isBanned = $_POST['isBanned'];

    // redirect variables
    $search = $_POST['search'];
    $sort = $_POST['sort'];
    $page = $_POST['page'];

    // edit the role of the user in the db
    $sql = "UPDATE users SET isBanned = ? WHERE id = ?";
    $vars = [!$isBanned, $uid];
    $varsType = "ii";
    executeStmt($db, $sql, $varsType, $vars, false);

    header("Location: /control-panel?manage-users&user-banned-successfully&search=$search&sort=$sort&page=$page");

} else {
    header("Location: /404");
}
?>
