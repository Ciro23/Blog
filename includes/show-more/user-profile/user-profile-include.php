<?php
if (isset($includeCheck)) {
    $result = mysqli_query($db, $sql = "SELECT * FROM comments WHERE author = $uidOwner AND status = 1 ORDER BY id DESC LIMIT $postCount");
    while ($row = mysqli_fetch_assoc($result)) {
        $comment = $row['content'];
        $idPost = $row['post'];

        // gets the post title from the db
        $resultPost = mysqli_query($db, $sql = "SELECT title FROM posts WHERE id = $idPost");
        $rowPost = mysqli_fetch_assoc($resultPost);

        // saves the title and the formatted title of the post
        $title = $rowPost['title'];
        $titleFormatted = format($title, true);

        // cuts the content after 300 characters
        if (strlen($comment) > 300) {
            $comment = substr($comment, 0, 300);
            $comment .= "...";
        }

        echo "
        <a href='/posts/$titleFormatted-$idPost'>
            <p class='title'>$title</p>
            <p class='comment'>$comment</p>
        </a>
        ";
    }

    $totalResult = mysqli_query($db, $sql = "SELECT id FROM comments WHERE author = $uidOwner AND status = 1");
    $totalResultCount = mysqli_num_rows($totalResult);
    if ($totalResultCount <= $postCount) {
        echo "
        <script>
        $('#show-more-flex').css('display', 'none');
        </script>
        ";
    }
} else {
    header("Location: /404");
}
?>
