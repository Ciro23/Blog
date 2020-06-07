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

// gets the name and the email if some error occured
if (isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    $name = "";
}

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

        case "name-length":
            $error = "<p class='error'>The username length must be between 4 and 20 characters</p>";
            break;

        case "name-special-characters":
            $error = "<p class='error'>Name can't contains special characters</p>";
            break;

        case "username-already-taken":
            $error = "<p class='error'>An account is already using this username</p>";
            break;

        case "email-not-valid":
            $error = "<p class='error'>Email format is not valid</p>";
            break;

        case "email-already-taken":
            $error = "<p class='error'>An account is already using this email</p>";
            break;

        case "password-length":
            $error = "<p class='error'>The password length must be between 6 and 128 characters</p>";
            break;

        case "passwords-dont-match":
            $error = "<p class='error'>Passwords don't match</p>";
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
        <title><?php echo $siteName ?> | Signup</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
        $(document).ready(function() {
            if ($(window).width() > 599) {
                $(".signup-login").animate({
                    top: "52%",
                    opacity: 1,
                }, "slow");
            }

            $(window).resize(function() {
                if ($(window).width() > 599) {
                    $(".signup-login").css("opacity", "100%");
                }
            });
            
        });
        </script>
    </head>
    <body>
        <?php include "../includes/general/menu.php" ?>
        <div class="signup-login">
            <div class="signup">
                <div class="first-flex">
                    <img src="/includes/general/logo.jpg">
                    <h2>Signup on <?php echo $siteName ?></h2>
                    <?php
                    if (isset($error)) {
                        echo "<p class='error'>$error</p>";
                    }
                    ?>
                    <form action="/includes/users/signup-login/signup-submit.php" method="post">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="username" placeholder="Minimum 4 characters, no special characters" value="<?php echo $name ?>" maxlength="20">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="A valid email address" value="<?php echo $email ?>" maxlength="128">
                        <label for="pwd">Password</label>
                        <input type="password" id="pwd" name="pwd" placeholder="Minimum 6 characters" maxlength="128">
                        <label for="repwd">Confirm Password</label>
                        <input type="password" id="repwd" name="repwd" placeholder="Minimum 6 characters" maxlength="128">
                        <input type="submit" name="signup-submit" value="Signup">
                    </form>
                </div>
            </div>
            <div class="utilities">
                <a href="/login">Want to login?</a>
            </div>
        </div>
    </body>
</html>
