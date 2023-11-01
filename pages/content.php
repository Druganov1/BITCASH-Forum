<?php
session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
} else {
    header("Location:../index.php");
}
$username = $_SESSION['username'];
$userid = $_SESSION['user_Id'];
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
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/bootstrap.min.js"></script>

    <style>
    /* Add this CSS to your stylesheet or within a <style> tag */
    .comment-text {
        width: 100%; /* Adjust the width as needed */
        max-width: 100%; /* To ensure it doesn't exceed the container width */
        white-space: pre-wrap; /* Allows line breaks to be displayed */
        resize: none;
    }
</style>

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
            <a class="navbar-brand" href="home.php">CSIT FORUM</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse">

            <ul class="nav navbar-nav navbar-left">
                <li><a href="#quest"> Post a Question</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($username); ?></a>
                    </li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>

                </ul>


        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<div class="container" style="margin:7% auto;">
    <h4>Latest Discussion</h4>
    <hr>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Programming</h3>
        </div>
        <div class="panel-body">

            <?php
            include "../functions/db.php";
            $id = $_GET['post_id'];

            $stmt = $pdo->prepare("SELECT tp.title, tp.content, tp.datetime, tp.user_Id, c.category 
                            FROM tblpost AS tp
                            JOIN category AS c ON tp.cat_id = c.cat_id 
                            WHERE tp.post_Id = :post_id");
            $stmt->bindParam(':post_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $title = htmlspecialchars($row['title']);
                $content = htmlspecialchars($row['content']);
                $datetime = htmlspecialchars($row['datetime']);
                $category = htmlspecialchars($row['category']);
                $user_Id = $row['user_Id'];

                if ($user_Id == 9) {
                    echo "<label>Topic Title: </label> " . $title . "<br>";
                    echo "<label>Topic Category: </label> " . $category . "<br>";
                    echo "<label>Date time posted: </label> " . $datetime;
                    echo "<textarea readonly class='well comment-text form-control'>" . $content . "</textarea>";
                    echo "<label>Posted By: </label> Admin";
                } else {
                    $stmt = $pdo->prepare("SELECT username FROM tblaccount WHERE user_Id = :user_id");
                    $stmt->bindParam(':user_id', $user_Id, PDO::PARAM_INT);
                    $stmt->execute();
                    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($userRow) {
                        $username = htmlspecialchars($userRow['username']);
                        echo "<label>Topic Title: </label> " . $title . "<br>";
                        echo "<label>Topic Category: </label> " . $category . "<br>";
                        echo "<label>Date time posted: </label> " . $datetime;
                        echo "<textarea readonly class='well comment-text form-control'>" . $content . "</textarea>";
                        echo '<label>Posted By: </label>' . $username;
                    }
                }
            }
            ?>

            <br><label>Comments</label><br>
            <div id="comments">
                <?php
                $postid = $_GET['post_id'];
                $stmt = $pdo->prepare("SELECT c.comment, c.datetime, u.username
                            FROM tblcomment AS c
                            JOIN tblaccount AS u ON c.user_Id = u.user_Id
                            WHERE c.post_Id = :post_id 
                            ORDER BY c.datetime");
                $stmt->bindParam(':post_id', $postid, PDO::PARAM_INT);
                $stmt->execute();
                $num = $stmt->rowCount();

                if ($num > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $comment = htmlspecialchars($row['comment']);
                        $username = htmlspecialchars($row['username']);
                        $datetime = htmlspecialchars($row['datetime']);
                        echo "<label>Comment by: </label> " . $username . "<br>";
                        echo '<label class="pull-right">' . $datetime . '</label>';
                        echo "<textarea readonly class='well comment-text form-control'>" . $comment . "</textarea>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-sm-5 col-md-5 sidebar">
        <h3>Comment</h3>
        <form method="POST">
            <textarea type="text" class="form-control" id="commenttxt"></textarea><br>
            <input type="hidden" id="postid" value="<?php echo $_GET['post_id']; ?>">
            <input type="hidden" id="userid" value="<?php echo $_SESSION['user_Id']; ?>">
            <input type="submit" id="save" class="btn btn-success pull-right" value="Comment">
        </form>
    </div>
</div>

<div class="my-quest" id="quest">
    <div>
        <form method="POST" action="question-function.php">
            <label>Category</label>
            <select name="category" class="form-control">
                <option></option>
                <option value="Programming">Programming</option>
                <option value="Multimedia">Multimedia</option>
                <option value="Computer Networking">Computer Networking</option>
            </select>
            <label>Topic Title</label>
            <input type="text" class="form-control" name="title" required>
            <label>Content</label>
            <textarea name="content" class="form-control"></textarea>
            <br>
            <input type="submit" class="btn btn-success pull-right" value="Post">
        </form><br>
        <hr>
        <a href="" class="pull-right">Close</a>
    </div>
</div>

</body>
<script>
    $("#save").click(function () {
        var postid = $("#postid").val();
        var userid = $("#userid").val();
        var comment = $("#commenttxt").val();
        var datastring = 'postid=' + postid + '&userid=' + userid + '&comment=' + comment;
        if (!comment) {
            alert("Please enter some text comment");
        } else {
            $.ajax({
                type: "POST",
                url: "../functions/addcomment.php",
                data: datastring,
                cache: false,
                success: function (result) {
                    document.getElementById("commenttxt").value = ' ';
                    $("#comments").append(result);
                }
            });
        }
        return false;
    })
</script>
</html>
