<?php
if (!isset($includeCheck)) {
    header("Location: /404");
    exit();
}

function format($str, $clear = false, $link = true) {
    if ($link) {
        $formatted = strtolower($str);
        $formatted = preg_replace("/\s+/", "_", $formatted);

        $str = $formatted;
    }

    if ($clear) {
        $formatted = preg_replace("/[^A-Za-z0-9_\s\-()!]/", "", $str);
    }

    return $formatted;
}

function getRole($db) {
    if (isset($_SESSION['uid'])) {
        // gets the user role
        $uid = $_SESSION['uid'];
        $result = mysqli_query($db, $sql = "SELECT role FROM users WHERE id = $uid");
        $row = mysqli_fetch_assoc($result);

        return $row['role'];
    }
    return -1;
}

function getInfo($row, $db) {
    // gets the topic name from the id
    $idTopic = $row['topic'];
    $resultTopic = mysqli_query($db, $sql = "SELECT topic FROM topics WHERE id = $idTopic");
    $rowTopic = mysqli_fetch_assoc($resultTopic);

    $info =  ["id"             => $row['id'],
              "title"          => $row['title'],
              "titleFormatted" => format($row['title']),
              "type"           => $row['type'],
              "topic"          => $rowTopic['topic'],
              "topicFormatted" => format($rowTopic['topic']),
              "desc"           => $row['description'],
              "image"          => $row['image'],
              "date"           => $row['date'],
              "url"            => "/posts/" . format($row['title'], true) . "-" . "{$row['id']}"];

    return $info;
}

function dateToTime($date) {
    $date = floor((time() - strtotime($date)) / 60);
    if ($date == 0) {
        $date = "now";
    } elseif ($date == 1) {
        $date = floor($date)." minute ago";
    } elseif ($date > 0 && $date < 60) {
        $date = floor($date)." minutes ago";
    } elseif ($date >= 60 && $date < 120) {
        $date = floor($date / 60) ." hour ago";
    } elseif ($date > 120 && $date < 1440) {
        $date = floor($date / 60) ." hours ago";
    } elseif ($date > 1440) {
        $date = floor($date / 1440) ." days ago";
    }
    return $date;
}

function resizeImage($source, $imageExt, $type) {

    if ($imageExt == "jpg" || $imageExt == "jpeg") {
        $image = imagecreatefromjpeg($source);

    } else if ($imageExt == "png") {
        $image = imagecreatefrompng($source);

    }

    // size of the uploaded image
    $size = getimagesize($source);

    $source = [];
    $offset = [0, 0];

    if ($type == "resize") {
        $newSize = [900];
    } else if ($type == "thumbnail") {
        $newSize = [430];
    }

    if ($size[0] > $newSize[0]) {
        $newSize[1] = $newSize[0] / ($size[0] / $size[1]);

        $source[0] = $size[0];
        $source[1] = $size[1];

    } else {
        $newSize = $size;
        $source = $size;
    }

    // create a resized copy of the image
    $newImage = imagecreatetruecolor($newSize[0], $newSize[1]);
    imagecopyresampled($newImage, $image, 0, 0, $offset[0], $offset[1], $newSize[0], $newSize[1], $source[0], $source[1]);

    return $newImage;
}
?>
