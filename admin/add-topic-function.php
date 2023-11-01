<?php
include "../functions/db.php";
date_default_timezone_set("Europe/Amsterdam");
$datetime = date("Y-m-d H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_Id = 009;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    try {
        $sql = "INSERT INTO tblpost (title, content, cat_id, datetime, user_Id) VALUES (:title, :content, :category, :datetime, :user_Id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":category", $category, PDO::PARAM_INT);
        $stmt->bindParam(":datetime", $datetime, PDO::PARAM_STR);
        $stmt->bindParam(":user_Id", $user_Id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: post.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
