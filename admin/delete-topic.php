<?php
include "../functions/db.php";

if (isset($_GET['post_Id'])) {
    $id = $_GET['post_Id'];
} else {
    header("location:index.php");
    exit();
}

try {
    $sql = "DELETE FROM tblpost WHERE post_Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: post.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
