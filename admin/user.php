<?php
session_start();

// Check if the user is logged in; otherwise, redirect to the login page.
if (!isset($_SESSION['uname']) || empty($_SESSION['uname'])) {
    header("Location: index.php");
    exit();
}

$uname = $_SESSION['uname'];

// Include your PDO database connection here
include "../functions/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'user_Id' is set in the POST request
    if (isset($_POST['user_Id'])) {
        $user_Id = $_POST['user_Id'];

        // Prepare a SQL statement to delete the user
        $sql = "DELETE FROM tbluser WHERE user_Id = :user_Id";

        // Prepare the query
        $stmt = $pdo->prepare($sql);

        // Bind values to placeholders
        $stmt->bindParam(':user_Id', $user_Id);

        // Execute the query
        if ($stmt->execute()) {
            echo "success"; // Return success to your JavaScript for confirmation
            exit();
        } else {
            echo "error"; // Return an error message if the deletion fails
            exit();
        }
    }
}
?>

<!-- Your HTML content for displaying users -->
<html>
<head>
    <title>User Management</title>
    <!-- Add your CSS and JS includes here -->
</head>
<body>
    <!-- Navigation and header content here -->

    <div class="container" style="margin: 8% auto; width: 900px;">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Users</h3>
            </div>
            <div class="panel-body">
                <table class="table table-stripped">
                    <th>Username</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Actions</th>
                    <?php
                    // Include your PDO database connection here
                    include "../functions/db.php";

                    $sql = "SELECT * FROM tbluser AS tu JOIN tblaccount AS ta ON tu.user_Id = ta.user_Id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                        echo '<td><button class="btn btn-danger" onclick="deleteUser(' . $row['user_Id'] . ')">Delete</button>';
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script>
    function deleteUser(user_Id) {
        var confirmation = confirm("Are you sure you want to delete this user?");
        if (confirmation) {
            // Perform an AJAX request to delete the user
            $.ajax({
                type: "POST",
                url: "user.php", // Replace with the correct URL
                data: { user_Id: user_Id },
                success: function(response) {
                    if (response === "success") {
                        // Refresh the page after successful deletion
                        window.location.reload();
                    } else {
                        alert("Failed to delete user.");
                    }
                }
            });
        }
    }
    </script>
</body>
</html>
