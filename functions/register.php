<?php
include "db.php";

extract($_POST);

try {
    // Sanitize and prepare data
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $gender = filter_var($gender, FILTER_SANITIZE_STRING);
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user information
    $stmt1 = $pdo->prepare("INSERT INTO tbluser (fname, lname, gender) VALUES (:fname, :lname, :gender)");
    $stmt1->bindParam(':fname', $fname);
    $stmt1->bindParam(':lname', $lname);
    $stmt1->bindParam(':gender', $gender);
    $stmt1->execute();

    // Get the last inserted user ID
    $userId = $pdo->lastInsertId();

    // Insert account information
    $stmt2 = $pdo->prepare("INSERT INTO tblaccount (username, password, user_Id) VALUES (:username, :password, :user_Id)");
    $stmt2->bindParam(':username', $username);
    $stmt2->bindParam(':password', $hashedPassword);
    $stmt2->bindParam(':user_Id', $userId);
    $stmt2->execute();

    echo '<script language="javascript">';
    echo 'alert("Successfully Registered")';
    echo '</script>';
    echo '<meta http-equiv="refresh" content="0;url=../index.php" />';
} catch (PDOException $e) {
    echo 'Registration failed: ' . $e->getMessage();
}
?>
