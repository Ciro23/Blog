<?php
if (isset($_POST['signup-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    // array containing all user infos
    $user = array($_POST['username'], $_POST['email'], $_POST['pwd'], $_POST['repwd']);

    // checks if slots are not empty
    if (empty($user[0]) || empty($user[1]) || empty($user[2]) || empty($user[3])) {
        header("Location: /signup?error=empty-fields&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if username is at least 4 characters long
    if (strlen($user[0]) < 4 || strlen($user[0]) > 20) {
        header("Location: /signup?error=name-length&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if the username contains special characters
    if (preg_match("/[^A-Za-z0-9\s*\-]/", $user[0])) {
        header("Location: /signup?error=name-special-characters&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if the username is already taken
    $sql = "SELECT id FROM users WHERE username = ?";
    $vars = [$user[0]];
    $varsType = "s";
    $result = executeStmt($db, $sql, $varsType, $vars);
    if (mysqli_num_rows($result) > 0) {
        header("Location: /signup?error=username-already-taken&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if email is valid
    if (!filter_var($user[1], FILTER_VALIDATE_EMAIL) || strlen($user[1]) > 128) {
        header("Location: /signup?error=email-not-valid&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if the email already exists in the db
    $sql = "SELECT id FROM users WHERE email = ?";
    $vars = [$user[1]];
    $result = executeStmt($db, $sql, $varsType, $vars);
    if (mysqli_num_rows($result) > 0) {
        header("Location: /signup?error=email-already-taken&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if password is at least 6 characters long
    if (strlen($user[2]) < 6 || strlen($user[0]) > 128) {
        header("Location: /signup?error=password-length&name=$user[0]&email=$user[1]");
        exit();
    }

    // checks if passwords match
    if ($user[2] != $user[3]) {
        header("Location: /signup?error=passwords-dont-match&name=$user[0]&email=$user[1]");
        exit();
    }

    // formatted versions of username
    $unameFormatted = format($user[0], true);

    // hashes the password
    $user[2] = password_hash($user[2], PASSWORD_DEFAULT);

    // adds the user to the db
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $vars = [$user[0], $user[1], $user[2]];
    $varsType = "sss";
    executeStmt($db, $sql, $varsType, $vars, false);

    // id of the just created user
    $uid = mysqli_insert_id($db);

    // creates the user session
    $_SESSION['uid'] = $uid;
    $_SESSION['uname'] = $user[0];

    // creates the user folder
    mkdir("../../../users/$unameFormatted-$uid");
    file_put_contents("../../../users/$unameFormatted-$uid/index.php",
"<?php
\$uidOwner = strval('$uid');
\$includeCheck = true;
include '../../includes/users/profile.php';
"
);

    // redirects
    header("Location: /users/$unameFormatted-$uid");

} else {
    header("Location: /404");
}
?>
