<?php
$host = "localhost";
$user = "root";
$pwd  = "";
$db   = "dbforum";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // You can now use $pdo for database operations
} catch (PDOException $e) {
    die("ERROR Connecting to Database: " . $e->getMessage());
}
?>
