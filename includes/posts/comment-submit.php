<?php
if (isset($_POST['comment-submit'])) {
    $includeCheck = true;
    require "../general/db_conn.php";
    require "../general/php-functions.php";

    if (!isset($_SESSION['uid'])) {
        header("Location: /404");
        exit();
    }

    // all comment infos
    $uid = $_SESSION['uid'];
    $comment = $_POST['comment'];
    $idPost = mysqli_real_escape_string($db, $_POST['idPost']);

    // gets the info of the post
    $resultPost = mysqli_query($db, $sql = "SELECT title, topic FROM posts WHERE id = '$idPost'");
    $rowPost = mysqli_fetch_assoc($resultPost);

    $title = $rowPost['title'];
    $titleFormatted = format($title, true);

    $idTopic = $rowPost['topic'];

    // format the comment
    $comment = preg_replace('/\r\n|\r|\n/', '<br>', $comment);

    // if the comment is empty
    if (empty($comment)) {
        $_SESSION['comment'] = $comment;
        header("Location: /posts/$titleFormatted-$idPost?error=empty-comment#comment");
        exit();
    }

    // if the comment contains html tags
    if ($comment != strip_tags($comment, "<br>")) {
        $comment = strip_tags($comment, "<br>");
        $_SESSION['comment'] = $comment;
        header("Location: /posts/$titleFormatted-$idPost?error=html-tags-not-allowed#comment");
        exit();
    }

    // if the comment is longer than the limit
    if (strlen($comment) > 2000) {
        $comment = substr($comment, 0, 2000);
    }

    $comment = mysqli_real_escape_string($db, $comment);

    // insert the comment into the db
    mysqli_query($db, $sql = "INSERT INTO comments (author, post, content, topic) VALUES ('$uid', '$idPost', '$comment', '$idTopic')");

    header("Location: /posts/$titleFormatted-$idPost#comment");
} else {
    header("Location: /404");
}
?>
