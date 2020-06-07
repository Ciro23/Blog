<?php
if (isset($includeCheck)) {
    $result = mysqli_query($db, $sql = "SELECT * FROM posts WHERE status = 1 $mysqlWhere ORDER BY id DESC LIMIT $postCount OFFSET $offset");
    while ($row = mysqli_fetch_assoc($result)) {
        $offset++;
        $info = getInfo($row, $db);
        $time = dateToTime($info['date']);
        echo "
        <div class='grid-box'>
            <div class='category'>
                <a href='{$info['url']}'><p>{$info['topic']}</p></a>
            </div>
            <div class='img-box'>
                <a href='{$info['url']}'>
                    <div class='img' style='background-image: url({$info['url']}/thumbnail.{$info['image']})'></div>
                </a>
            </div>
            <h2>
                <a href='{$info['url']}'>{$info['title']}</a>
            </h2>
            <p>{$info['type']} - $time</p>
        </div>
        ";
    }

    $totalResult = mysqli_query($db, $sql = "SELECT id FROM posts WHERE status = 1 $mysqlWhere");
    $totalResultCount = mysqli_num_rows($totalResult);
    if ($totalResultCount - $offset <= $postCount) {
        $calc = $totalResultCount - $offset;
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
