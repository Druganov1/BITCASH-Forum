<?php
session_start();
if (isset($_SESSION['uname']) && $_SESSION['uname'] != "") {
    $uname = $_SESSION['uname'];
} else {
    header("Location:index.php");
    exit();
}

include "../functions/db.php"; // Ensure you have a PDO connection in your db.php file.

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
                    <li class="active"><a href="home.php"> Dashboard</a></li>
                    <li><a href="post.php"> Topics</a></li>
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

    <div class="container" style="margin: 8% auto; width: 900px;">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Topic Posted</h3>
            </div>
            <div class="panel-body">
                <table class="table table-stripped">
                    <th>Topic</th>
                    <th>Category</th>
                    <th>Action</th>
                    <?php

                    $sql = "SELECT tp.post_Id, tp.title, c.category FROM tblpost as tp JOIN category as c ON tp.cat_id=c.cat_id ORDER BY tp.datetime ASC LIMIT 5";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $id = $row['post_Id'];
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td>" .
                            "<a href='topic-details.php?post_Id=$id' class='btn btn-default'>Details</a>"
                            . "</td>";
                        echo "</tr>";
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
