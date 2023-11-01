<?php
include "../functions/db.php";

date_default_timezone_set("Europe/Amsterdam");
$datetime = date("Y-m-d H:i:s");

extract($_POST);

try {
    $stmt = $pdo->prepare("INSERT INTO tblpost (title, content, cat_id, datetime, user_Id) VALUES (:title, :content, :category, :datetime, :userid)");
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_INT);
    $stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo '<script language="javascript">';
        echo 'alert("Post Successfully")';
        echo '</script>';
        echo '<meta http-equiv="refresh" content="0;url=home.php" />';
    } else {
        echo '<script language="javascript">';
        echo 'alert("Failed to post. Please try again later.")';
        echo '</script>';
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
