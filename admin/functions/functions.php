<?php

function dbcon() {
    $host = "localhost";
    $user = "root";
    $pwd = "";
    $db = "dbforum";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("ERROR Connecting to Database: " . $e->getMessage());
    }
}

function dbclose($pdo) {
    $pdo = null; // Close the PDO connection
}

function deleteuser($user_Id) {
    $pdo = dbcon();
    
    try {
        $stmt1 = $pdo->prepare("DELETE FROM tbluser WHERE user_Id = :user_Id");
        $stmt1->bindParam(":user_Id", $user_Id, PDO::PARAM_INT);
        $stmt1->execute();
        
        $stmt2 = $pdo->prepare("DELETE FROM tblacct WHERE user_Id = :user_Id");
        $stmt2->bindParam(":user_Id", $user_Id, PDO::PARAM_INT);
        $stmt2->execute();
        
        echo "success";
    } catch (PDOException $e) {
        echo "failed: " . $e->getMessage();
    }

    dbclose($pdo);
}

function category() {
    $pdo = dbcon();
    
    try {
        $stmt = $pdo->query("SELECT * FROM category");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $row['cat_id'] . '">' . $row['category'] . '</option>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    dbclose($pdo);
}

?>
