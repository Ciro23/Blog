<?php
if (isset($_POST['delete-submit'])) {
    $includeCheck = true;
    require "../general/db_conn.php";
    require "../general/php-functions.php";

    if (!isset($_SESSION['uid'])) {
        header("Location: /404");
        exit();
    }

    if (getRole($db) < 1) {
        header("Location: /404");
        exit();
    }

    $idPost = $_POST['idPost'];

    $resultPost = mysqli_query($db, $sql = "SELECT title, author, topic FROM posts WHERE id = $idPost");
    $rowPost = mysqli_fetch_assoc($resultPost);

    // checks if the user deleting the post is its author
    if ($_SESSION['uid'] != $rowPost['author']) {
        $titleFormatted = format($rowPost['title'], true);
        header("Location: /posts/$titleFormatted-$idPost");
    }

    $idTopic = $rowPost['topic'];
    mysqli_query($db, $sql = "UPDATE posts SET status = 0 WHERE id = $idPost");
    mysqli_query($db, $sql = "UPDATE comments SET status = 0 WHERE post = $idPost");

    header("Location: /");
} else {
    header("Location: /404");
}
?>
