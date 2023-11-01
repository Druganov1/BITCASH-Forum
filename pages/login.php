<?php
session_start();

include '../functions/db.php';

// Assuming you have established a PDO connection in db.php

$username = $_POST['username'];
$password = $_POST['password'];

try {
    $stmt = $pdo->prepare("SELECT * FROM tblaccount WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['lname'] = $row['lname'];
        $_SESSION['user_Id'] = $row['user_Id'];
        header("Location: home.php");
    } else {
        echo '<script language="javascript">';
        echo 'alert("Incorrect username or password")';
        echo '</script>';
        echo '<meta http-equiv="refresh" content="0;url=../index.php" />';
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
