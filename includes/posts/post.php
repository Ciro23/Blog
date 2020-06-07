<?php
if (!isset($includeCheck)) {
    header("Location: /404");
    exit();
}

require "../../includes/general/db_conn.php";
require "../../includes/general/common.php";
require "../../includes/general/php-functions.php";
require "../../includes/general/js-functions.php";

$resultPost = mysqli_query($db, $sql = "SELECT * FROM posts WHERE id = $idPost");
$rowPost = mysqli_fetch_assoc($resultPost);

if ($rowPost['status'] == 0) {
    header("Location: /404");
    exit();
}

// gets post info
$titlePost = $rowPost['title'];
$contentPost = $rowPost['content'];
$imagePost = $rowPost['image'];
$uidAuthor = $rowPost['author'];
$datePost = $rowPost['date'];
$datePost = date("M d, Y", strtotime($datePost));

// gets author info
$resultAuthor = mysqli_query($db, $sql = "SELECT * FROM users WHERE id = $uidAuthor");
$rowAuthor = mysqli_fetch_assoc($resultAuthor);

$statusAuthor = $rowAuthor['status'];
$roleAuthor = $rowAuthor['role'];

$commentError = "";
$commentTA = "";
if (isset($_GET['error'])) {

    $error = $_GET['error'];
    $commentTA = $_SESSION['comment'];

    // add \n to the comment
    $commentTA = preg_replace("/<br>/", "\n", $commentTA);

    switch ($error) {
        case 'html-tags-not-allowed':
            $commentError = '<p class="error">HTML Tags are not allowed!</p>';
            break;

        case 'empty-comment':
            $commentError = '<p class="error">Comment cannot be empty!</p>';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/css/article.css">
        <title><?= "$titlePost - $siteName" ?></title>
        <script>
        $(document).ready(function() {
            console.log($(".mega-box").width());
            // initial value of the characters counter for the comment textarea
            var characters = $(".comment-textarea").val().length;
            $("#current").text(characters);

            // update the characters counter for the comment textarea
            $(".comment-textarea").keyup(function() {
                var characters = $(this).val().length;
                $("#current").text(characters);
            });

            // shows the delete button for every user's comment
            $(document).on("mouseenter", ".comment", function() {
                if ($(window).width() > 599) {
                    $(this).find("button[name='comment-delete-submit']").css("display", "inline");
                }
            });

            $(document).on("mouseleave", ".comment", function() {
                if ($(window).width() > 599) {
                    $(this).find("button[name='comment-delete-submit']").css("display", "none");
                }
            });

            // display or hide the delete button based on the window width
            $(window).on("resize", function() {
                if ($(window).width() <= 599) {
                    $(document).find("button[name='comment-delete-submit']").css("display", "block");
                } else {
                    $(document).find("button[name='comment-delete-submit']").css("display", "none");
                }
            });

            var postCount = 20;
            $("#show-more").click(function() {
                postCount += 20;
                $("#comments").load("/includes/show-more/post/post.php", {
                    postCount: postCount,
                    idPost: "<?php print($idPost) ?>",
                    includeCheck: true,
                });
            });
        });
        </script>
    </head>
    <body>
        <?php include "../../includes/general/menu.php" ?>
        <div class="mega-box">
            <?php
            if (isset($_SESSION['uid']) && $uidAuthor == $_SESSION['uid'] || getRole($db) >= 2) {
                echo "
                <div class='delete-buttons'>
                    <form action='/control-panel?post-article&edit&idPost=$idPost&load' method='post'>
                        <input type='hidden' name='idPost' value='$idPost'>
                        <button type='submit' class='button edit-button'>Edit post</button>
                    </form>

                    <span>
                        <button class='button delete-button'>Delete post</button>
                        <form action='/includes/posts/post-delete-submit.php' method='post' class='delete-form'>
                            <input type='hidden' name='idPost' value='$idPost'>
                            <button type='submit' class='button delete-yes' name='delete-submit'>Confirm delete</button>
                        </form>
                        <button class='button delete-no'>Cancel</button>
                    </span>
                </div>
                ";
            }
            ?>

            <h1><?= $titlePost ?></h1>

            <?php
            if ($statusAuthor == 1) {
                $unameAuthor = $rowAuthor['username'];
                $unameAuthorFormatted = format($unameAuthor, true);

                echo "<p class='author'><a href='/users/$unameAuthorFormatted-$uidAuthor'>$unameAuthor</a> • $datePost </p>";
            }
            ?>

            <img src="<?= "resized." . $imagePost ?>">
            <p><?= $contentPost ?></p>

            <hr>

            <h2>User comments</h2>
            <div id="comments">
                <?php
                // comments section
                $postCount = 20;
                include "../../includes/show-more/post/post-include.php";
                ?>
            </div>

            <?php
            $totalResult = mysqli_query($db, $sql = "SELECT id FROM comments WHERE post = $idPost AND status = 1");
            $totalResultCount = mysqli_num_rows($totalResult);
            if ($totalResultCount > $postCount) {
                echo "
                <div class='show-more-flex' id='show-more-flex'>
                    <hr>
                    <button type='button' id='show-more'>Show more</button>
                    <hr>
                </div>
                ";
            }

            if (isset($_SESSION['uid'])) {

                // get the user banned status
                $uid = $_SESSION['uid'];
                $resultUser = mysqli_query($db, $sql = "SELECT isBanned FROM users WHERE id = $uid");
                $rowUser = mysqli_fetch_assoc($resultUser);

                // checks if user is not banned
                if (!$rowUser['isBanned']) {

                    if (isset($_GET['comment'])) {
                        $comment = $_GET['comment'];
                    } else {
                        $comment = "";
                    }
                    
                    echo "
                    <span class='char-count'>
                        <p id='current'>0</p><p>/2000</p>
                    </span>
                    <form action='/includes/posts/comment-submit.php' method='post' id='comment'>
                        <textarea name='comment' placeholder='Write here your comment... (max 2000 characters)' class='comment-textarea' maxlength='2000'>$commentTA</textarea>
                        <input type='hidden' name='idPost' value='$idPost'>

                        $commentError

                        <input type='submit' name='comment-submit' value='Send' class='comment-submit'>
                    </form>
                    ";
                }

            } else {
                echo "
                <div class='comment-lg-sg'>
                    <p>In order to post your comment, you must be logged in</p>
                    <div class='a-lg-sg-flex'>
                        <a href='/login'>Login</a>
                        <a href='/signup'>Signup</a>
                    </div>
                </div>";
            }
            ?>
        </div>
        <?php include "../../includes/general/footer.php" ?>
    </body>
</html>