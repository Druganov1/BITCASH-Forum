<?php
include "db.php";

// Get the comment, user ID, and post ID from the POST data
$comment = $_POST['comment'];
$userid = $_POST['userid'];
$postid = $_POST['postid'];

// Set the timezone and get the current datetime
date_default_timezone_set("Europe/Amsterdam");
$datetime = date("Y-m-d H:i:s");

try {
    // Insert the comment into the database
    $stmt = $pdo->prepare("INSERT INTO tblcomment (comment, post_Id, user_Id, datetime) VALUES (:comment, :postid, :userid, :datetime)");
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':datetime', $datetime);
    $stmt->execute();

    // Retrieve the inserted comment details with the username
    $stmt = $pdo->prepare("SELECT c.*, a.username FROM tblcomment AS c JOIN tblaccount AS a ON c.user_Id = a.user_Id WHERE c.post_Id = :postid AND c.user_Id = :userid AND c.datetime = :datetime");
    $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':datetime', $datetime);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<label>Comment by: </label> " . $row['username'] . "<br>";
        echo '<label class="pull-right">' . $row['datetime'] . '</label>';
        echo "<p class='well'>" . $row['comment'] . "</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
