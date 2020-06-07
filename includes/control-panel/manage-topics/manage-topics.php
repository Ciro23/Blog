<?php
if (!isset($_POST['includeCheck'])) {
    header("Location: /404");
    exit();
}

$includeCheck = true;
require "../../general/db_conn.php";
require "../../general/php-functions.php";

if (getRole($db) < 2) {
    header("Location: /404");
    exit();
}

$error = $_POST['error'];
$_SESSION['control-panel'] = "manage-topics";
?>

<script>
$(document).ready(function() {
    $("#second-mega-box").fadeIn("fast");
});
</script>

<div class="mega-box" id="second-mega-box">
    <h1>Manage topics</h1>
    <form class="first-form" action="/includes/control-panel/manage-topics/add-topic-submit.php" method="post">
        <input type="text" name="name" placeholder="Max 20 characters" maxlength="20">
        <button type="submit" name="add-topic-submit">Add topic</button>
    </form>
    <?php
    if ($error != "") {
        echo "<p class='error' style='margin: 0 0 10px;'>$error</p>";
    }
    ?>
    <hr id="hr-topics-users">
    <?php
    $resultTopics = mysqli_query($db, $sql = "SELECT * FROM topics WHERE status = 1");
    $i = 0;
    while ($rowTopics = mysqli_fetch_assoc($resultTopics)) {
        $i++;

        if ($i % 2 == 0) {
            $altColor = "alt-color";
        } else {
            $altColor = "";
        }

        $idTopic = $rowTopics['id'];
        $topic = $rowTopics['topic'];

        // counts the number of posts for this topic
        $resultPosts = mysqli_query($db, $sql = "SELECT id FROM posts WHERE topic = $idTopic AND status = 1");
        $nPosts = mysqli_num_rows($resultPosts);

        echo "
        <div class='box $altColor'>
            <span>
                <form action='/includes/control-panel/manage-topics/edit-topic-name-submit.php' method='post'>
                    <input type='hidden' name='oldName' value='$topic'>
                    <input type='text' name='newName' value='$topic' maxlength='20' placeholder='Max 20 characters'>
                    <button type='image' name='edit-topic-name-submit' title='Edit name'>
                        <img src='/png_icons/edit.png'>
                    </button>
                </form>
            </span>

            <p>Active posts: $nPosts</p>

            <form action='/includes/control-panel/manage-topics/delete-topic-submit.php' method='post' class='side-form'>
                <input type='hidden' name='id' value='$idTopic'>
                <button type='image' name='delete-topic-submit' title='Delete topic'>
                    <img src='/png_icons/trash.png'>
                </button>
            </form>
        </div>
        ";
    }
    ?>
</div>
