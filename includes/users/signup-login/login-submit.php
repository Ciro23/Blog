<?php
if (isset($_POST['login-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    $user = array($_POST['email'], $_POST['pwd']);

    // checks if slots are not empty
    if (empty($user[0]) || empty($user[1])) {
        header("Location: /login?error=empty-fields&email=$user[0]");
        exit();
    }

    $result = mysqli_query($db, $sql = "SELECT id, username, password FROM users WHERE email = '$user[0]' AND status = 1");
    $row = mysqli_fetch_assoc($result);

    // checks if the user exists in the db
    if (mysqli_num_rows($result) > 0 && password_verify($user[1], $row['password'])) {

        $uid = $row['id'];
        $uname = $row['username'];
        $unameFormatted = format($uname, true);

        // creates the user session
        $_SESSION['uid'] = $uid;
        $_SESSION['uname'] = $uname;

        header("Location: /users/$unameFormatted-$uid");
    } else {
        header("Location: /login?error=user-not-found&email=$user[0]");
    }
} else {
    header("Location: /404");
}
?>
