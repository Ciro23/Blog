<?php
$includeCheck = true;
require "../includes/general/db_conn.php";
require "../includes/general/common.php";
require "../includes/general/php-functions.php";
require "../includes/general/js-functions.php";

if (getRole($db) < 1) {
    header("Location: /404");
    exit();
}

// post article variables initialize
$title = "";
$content = "";
$type = "";
$topic = "";
$desc = "";
$idPost = 0;
$postOrEdit = "Post";

// manage users variables initialize
$elementsPerPage = 5;
$totalPages = 0;
$currentPage = 0;
$offset = 0;
$minPage = 0;
$maxPage = 0;
$search = "";
$where = "";
$sort = "";

// checks for every possible error
$error = "";
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        // post article errors
        case "empty-fields":
            $error = "Obligatory fields cannot be empty";
            break;
        
        case "topic-not-chosen":
            $error = "Topic is not selected";
            break;

        case "image-file-not-supported":
            $error = "Image can only be a jpg or png file";
            break;
        
        // manage topics errors
        case "empty-name":
            $error = "Topic name cannot be empty";
            break;
        
        case "invalid-name":
            $error = "Invalid topic name";
            break;

        case "topic-already-exists":
            $error = "There's already one topic with that name";
            break;
        
        case "empty-name":
            $error = "Topic name cannot be empty";
            break;
    }
}

if (isset($_GET['post-article'])) {
    // gets the post info if edit is activated
    if (isset($_GET['edit'])) {
        $postOrEdit = "Edit";

        $idPost = $_GET['idPost'];
        $resultPost = mysqli_query($db, $sql = "SELECT * FROM posts WHERE id = '$idPost'");
        $rowPost = mysqli_fetch_assoc($resultPost);

        $title = $rowPost['title'];
        $content = $rowPost['content'];
        $type = $rowPost['type'];
        $topic = $rowPost['topic'];
        $desc = $rowPost['description'];

    } else {
        $idPost = 0;
        $postOrEdit = "Post";

        // gets the form fields if some error occured
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
        }

        if (isset($_SESSION['content'])) {
            $content = $_SESSION['content'];
        }

        if (isset($_GET['type'])) {
            $type = $_GET['type'];
        }

        if (isset($_GET['topic'])) {
            $topic = $_GET['topic'];
        }

        if (isset($_GET['desc'])) {
            $desc = $_GET['desc'];
        }
    }
} else if (isset($_GET['manage-users'])) {

    // gets the user search if exists
    if (isset($_GET['search']) && $_GET['search'] != "") {
        $search = $_GET['search'];
        $where = "AND username LIKE '%$search%'";
    }

    $total = mysqli_query($db, $sql = "SELECT id FROM users WHERE status = 1 AND username != '{$_SESSION['uname']}' $where");
    $totalCount = mysqli_num_rows($total);

    $totalPages = ceil($totalCount / $elementsPerPage);

    $minPage = 1;
    $maxPage = $totalPages;

    if (!isset($_GET['page']) || empty($_GET['page'])) {
        $currentPage = 1;
    } else {
        $currentPage = abs($_GET['page']);
    }

    $offset = ($currentPage - 1) * $elementsPerPage;

    // if the user is viewing a page that doesn't exists
    if ($currentPage > $totalPages) {
        header("Location: ?manage-users&page=$totalPages");
    }

    if ($currentPage - 2 < 1 || $currentPage == 2) {
        $minPage = 1;
        if ($maxPage + 2 > $totalPages) {
            $maxPage = $totalPages;
        }
    } else {
        if ($currentPage == $totalPages - 1) {
            $minPage = $currentPage - 3;
            $maxPage = $currentPage + 1;
        } else if ($currentPage == $totalPages) {
            if ($currentPage - 4 > 0) {
                $minPage = $currentPage - 4;
            } else {
                $minPage = $currentPage - 2;
            }
            $maxPage = $currentPage;
        } else {
            $minPage = $currentPage - 2;
            $maxPage = $currentPage + 2;
        }
    }

    // gets the users sort if exists
    if (isset($_GET['sort'])) {
        $sort = strtolower($_GET['sort']);

        // the sort is invalid
        if ($sort != "alphabetically" && $sort != "newers" && $sort != "olders" && $sort != "role") {
            header("Location: ?manage-users&sort=alphabetically");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/css/control-panel.css">
        <title>Control Panel - <?= $siteName ?></title>
        <?php include_once '../includes/general/menu.php'; ?>
        <script>
        function showPostArticle(idPost, postOrEdit, title, content, type, topic, desc, error) {
            $(".load-container").load("/includes/control-panel/post-articles/post-articles.php", {
                includeCheck: true,
                idPost: idPost,
                postOrEdit: postOrEdit,
                title: title,
                content: content,
                type: type,
                topic: topic,
                desc: desc,
                error: error,
            });
        }

        function showManageTopics(error) {
            $(".load-container").load("/includes/control-panel/manage-topics/manage-topics.php", {
                includeCheck: true,
                error: error,
            });
        }

        function showManageUsers(elementsPerPage, totalPages, currentPage, offset, minPage, maxPage, search, sort) {
            $(".load-container").load("/includes/control-panel/manage-users/manage-users.php", {
                includeCheck: true,
                elementsPerPage: elementsPerPage,
                totalPages: totalPages,
                currentPage: currentPage,
                offset: offset,
                minPage: minPage,
                maxPage: maxPage,
                search: search,
                sort: sort,
            });
        }

        $(document).ready(function() {
            var url = window.location.href;
            if (url.indexOf("post-article") > -1) {
                showPostArticle("<?= $idPost ?>", "<?= $postOrEdit ?>", "<?= $title ?>", "<?= $content ?>", "<?= $type ?>", "<?= $topic ?>", "<?= $desc ?>", "<?= $error ?>");
            } else if (url.indexOf("manage-topics") > -1) {
                showManageTopics("<?= $error ?>");
            } else if (url.indexOf("manage-users") > -1) {
                showManageUsers("<?= $elementsPerPage ?>", "<?= $totalPages ?>", "<?= $currentPage ?>", "<?= $offset ?>", "<?= $minPage ?>", "<?= $maxPage ?>", "<?= $search ?>", "<?= $sort ?>");
            }
        });
        </script>
    </head>
    <body>
        <div class="mega-box" id="first-mega-box">
        <?php
        if (getRole($db) > 1) {
            echo "
            <div class='selection'>
                <a href='?post-article'>Post an article</a>
                <a href='?manage-topics'>Manage topics</a>
                <a href='?manage-users'>Manage users</a>
            </div>
            ";
        }
        ?>
            <span class="load-container"></span>
        </div>
        <?php include "../includes/general/footer.php" ?>
    </body>
</html>
