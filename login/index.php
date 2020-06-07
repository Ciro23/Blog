<?php
$includeCheck = true;
require "../includes/general/db_conn.php";
require "../includes/general/common.php";
require "../includes/general/php-functions.php";
require "../includes/general/js-functions.php";

// if the user is already logged in
if (isset($_SESSION['uid'])) {
    header("Location: /");
}

// gets the email if some error occured
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    $email = "";
}

// errors handler
if (isset($_GET["error"])) {
    switch ($_GET['error']) {
        case "empty-fields":
            $error = "<p class='error'>All fields must be filled</p>";
            break;

        case "email-not-valid":
            $error = "<p class='error'>Email format is not valid</p>";
            break;

        case "user-not-found":
            $error = "<p class='error'>The email or password are wrong</p>";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/css/login-signup.css">
        <title><?php echo $siteName ?> | Login</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
        $(document).ready(function() {
            if ($(window).width() > 599) {
                $(".signup-login").animate({
                    top: "50%",
                    opacity: 1,
                }, "slow");
            }
        });
        </script>
    </head>
    <body>
        <?php include "../includes/general/menu.php" ?>
        <div class="signup-login">
            <div class="login">
                <div class="first-flex">
                    <img src="/includes/general/logo.jpg">
                    <h2>Login on <?php echo $siteName ?></h2>
                    <?php
                    if (isset($error)) {
                        echo "<p class='error'>$error</p>";
                    }
                    ?>
                    <form action="/includes/users/signup-login/login-submit.php" method="post">
                        <input type="text" name="email" placeholder="Email" value="<?php echo $email ?>">
                        <input type="password" name="pwd" placeholder="Password">
                        <input type="submit" name="login-submit" value="Login">
                    </form>
                </div>
            </div>
            <div class="utilities">
                <a href="/signup">Need to signup?</a>
            </div>
        </div>
    </body>
</html>
