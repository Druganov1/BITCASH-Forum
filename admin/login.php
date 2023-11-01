<?php
session_start();

include '../functions/db.php';

$uname = $_POST['uname'];
$pwd = $_POST['pwd'];

try {
    $query = "SELECT * FROM tbladmin WHERE uname = :uname AND pwd = :pwd";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':uname', $uname, PDO::PARAM_STR);
    $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
    $stmt->execute();
    $array = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($array && $array['uname'] == $uname) {
        $_SESSION['uname'] = $uname;
        header("Location: home.php");
        exit();
    } else {
        echo '<script language="javascript">';
        echo 'alert("Incorrect username or password")';
        echo '</script>';
        echo '<meta http-equiv="refresh" content="0;url=index.php" />';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
