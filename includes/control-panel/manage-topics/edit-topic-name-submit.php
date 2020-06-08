<?php
if (isset($_POST['edit-topic-name-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    if (getRole($db) < 2) {
        header("Location: /404");
        exit();
    }

    // if the new name is empty
    if (empty($_POST['newName'])) {
        header("Location: /control-panel?manage-topics&error=empty-name");
        exit();
    }

    // old name
    $oldName = $_POST['oldName'];
    // completely formats the old name
    $oldNameFormatted = format($oldName);

    // new name
    $newName = $_POST['newName'];
    // removes the special characters
    $newNameOnlySpecialChars = format($newName, true, false);
    // completely formats the new name
    $newNameFormatted = format($newName, true);

    // if the topic name is the default for form selection
    if ($newName == "not-chosen") {
        header("Location: /control-panel?manage-topics&error=invalid-name");
        exit();
    }

    // if the new topic name already exists
    $sql = "SELECT topic FROM topics WHERE topic = ?";
    $vars = [$newNameOnlySpecialChars];
    $varsType = "s";
    $resultTopic = executeStmt($db, $sql, $varsType, $vars);

    if (mysqli_num_rows($resultTopic) > 0) {
        header("Location: /control-panel?manage-topics&error=topic-already-exists");
        exit();
    }

    // rename the topic folder
    rename("../../../topics/$oldNameFormatted", "../../../topics/$newNameFormatted");

    // change the name of the topic in the db
    $sql = "UPDATE topics SET topic = ? WHERE topic = ?";
    $vars = [$newNameOnlySpecialChars, $oldName];
    $varsType = "ss";
    $resultTopic = executeStmt($db, $sql, $varsType, $vars, false);

    header("Location: /control-panel?manage-topics&topic-renamed-successfully");

} else {
    header("Location: /404");
}
?>
