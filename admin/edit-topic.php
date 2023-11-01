<?php
session_start();
if (isset($_SESSION['uname']) && $_SESSION['uname'] != "") {
    $uname = $_SESSION['uname'];
} else {
    header("Location:index.php");
    exit();
}

include "../functions/db.php"; // Ensure you have a PDO connection in your db.php file.

$cat_id = isset($_GET['post_Id']) ? $_GET['post_Id'] : null;

if (empty($cat_id)) {
    header("location:post.php");
    exit();
}

if (isset($_POST['edit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    try {
        $sql = "UPDATE tblpost SET title = :title, content = :content, category = :category, datetime = NOW() WHERE post_Id = :post_Id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":category", $category, PDO::PARAM_STR);
        $stmt->bindParam(":post_Id", $cat_id, PDO::PARAM_INT);
        $stmt->execute();

        echo '<script language="javascript">';
        echo 'alert("Updated")';
        echo '</script>';
        header("Location: post.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<html>

<head>
    <title></title>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../css/global.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <!-- Script -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="home.php"></a>
            </div>
            <div class="navbar-header">
                <a class="navbar-brand" href="home.php">Administrator</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="home.php"> Dashboard</a></li>
                    <li class="active"><a href="post.php"> Topics</a></li>
                    <li><a href="user.php"> Users</a></li>
                    <li><a href="category.php">Category</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $uname; ?></a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div class="container" style="margin:8% auto;width:900px;">
        <h2> Topics Posted</h2>
        <hr>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Topic Details</h3>
            </div>
            <div class="panel-body">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM tblpost as tp JOIN category as c ON tp.cat_id=c.cat_id WHERE tp.post_Id = :post_Id");
                $stmt->bindParam(":post_Id", $cat_id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $title = htmlspecialchars($row['title']);
                    $content = htmlspecialchars($row['content']);
                    $category = htmlspecialchars($row['category']);
                    $datetime = htmlspecialchars($row['datetime']);
                }
                ?>

                <form method="POST">
                    <input type="text" name="title" class="form-control" value="<?php echo $title; ?>"><br>
                    <textarea name="content" class="form-control"><?php echo $content; ?></textarea><br>
                    <select name="category" class="form-control">
                        <option><?php echo $category; ?></option>
                        <option value="Programming">Programming</option>
                        <option value="Multimedia">Multimedia</option>
                        <option value="Computer Networking">Computer Networking</option>
                    </select><br>
                    <input type="text" class="form-control" value="<?php echo $datetime; ?>" disabled><br>
                    <input type="submit" name="edit" class="btn btn-success pull-right" value="Update">
                </form>
            </div>
        </div>
    </div>
</body>

</html>
