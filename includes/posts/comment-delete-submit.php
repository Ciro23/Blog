<?php
if (isset($_POST['comment-delete-submit'])) {
    $includeCheck = true;
    require "../general/db_conn.php";
    require "../general/php-functions.php";

    if (!isset($_SESSION['uid'])) {
        header("Location: /404");
        exit();
    }

    $idComment = $_POST['idComment'];

    // gets the post id and author
    $sql = "SELECT post, author FROM comments WHERE id = ?";
    $vars = [$idComment];
    $varsType = "i";
    $resultComment = executeStmt($db, $sql, $varsType, $vars);

    $rowComment = mysqli_fetch_assoc($resultComment);
    $idPost = $rowComment['post'];

    // gets the title of the post
    $resultPost = mysqli_query($db, $sql = "SELECT title FROM posts WHERE id = $idPost");
    $rowPost = mysqli_fetch_assoc($resultPost);
    $titlePost = $rowPost['title'];

    $titleFormatted = format($titlePost, true);

    // checks if the user deleting the comment is its author or an admin
    if ($rowComment['author'] != $_SESSION['uid'] && getRole($db) < 2) {
        header("Location: /posts/$titleFormatted-$idPost#comments");
        exit();
    }

    $sql = "UPDATE comments SET status = 0, content = '[deleted]' WHERE id = ?";
    executeStmt($db, $sql, $varsType, $vars, false);

    header("Location: /posts/$titleFormatted-$idPost#comments");
} else {
    header("Location: /404");
}
?>
