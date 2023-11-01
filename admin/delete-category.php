<?php
include "../functions/db.php";

if (isset($_GET['cat_id'])) {
    $id = $_GET['cat_id'];
} else {
    header("location:index.php");
    exit();
}

try {
    $sql = "DELETE FROM category WHERE cat_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    
    header("Location: category.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
