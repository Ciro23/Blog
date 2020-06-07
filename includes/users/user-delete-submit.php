<?php
if (isset($_POST['user-delete-submit'])) {
    $includeCheck = true;
    require "../general/db_conn.php";

    $idUser = $_SESSION['uid'];

    // hides everything about the user in the db
    mysqli_query($db, $sql = "UPDATE posts SET status = 0 WHERE author = $idUser");
    mysqli_query($db, $sql = "UPDATE posts SET status = 0 WHERE author = $idUser");
    mysqli_query($db, $sql = "UPDATE comments SET status = 0, content = '[deleted]' WHERE author = $idUser");
    mysqli_query($db, $sql = "UPDATE users SET status = 0, email = '[deleted]', password = '[deleted]' WHERE id = $idUser");

    // deletes the cookies
    session_destroy();

    header("Location: /404");
} else {
    header("Location: /404");
}
?>
