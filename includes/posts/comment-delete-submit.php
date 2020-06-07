<?php
if (isset($_POST['comment-delete-submit'])) {
    $includeCheck = true;
    require "../general/db_conn.php";
    require "../general/php-functions.php";

    if (!isset($_SESSION['uid'])) {
        header("Location: /404");
        exit();
    }

    $idComment = mysqli_real_escape_string($db, $_POST['idComment']);

    // gets the post id and author
    $resultComment = mysqli_query($db, $sql = "SELECT post, author FROM comments WHERE id = '$idComment'");
    $rowComment = mysqli_fetch_assoc($resultComment);
    $idPost = $rowComment['post'];

    // gets the title of the post
    $resultPost = mysqli_query($db, $sql = "SELECT title FROM posts WHERE id = $idPost");
    $rowPost = mysqli_fetch_assoc($resultPost);
    $titlePost = $rowPost['title'];

    $titleFormatted = format($titlePost, true);

    if ($rowComment['author'] != $_SESSION['uid']) {
        header("Location: /posts/$titleFormatted-$idPost#comments");
        exit();
    }

    mysqli_query($db, $sql = "UPDATE comments SET status = 0, content = '[deleted]' WHERE id = '$idComment'");

    header("Location: /posts/$titleFormatted-$idPost#comments");
} else {
    header("Location: /404");
}
?>
