<?php
if (!isset($includeCheck)) {
    header("Location: /404");
    exit();
}
?>
<div class="menu">
    <div class="menu-box">
        <a href="/"><img src="/png_icons/home.png"></a>
        <span id="topics-menu"><img src="/png_icons/burger.png"> Topics
            <span id="topics-dropdown" class='dropdown-links'>
                <span>
                    <?php
                    $resultTopics = mysqli_query($db, $sql = "SELECT topic FROM topics WHERE status = 1");
                    while ($rowTopics = mysqli_fetch_assoc($resultTopics)) {
                        $topicMenu = $rowTopics['topic'];
                        $topicMenuFormatted = format($topicMenu, true);
                        echo "<a href='/topics/$topicMenuFormatted'>$topicMenu</a>";
                    }
                    ?>
                </span>
            </span>
        </span>
        <input type="text" placeholder="Search for an article" id="menu-search-box">
        <?php
        if (isset($_SESSION['uid'])) {
            $uname = $_SESSION['uname'];
            $unameFormatted = format($uname);
            $uid = $_SESSION['uid'];

            echo "
            <span class='user-menu'>
                <img src='/png_icons/user.png'>
                <p>$uname</p>
                <img src='/png_icons/down-arrow.png'>
                <span id='user-dropdown' class='dropdown-links'>
                    <span>
                        <a href='/users/$unameFormatted-$uid'>My Profile</a>
                        ";
                        // shows the control panel option if the user is an admin
                        if (getRole($db) > 0) {
                            if (isset($_SESSION['control-panel'])) {
                                $controlPanel = $_SESSION['control-panel'];
                            } else {
                                $controlPanel = "post-article";
                            }
                            echo "<a href='/control-panel?$controlPanel'>Control Panel</a>";
                        }
                        echo "
                        <a href='/logout'>Logout</a>
                    </span>
                </span>
            </span>
            ";
        } else {
            echo "
            <span class='signup-login-menu'>
                <a href='/signup'>Signup</a>
                <a href='/login'>Login</a>
            </span>
            ";
        }
        ?>
        <span class='switch'>
            <?php
            $changeThemeChecked = "";
            if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == "dark") {
                $changeThemeChecked = "checked = 'checked'";
            }

            ?>
            <input type='checkbox' id='changeTheme' <?php echo $changeThemeChecked ?>>
        </span>
    </div>
</div>
