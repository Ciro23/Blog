<?php
if (!isset($_POST['includeCheck'])) {
    header("Location: /404");
    exit();
}

$includeCheck = true;
require "../../general/db_conn.php";
require "../../general/php-functions.php";

if (getRole($db) < 1) {
    header("Location: /404");
    exit();
}

$_SESSION['control-panel'] = "post-article";

// gets all the needed variables from post
$title = $_POST['title'];
$content = $_POST['content'];
$type = $_POST['type'];
$topic = $_POST['topic'];
$desc = $_POST['desc'];
$idPost = $_POST['idPost'];
$postOrEdit = $_POST['postOrEdit'];
$error = $_POST['error'];

// add \n to the content
$content = preg_replace("/<br>/", "\n", $content);
?>

<script>
$(document).ready(function() {
    $("#second-mega-box").fadeIn("fast");

    // gets the uploaded image name
    $("#image").change(function() {
        var fileName = $("#image").val().split("\\").pop();

        if (fileName != "") {
            $("#labelForImage").html(fileName);
        } else {
            $("#labelForImage").html("Upload an image");
        }
    });
});
</script>

<div class="mega-box" id="second-mega-box">
    <h1><?= $postOrEdit ?> an article</h1>
    <hr>
    <form action="/includes/control-panel/post-articles/post-articles-submit.php" method="post" enctype="multipart/form-data">
        <textarea name="title" placeholder="Title (Obligatory, max 100 characters)" maxlength="100"><?= $title ?></textarea>
        <textarea name="content" placeholder="Content (Obligatory)"><?= $content ?></textarea>
        <textarea name="type" rows="1" placeholder="Type of article (Obligatory, max 20 characters)" maxlength="20"><?= $type ?></textarea>
        <textarea name="desc" rows="3" placeholder="Short description (Optional, max 256 characters)" maxlength="256"><?= $desc ?></textarea>
        <span>
            <span>
                <select name="topic">
                    <option value="not-chosen" selected>Select a topic (Obligatory)</option>
                    <?php
                    $resultTopics = mysqli_query($db, $sql = "SELECT id, topic FROM topics WHERE status = 1");

                    while ($rowTopics = mysqli_fetch_assoc($resultTopics)) {
                        $topicName = $rowTopics['topic'];
                        $idTopic = $rowTopics['id'];

                        if ($topic == $idTopic) {
                            $selected = "selected";
                        } else {
                            $selected = "";
                        }

                        echo "<option value='$topicName' $selected>$topicName</option>";
                    }
                    ?>
                </select>
                <input type="file" name="image" id="image">
                <label for="image" id="labelForImage">Upload an image</label>
            </span>
            <input type="hidden" name="idPost" value="<?= $idPost ?>">
            <button type="submit" name="post-article-submit"><?= $postOrEdit ?></button>
        </span>
    </form>
    <?php
    if ($error != "") {
        echo "<p class='error'>$error</p>";
    }
    ?>
</div>