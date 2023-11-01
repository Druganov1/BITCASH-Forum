<?php
include "../functions/db.php";

// Make sure you have a PDO connection established in your "db.php" file.

extract($_POST);

try {
    // Prepare the SQL statement with a placeholder
    $sql = "INSERT INTO `category`(category) VALUES (:category)";
    $stmt = $pdo->prepare($sql);
    
    // Bind the parameter
    $stmt->bindParam(":category", $category, PDO::PARAM_STR);
    
    // Execute the query
    $stmt->execute();

    // Redirect after successful insertion
    header("Location: category.php");
    exit(); // Terminate the script after redirection
} catch (PDOException $e) {
    // Handle any errors gracefully
    echo "Error: " . $e->getMessage();
}
?>
