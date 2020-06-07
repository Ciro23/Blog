<?php
if (isset($includeCheck)) {
    /*
    these 3 bunches of codes need to delete from the session the content of the post
    or the comment if the user leaves the working page
    */
    // current url
    $url = $_SERVER['REQUEST_URI'];
    $urlExploded = explode("/", $url);
    $firstPartOfUrl = $urlExploded[1];

    // deletes content session variable
    if (strpos($url, "/control-panel/?post-article") !== 0) {
        $_SESSION['content'] = "";
    }

    // deletes comment session variable
    if ($firstPartOfUrl != "posts") {
        $_SESSION['comment'] = "";
    }

    // website name
    $siteName = "Website";
} else {
    header("Location: /404");
    exit();
}
?>

<!-- jquery script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,531;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,531;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

<!-- common style files -->
<link rel="stylesheet" href="/styles/css/common.css">
<link rel="stylesheet" href="/styles/css/menu.css">
<link rel="stylesheet" href="/styles/css/footer.css">
