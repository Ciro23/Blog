<?php
if (!isset($includeCheck)) {
    header("Location: /404");
}

if (isset($idTopic)) {
    $directory = "../../";
} else {
    $pageTitle = "Home";
    $mysqlWhere = "";
    $directory = "";
}

require $directory . "includes/general/db_conn.php";
require $directory . "includes/general/common.php";
require $directory . "includes/general/php-functions.php";
require $directory . "includes/general/js-functions.php";

if (isset($idTopic)) {
    $resultTopic = mysqli_query($db, $sql = "SELECT topic FROM topics WHERE id = $idTopic");
    $rowTopic = mysqli_fetch_assoc($resultTopic);

    $topicName = $rowTopic['topic'];

    $h1 = $topicName;
    $pageTitle = $topicName;
    $mysqlWhere = "AND topic = '$idTopic'";
} else {
    $h1 = "All most recent articles from $siteName";
}

$totalResult = mysqli_query($db, $sql = "SELECT id FROM posts WHERE status = 1 $mysqlWhere");
$totalResultCount = mysqli_num_rows($totalResult);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/css/home.css">
        <title><?= $pageTitle . " - " . $siteName ?></title>
        <script>
        $(document).ready(function() {
            $(".card").hover(function() {
                $(this).find(".img").css("transform", "scale(1.15)");
            }, function() {
                $(this).find(".img").css("transform", "scale(1)");
            });

            var postCount = 20;
            $("#show-more").click(function() {
                postCount += 20;
                $(".grid-container").load("/includes/show-more/home/home.php", {
                    postCount: postCount,
                    offset: 13,
                    mysqlWhere: "<?php print($mysqlWhere); ?>",
                    includeCheck: true,
                });
            });
        });
        </script>
    </head>
    <body>
        <?php include $directory . "includes/general/menu.php" ?>
        <div class="mega-box">
            <div class="recent-box">
                <h1><?= $h1 ?></h1>

                <?php
                if ($totalResultCount == 0) {
                    echo "<p>There are still no posts for this topic :C</p>";
                } else {
                    
                    echo "<span>";
                        $offset = 0;

                        $result = mysqli_query($db, $sql = "SELECT * FROM posts WHERE status = 1 $mysqlWhere ORDER BY id DESC LIMIT 3");
                        while ($row = mysqli_fetch_assoc($result)) {
                            $offset++;

                            $info = getInfo($row, $db);
                            echo "
                            <div class='first-recent'>
                                <a href='{$info['url']}'>
                                    <div class='img' style='background-image: url({$info['url']}/thumbnail.{$info['image']})'></div>
                                </a>
                                <div class='info'>
                                    <div class='category'>
                                        <a href='/topics/{$info['topicFormatted']}'><p>{$info['topic']}</p></a>
                                    </div>
                                    <p>{$info['type']}</p>
                                    <h2><a href='{$info['url']}'>{$info['title']}</a></h2>
                                </div>
                            </div>
                            ";
                        }
                    echo "</span>";
                }
                ?>
            </div>

            <?php
            if ($totalResultCount > 3) {
                ?>
                <div class="recent-box">
                    <span>
                        <?php
                        $result = mysqli_query($db, $sql = "SELECT * FROM posts WHERE status = 1 $mysqlWhere ORDER BY id DESC LIMIT 3 OFFSET $offset");
                        while ($row = mysqli_fetch_assoc($result)) {
                            $offset++;

                            $info = getInfo($row, $db);
                            echo "
                            <div class='second-recent'>
                                <a href='{$info['url']}'>
                                    <div class='img' style='background-image: url({$info['url']}/thumbnail.{$info['image']})'></div>
                                </a>
                                <div class='info'>
                                    <div class='category'>
                                        <a href='/topics/{$info['topicFormatted']}'><p>{$info['topic']}</p></a>
                                    </div>
                                    <p>{$info['type']}</p>
                                    <h2>
                                        <a href='{$info['url']}'>{$info['title']}</a>
                                    </h2>
                                </div>
                            </div>
                            ";
                        }
                        ?>
                    </span>
                </div>
                <?php
            }

            if ($totalResultCount > 6) {
                ?>
                <hr>

                <div class="col-side-flex">
                    <div class="column-container">
                        <?php
                        $result = mysqli_query($db, $sql = "SELECT * FROM posts WHERE status = 1 $mysqlWhere ORDER BY id DESC LIMIT 3 OFFSET $offset");
                        while ($row = mysqli_fetch_assoc($result)) {
                            $offset++;
                            $info = getInfo($row, $db);
                            $time = dateToTime($info['date']);
                            echo "
                            <div class='column-box'>
                                <div class='category'>
                                    <a href='/topics/{$info['topicFormatted']}'><p>{$info['topic']}</p></a>
                                </div>
                                <div class='column-flex'>
                                    <div class='info'>
                                        <h2>
                                            <a href='{$info['url']}'>{$info['title']}</a>
                                        </h2>
                                        <p>{$info['desc']}</p>
                                        <p>{$info['type']} - $time</p>
                                    </div>
                                    <div class='img-box'>
                                        <a href='{$info['url']}'>
                                            <div class='img' style='background-image: url({$info['url']}/thumbnail.{$info['image']})'></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                        ?>
                    </div>

                    <?php
                    if ($totalResultCount >= 9) {
                        ?>
                        <div class="side-container">
                            <h2>Articles you may like</h2>
                            <?php
                            $result = mysqli_query($db, $sql = "SELECT * FROM posts WHERE status = 1 $mysqlWhere ORDER BY RAND() LIMIT 8");
                            while ($row = mysqli_fetch_assoc($result)) {
                                $info = getInfo($row, $db);
                                echo "
                                <div class='side-box'>
                                    <a href='{$info['url']}'>
                                        <div class='img' style='background-image: url({$info['url']}/thumbnail.{$info['image']})'></div>
                                    </a>
                                    <h2>
                                        <a href='{$info['url']}'>{$info['title']}</a>
                                    </h2>
                                </div>
                                ";
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>

            <?php
            if ($totalResultCount >= 13) {
                ?>
                <hr>

                <div class="cards-container">
                    <?php
                    $result = mysqli_query($db, $sql = "SELECT * FROM posts WHERE status = 1 $mysqlWhere ORDER BY id DESC LIMIT 4 OFFSET $offset");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $offset++;
                        $info = getInfo($row, $db);
                        echo "
                        <div class='card'>
                            <a href='{$info['url']}'>
                                <div class='img' style='background-image: url({$info['url']}/thumbnail.{$info['image']})'></div>
                                <div class='p-shadow'>
                                    <p><b>{$info['topic']}</b><br>{$info['type']}</p>
                                </div>
                                <div class='h2-shadow'>
                                    <h2>{$info['title']}</h2>
                                </div>
                            </a>
                        </div>
                        ";
                    }
                    ?>
                </div>
                <?php
            }

            if ($totalResultCount > 9) {
                ?>
                <hr>
                <div class="grid-container">
                    <?php
                    $offsetBefore = $offset;
                    $postCount = 20;
                    include $directory . "includes/show-more/home/home-include.php";
                    ?>
                </div>
                <?php
                if ($totalResultCount - $offsetBefore > $postCount) {
                    echo "
                    <div class='show-more-flex' id='show-more-flex'>
                        <hr>
                        <button type='button' id='show-more'>Show more</button>
                        <hr>
                    </div>
                    ";
                }
            }
            ?>
        </div>
        <?php include $directory . "includes/general/footer.php" ?>
    </body>
</html>
