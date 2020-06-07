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
    $resultTopic = mysqli_query($db, $sql = "SELECT id FROM topics WHERE id = $idTopic");
    $rowTopic = mysqli_fetch_assoc($resultTopic);
    $topic = $rowTopic['id'];

    // completely formats the old name
    $topicFormatted = format($topic);

    // delets the index.php file in the topic folder
    unlink("../../../topics/$topicFormatted/index.php");
    rmdir("../../../topics/$topicFormatted");

    // hides everything about the topic in the db
    mysqli_query($db, $sql = "UPDATE topics SET status = 0 WHERE id = '$idTopic'");
    mysqli_query($db, $sql = "UPDATE posts SET status = 0 WHERE topic = '$idTopic'");
    mysqli_query($db, $sql = "UPDATE comments SET status = 0 WHERE topic = '$idTopic'");

    //header("Location: /control-panel?manage-topics&topic-deleted-successfully");

} else {
    header("Location: /404");
}
?>
