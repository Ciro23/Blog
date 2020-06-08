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

$_SESSION['control-panel'] = "manage-users";

// gets all the needed variables from post
$elementsPerPage = $_POST['elementsPerPage'];
$totalPages = $_POST['totalPages'];
$currentPage = $_POST['currentPage'];
$offset = $_POST['offset'];
$minPage = $_POST['minPage'];
$maxPage = $_POST['maxPage'];
$search = $_POST['search'];
$sort = $_POST['sort'];

// if a search is submitted
if ($search != "") {
    $where = "AND username LIKE ?";
    $vars = ["%$search%"];
    $varsType = "s";
} else {
    $where = "";
}

$sortSelected1 = "";
$sortSelected2 = "";
$sortSelected3 = "";
$sortSelected4 = "";

// if users sort is selected
if ($sort == "" || $sort == "alphabetically") {
    $sort = "alphabetically";
    $sortSql = "ORDER BY username";
    $sortSelected1 = "selected";
} else if ($sort == "newers") {
    $sortSql = "ORDER BY id DESC";
    $sortSelected2 = "selected";
} else if ($sort == "olders") {
    $sortSql = "ORDER BY id ASC";
    $sortSelected3 = "selected";
} else if ($sort == "role") {
    $sortSql = "ORDER BY role DESC";
    $sortSelected4 = "selected";
}
?>

<script>
$(document).ready(function() {
    $("#second-mega-box").fadeIn("fast");
});
</script>

<div class="mega-box" id="second-mega-box">
    <h1>Manage users</h1>
    <form class="first-form" method="get">
        <input type="hidden" name="manage-users">
        <input type="text" name="search" placeholder="Search for an user" value="<?= $search ?>">
        <button type="file" title="Search user">
            <img src="/png_icons/search.png">
        </button>
        <input type="hidden" name="sort" value="<?= $sort ?>">
    </form>

    <hr id="hr-topics-users">

    <form class="sort-results" method="get">
        <input type="hidden" name="manage-users">
        <label>Sort by</label>
        <input type="hidden" name="search" value="<?= $search ?>">
        <select name="sort" onchange="this.form.submit()">
            <option value="alphabetically" <?= $sortSelected1 ?>>Alphabetically</option>
            <option value="newers" <?= $sortSelected2 ?>>Newers</option>
            <option value="olders" <?= $sortSelected3 ?>>Olders</option>
            <option value="role" <?= $sortSelected4 ?>>Role</option>
        </select>
        <input type="hidden" name="page" value="<?= $currentPage ?>">
    </form>

    <?php
    $sql = "SELECT id, username, role, isBanned FROM users WHERE status = 1 AND username != '{$_SESSION['uname']}' $where $sortSql LIMIT $elementsPerPage OFFSET $offset";
    
    if ($search != "") {
        $resultUsers = executeStmt($db, $sql, $varsType, $vars);
    } else {
        $resultUsers = mysqli_query($db, $sql);
    }

    $resultUsersCheck = mysqli_num_rows($resultUsers);

    if ($resultUsersCheck > 0) {
        $i = 0;
        while ($rowUsers = mysqli_fetch_assoc($resultUsers)) {
            $i++;

            if ($i % 2 == 0) {
                $altColor = "alt-color";
            } else {
                $altColor = "";
            }

            // user info
            $uid = $rowUsers['id'];
            $uname = $rowUsers['username'];
            $unameFormatted = format($uname);
            $role = $rowUsers['role'];

            // user role
            $roleSelected1 = "";
            $roleSelected2 = "";
            $roleSelected3 = "";
            switch ($role) {
                case 0:
                    $roleSelected1 = "selected";
                    break;
                
                case 1:
                    $roleSelected2 = "selected";
                    break;
                
                case 2:
                    $roleSelected3 = "selected";
                    break;
            }

            // if user is banned
            $isBanned = $rowUsers['isBanned'];
            if ($isBanned) {
                $title = "Unban from comments";
                $img = "unban";
            } else {
                $title = "Ban from comments";
                $img = "ban";
            }

            echo "
            <div class='box $altColor'>
                <a href='/users/$unameFormatted-$uid'>$uname</a>

                <p>Role: </p>
                <form action='/includes/control-panel/manage-users/edit-user-role-submit.php' method='post' class='bottom-form'>
                    <input type='hidden' name='uid' value='$uid'>
                    <input type='hidden' name='search' value='$search'>
                    <input type='hidden' name='sort' value='$sort'>
                    <input type='hidden' name='page' value='$currentPage'>
                    <select name='role' onchange='this.form.submit()'>
                        <option value='0' $roleSelected1>User</option>
                        <option value='1' $roleSelected2>Writer</option>
                        <option value='2' $roleSelected3>Admin</option>
                    </select>
                </form>

                <form action='/includes/control-panel/manage-users/ban-user-submit.php' method='post' class='side-form'>
                    <input type='hidden' name='uid' value='$uid'>
                    <input type='hidden' name='isBanned' value='$isBanned'>
                    <input type='hidden' name='search' value='$search'>
                    <input type='hidden' name='sort' value='$sort'>
                    <input type='hidden' name='page' value='$currentPage'>
                    <button type='image' name='ban-user-submit' title='$title'>
                        <img src='/png_icons/$img.png'>
                    </button>
                </form>
            </div>
            ";
        }

        // displays pages
        echo "<div class='pagination'>";

        if ($currentPage > 1) {
            $prev = $currentPage - 1;
            echo "<a href='?manage-users&search=$search&sort=$sort&page=$prev' id='prev'></a>";
        }

        for ($i = $minPage; $i <= $maxPage; $i++) {

            if ($i == $currentPage) {
                $class = "id='current-page'";
            } else {
                $class = "";
            }

            echo "<a href='?manage-users&search=$search&sort=$sort&page=$i' $class>$i</a> ";
        }

        if ($currentPage < $totalPages) {
            $following = $currentPage + 1;
            echo "<a href='?manage-users&search=$search&sort=$sort&page=$following' id='following'></a>";
        }

    echo "</div>";
    } else {
        echo "<p id='no-match'>No match</p>";
    }
    ?>
</div>
