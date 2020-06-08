<?php
if (isset($_POST['add-topic-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    if (getRole($db) < 2) {
        header("Location: /404");
        exit();
    }

    // topic name
    $topic = $_POST['name'];

    // if the topic name is empty
    if (empty($topic)) {
        header("Location: /control-panel?manage-topics&error=empty-name");
        exit();
    }

    // if the topic name is the default for form selection
    if ($topic == "not-chosen") {
        header("Location: /control-panel?manage-topics&error=invalid-name");
        exit();
    }

    // if the topic already exists
    $sql = "SELECT id FROM topics WHERE topic = ? AND status = 1";
    $vars = [$topic];
    $varsType = "s";
    $result = executeStmt($db, $sql, $varsType, $vars);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck > 0) {
        header("Location: /control-panel?manage-topics&error=topic-already-exists");
        exit();
    }

    // completely formats the old name
    $topicFormatted = format($topic, true);

    // add the topic into the db
    mysqli_query($db, $sql = "INSERT INTO topics (topic) VALUES ('$topic')");

    // id of the just created topic
    $idTopic = mysqli_insert_id($db);

    mkdir("../../../topics/$topicFormatted");
    file_put_contents("../../../topics/$topicFormatted/index.php",
"<?php
\$includeCheck = true;
\$idTopic = strval('$idTopic');
include '../../includes/homes/home.php';
?>"
);

    header("Location: /control-panel?manage-topics&topic-addess-successfully");

} else {
    header("Location: /404");
}
?>
