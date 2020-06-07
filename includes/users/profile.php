<?php
if (!isset($includeCheck)) {
    header("Location: /404");
    exit();
}

require "../../includes/general/db_conn.php";
require "../../includes/general/common.php";
require "../../includes/general/php-functions.php";
require "../../includes/general/js-functions.php";

$resultOwner = mysqli_query($db, $sql = "SELECT * FROM users WHERE id = $uidOwner");
$rowOwner = mysqli_fetch_assoc($resultOwner);

if ($rowOwner['status'] == 1) {
    $unameOwner = $rowOwner['username'];
} else {
    header("Location: /404");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/css/user-profile.css">
        <title><?= "$unameOwner - $siteName" ?></title>
        <script>
        $(document).ready(function() {
            var postCount = 20;
            $("#show-more").click(function() {
                postCount += 20;
                console.log(postCount);
                $(".comments").load("/includes/show-more/user-profile/user-profile.php", {
                    postCount: postCount,
                    uidOwner: "<?php print($uidOwner) ?>",
                    unameOwner: "<?php print($unameOwner) ?>",
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

                if (isset($_SESSION['uid']) && $_SESSION['uid'] == $uidOwner) {
                    echo "
                    <span class='delete-profile'>
                        <button class='button delete-button'>Delete profile</button>
                        <form action='/includes/users/user-delete-submit.php' method='post' class='delete-form'>
                            <button type='submit' class='button delete-yes' name='user-delete-submit'>Confirm delete</button>
                        </form>
                        <button class='button delete-no'>Cancel</button>
                    </span>
                    ";
                }

                ?>

            <h1><?= $unameOwner . " most recent comments" ?></h1>

            <hr>

            <?php
            $totalResult = mysqli_query($db, $sql = "SELECT id FROM comments WHERE author = $uidOwner AND status = 1");
            $totalResultCount = mysqli_num_rows($totalResult);

            $postCount = 20;

            if ($totalResultCount > 0) {
                echo "<span class='comments'>";
                include '../../includes/show-more/user-profile/user-profile-include.php';
                echo "</span>";
            } else {
                echo "<p class='no-comments'>$unameOwner hasn't commented anything yet :C</p>";
            }

            if ($totalResultCount > $postCount) {
                echo "
                <div class='show-more-flex' id='show-more-flex'>
                    <hr>
                    <button type='button' id='show-more'>Show more</button>
                    <hr>
                </div>
                ";
            }
            ?>
        </div>
        <?php include "../../includes/general/footer.php" ?>
    </body>
</html>
