<?php
if (isset($includeCheck)) {
    $resultComments = mysqli_query($db, $sql = "SELECT * FROM comments WHERE post = $idPost AND status = 1 ORDER BY id DESC LIMIT $postCount");
    $resultCheck = mysqli_num_rows($resultComments);

    if ($resultCheck > 0) {
        $i = 0;
        while ($rowComments = mysqli_fetch_assoc($resultComments)) {
            // alternates the background color of the comment
            $i++;
            if ($i % 2 == 0) {
                $color = "alt-color";
            } else {
                $color = "";
            }

            // gets the comment's author info
            $idAuthorComment = $rowComments['author'];
            $resultCAuthor = mysqli_query($db, $sql = "SELECT username FROM users WHERE id = $idAuthorComment");
            $rowCAuthor = mysqli_fetch_assoc($resultCAuthor);
            $unameCAuthor = $rowCAuthor['username'];
            $unameCFormatted = format($unameCAuthor, true);

            $idComment = $rowComments['id'];
            $comment = $rowComments['content'];

            // checks if the user is the author of the comment
            if (isset($_SESSION['uid']) && $idAuthorComment == $_SESSION['uid'] || getRole($db) >= 2) {
                $deleteCommentBtn = "
                <form action='/includes/posts/comment-delete-submit.php' method='post'>
                    <input type='hidden' name='idComment' value='$idComment'>
                    <button type='image' name='comment-delete-submit'><img src='/png_icons/trash.png' title='Delete comment' id='delete-comment'></button>
                </form>";
            } else {
                $deleteCommentBtn = "";
            }

            echo "
            <div class='comment $color'>
                <span>
                    <h3><a href='/users/$unameCFormatted-$idAuthorComment'>$unameCAuthor</a></h3>
                    $deleteCommentBtn
                </span>
                <p>$comment</p>
            </div>
            ";
        }
    } else {
        echo "<p class='no-comments'>There are still no comments :C</p>";
    }

    $totalResult = mysqli_query($db, $sql = "SELECT id FROM comments WHERE post = $idPost AND status = 1");
    $totalResultCount = mysqli_num_rows($totalResult);
    if ($totalResultCount <= $postCount) {
        echo "
        <script>
        $('#show-more-flex').css('display', 'none');
        </script>
        ";
    }

    ?>
    <script>
    // cast the height for longer comments
    $(".comment").each(function() {
        if ($(this).height() >= 200) {
            $(this).addClass("long-comment");
        }
    });

    // restore the height for the longer comments
    $(".comment").on("click", function() {
        $(this).removeClass("long-comment");
    });
    </script>
    <?php
} else {
    header("Location: /404");
}
?>
