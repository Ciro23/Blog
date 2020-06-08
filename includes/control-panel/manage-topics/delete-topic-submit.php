<?php
if (isset($_POST['delete-topic-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    if (getRole($db) < 2) {
        header("Location: /404");
        exit();
    }

    // topic name
    $idTopic = $_POST['id'];

    // gets the name of the topic
    $sql = "SELECT id FROM topics WHERE id = ? AND status = 1";
    $vars = [$idTopic];
    $varsType = "s";
    $result = executeStmt($db, $sql, $varsType, $vars);

    $row = mysqli_fetch_assoc($result);
    $topic = $row['id'];

    // completely formats the old name
    $topicFormatted = format($topic);

    // delets the index.php file in the topic folder
    unlink("../../../topics/$topicFormatted/index.php");
    rmdir("../../../topics/$topicFormatted");

    // hides everything about the topic in the db
    $sql = "UPDATE topics SET status = 0 WHERE id = ?";
    $result = executeStmt($db, $sql, $varsType, $vars);

    $sql = "UPDATE posts SET status = 0 WHERE topic = ?";
    $result = executeStmt($db, $sql, $varsType, $vars);

    $sql = "UPDATE comments SET status = 0 WHERE topic = ?";
    $result = executeStmt($db, $sql, $varsType, $vars);

    header("Location: /control-panel?manage-topics&topic-deleted-successfully");

} else {
    header("Location: /404");
}
?>
