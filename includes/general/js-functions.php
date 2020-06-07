<?php
if (!isset($includeCheck)) {
    header("Location: /404");
    exit();
}
?>

<script>
// checks if a cookie exists and return its value
function findCookie(cookieToFind) {
    var cookies = document.cookie.split("; ");
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split("=");
        if (cookie[0] == cookieToFind) {
            return cookie[1];
        }
    }
    return "";
}

$(document).ready(function() {
    // set the user dropdown width based on the username length
    $("#user-dropdown").css("width", $(".user-menu").width() + 5);

    // delete button
    $(".delete-button").click(function() {
        $(".delete-button").hide();
        $(".delete-yes").show();
        $(".delete-no").show();
    });

    $(".delete-no").click(function() {
        $(".delete-yes").hide();
        $(".delete-no").hide();
        $(".delete-button").show();
    });

    // menu dropdown
    $(".user-menu").on("click", function(e) {
        e.stopPropagation();
        $("#user-dropdown").toggle();
    });

    // topics menu
    $("#topics-menu").on("click", function(e) {
        e.stopPropagation();
        $("#topics-dropdown").toggle();
    });

    // hides menu user dropdown and topics menu if clicking somewhere
    $("html").on("click", function() {
        $("#user-dropdown").hide();
        $("#topics-dropdown").hide();
    });

    // if dark cookie is set, change the theme to dark
    if (findCookie("theme") == "dark") {
        $("html").addClass("dark");
    }

    // switch from light to dark theme
    $("#changeTheme").click(function() {
        var theme = findCookie("theme");
        if (theme == "light" || theme == "") {
            $("html").addClass("dark");
            document.cookie = "theme=light;expires=Thu, 03 Sep 2025 05:33:44 UTC; path=/";
            document.cookie = "theme=dark;expires=Thu, 03 Sep 2025 05:33:44 UTC; path=/";
        } else if (theme == "dark") {
            $("html").removeClass("dark");
            document.cookie = "theme=dark;expires=Thu, 03 Sep 2025 05:33:44 UTC; path=/";
            document.cookie = "theme=light;expires=Thu, 03 Sep 2025 05:33:44 UTC; path=/";
        }
    });
});
</script>
