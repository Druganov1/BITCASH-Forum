<?php
session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] !== "") {
    $username = $_SESSION['username'];
    $userid = $_SESSION['user_Id'];
} else {
    header("Location:../index.php");
    exit; // Ensure script stops execution after redirection
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSIT FORUM</title>
    <link rel="stylesheet" type="text/css" href="../css/global.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <!-- Script -->
    <script src="../js/jquery.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <style>
    .topic-title {
        max-width: 10px; /* Adjust the maximum width as needed */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <!-- Navbar content goes here -->
    </nav>
    <div class="container" style="margin: 7% auto;">
        <h4>Latest Discussion</h4>
        <hr>

        <?php
        include "../functions/db.php";
        echo '<button type="button" style="margin-bottom: 2vh;" class="btn btn-success " data-toggle="modal" data-target="#createPostModal">
        Create New Post
    </button>
    ';

        $categories = [];

        // Retrieve categories
        $stmt = $pdo->query("SELECT * FROM category");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[$row['cat_id']] = $row['category'];
        }

        // Display categories and "Create New Post" links
        foreach ($categories as $catId => $category) {
            echo '<div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">' . htmlspecialchars($category) . '</h3>
                    </div> 
                    <div class="panel-body">';
            
            $stmt = $pdo->prepare("SELECT * FROM tblpost WHERE cat_id = :cat_id");
            $stmt->bindParam(':cat_id', $catId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo '<table class="table table-stripped">
                        <tr>
                            <th>Topic title</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td class="topic-title">' . htmlspecialchars($row['title']) . '</td>';
                    echo '<td>' . htmlspecialchars($categories[$row['cat_id']]) . '</td>';
                    echo '<td><a href="content.php?post_id=' . $row['post_Id'] . '"><button class="btn btn-success">View</button></a></td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                // Display a message indicating no posts in this category
                echo '<p>No posts in this category.</p>';
            }

            // Create New Post link for this category

            
            echo '</div>
                </div>';
        }
        ?>

<div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="question-function.php">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option></option>
                        <?php
                        foreach ($categories as $catId => $category) {
                            echo '<option value="' . $catId . '">' . htmlspecialchars($category) . '</option>';
                        }
                        ?>
                    </select>
                    <label>Topic Title</label>
                    <input type="text" class="form-control" name="title" required>
                    <label>Content</label>
                    <textarea name="content" class="form-control"></textarea>
                    <br>
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <button type="submit" class="btn btn-success">Post</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    </div>
</body>
</html>
