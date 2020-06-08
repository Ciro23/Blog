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

    $sql = "SELECT title, author FROM posts WHERE id = ?";
    $vars = [$idPost];
    $varsType = "i";
    $resultPost = executeStmt($db, $sql, $varsType, $vars);

    $rowPost = mysqli_fetch_assoc($resultPost);

    // checks if the user deleting the post is its author
    if ($_SESSION['uid'] != $rowPost['author']) {
        $titleFormatted = format($rowPost['title'], true);
        header("Location: /posts/$titleFormatted-$idPost");
    }

    $sql = "UPDATE posts SET status = 0 WHERE id = ?";
    $vars = [$idPost];
    $varsType = "i";
    executeStmt($db, $sql, $varsType, $vars, false);

    $sql = "UPDATE comments SET status = 0 WHERE post = ?";
    executeStmt($db, $sql, $varsType, $vars, false);

    header("Location: /");
} else {
    header("Location: /404");
}
?>
