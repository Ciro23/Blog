<?php
if (isset($_POST['post-article-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    if (getRole($db) < 1) {
        header("Location: /404");
        exit();
    }

    // if the user is editing the post
    if ($_POST['idPost'] != 0) {
        $idRedirect = $_POST['idPost'];
        $redirect = "&edit&idPost=$idRedirect";
    } else {
        $redirect = "";
    }

    // variables containing all post infos
    $uid = $_SESSION['uid'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $type = $_POST['type'];
    $desc = $_POST['desc'];
    $idTopic = $_POST['topic'];
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];

    // format the content
    $content = preg_replace('/\r\n|\r|\n/', '<br>', $content);

    // if not every field is filled
    if (empty($title) || empty($content) || empty($type)) {
        $_SESSION['content'] = $content;
        header("Location: /control-panel?post-article&error=empty-fields$redirect&title=$title&type=$type&topic=$idTopic&desc=$desc");
        exit();
    }

    // if the topic hasn't been chosen
    if ($idTopic == "not-chosen") {
        $_SESSION['content'] = $content;
        header("Location: /control-panel?post-article&error=topic-not-chosen$redirect&title=$title&type=$type&topic=$idTopic&desc=$desc");
        exit();
    }

    // if the image is uploaded
    if (is_uploaded_file($imageTmp)) {
        // if the image is not a supported file
        $imageExt = pathinfo($image, PATHINFO_EXTENSION);
        if ($imageExt != "jpg" && $imageExt != "jpeg" && $imageExt != "png") {
            $_SESSION['content'] = $content;
            header("Location: /control-panel?post-article&error=image-file-not-supported$redirect&title=$title&type=$type&topic=$idTopic&desc=$desc");
            exit();
        }
        $imageSource = $imageTmp;
    } else {
        $imageExt = "jpg";
        if (isset($_SERVER['HTTPS'])) {
            $protocol = "https://";
        } else {
            $protocol = "http://";
        }
        $imageSource = "$protocol{$_SERVER['HTTP_HOST']}/includes/general/post_default.$imageExt";
    }


    // format the title
    $titleFormatted = format($title, true);

    // insert data into the db if the post is being created
    if ($_POST['idPost'] == 0) {
        $sql = "INSERT INTO posts (author, title, content, topic, type, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $vars = [$uid, $title, $content, $idTopic, $type, $desc, $imageExt];
        $varsType = "ississs";
        executeStmt($db, $sql, $varsType, $vars, false);

        // id of the just created article
        $idPost = mysqli_insert_id($db);
    } else {
        // edit the data if the post is being edited
        $idPost = $_POST['idPost'];

        $sql = "SELECT title, image FROM posts WHERE id = ?";
        $vars = [$idPost];
        $varsType = "i";
        $resultOld = executeStmt($db, $sql, $varsType, $vars);

        $rowOld = mysqli_fetch_assoc($resultOld);
        $oldTitle = $rowOld['title'];
        $oldTitle = format($oldTitle, true);

        $sql = "UPDATE posts SET title = ?, content = ?, topic = ?, type = ?, description = ?, image = ? WHERE id = ?";
        $vars = [$title, $content, $idTopic, $type, $desc, $imageExt, $idPost];
        $varsType = "ssisssi";
        executeStmt($db, $sql, $varsType, $vars, false);
    }

    $target = "../../../posts/$titleFormatted-$idPost";

    // create post folder and page if the post is being created
    if ($_POST['idPost'] == 0) {
        mkdir($target);
        file_put_contents(
            "$target/index.php",
            "<?php
\$idPost = strval('$idPost');
\$includeCheck = true;
include '../../includes/posts/post.php';
"
        );
    } else {
        // renames the post folder if the post is being edited
        rename("../../../posts/$oldTitle-$idPost", $target);
    }

    // create the post image and moves it into its folder
    $resized = resizeImage($imageSource, $imageExt, "resize");
    $thumbnail = resizeImage($imageSource, $imageExt, "thumbnail");

    if ($imageExt == "jpg" || $imageExt == "jpeg") {
        imagejpeg($resized, "$target/resized.$imageExt", 100);
        imagejpeg($thumbnail, "$target/thumbnail.$imageExt", 90);
    } else if ($imageExt == "png") {
        imagepng($resized, "$target/resized.$imageExt", 100);
        imagepng($thumbnail, "$target/thumbnail.$imageExt", 90);
    }

    imagedestroy($resized);
    imagedestroy($thumbnail);

    header("Location: /posts/$titleFormatted-$idPost");
} else {
    header("Location: /404");
}
