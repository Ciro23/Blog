<?php
$includeCheck = true;
include "../includes/general/db_conn.php";
include "../includes/general/common.php";
include "../includes/general/php-functions.php";
include "../includes/general/js-functions.php";
include "../includes/general/menu.php";
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/styles/css/404.css">
        <title>Page not found - <?= $siteName ?></title>
    </head>
    <body>
        <h1>404</h1>
        <p>page not found</p>
    </body>
</html>