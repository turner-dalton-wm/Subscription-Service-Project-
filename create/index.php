<?php
require_once('../connect.php');

$token = getToken();
$sql = 'SELECT * FROM users WHERE token = ?';
$stmt = $dbh->prepare($sql);
if ($stmt->execute(array($token))) {
    while($row = $stmt->fetch()) {
        if($row['rank'] != 'admin') {
            header('location:/Subscription-Service-Project-/');
        }
    }
}

function addPost($conn) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $token = generateToken();
    if(!empty($image)) {
        if(move_uploaded_file($_FILES['image']['tmp_name'], '../res/images/' . $image)) {

        }
    }
    else {
        $image = 'undefined.png';
    }
    $sql = 'INSERT INTO posts (title, content, image, token) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($title, $content, $image, $token))) {
        echo 'Post Submitted Successfully';
    }
}
function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}
function getToken() {
    if (isset($_COOKIE['token'])) {
        return $_COOKIE['token'];
    }
    else {
        header('location:/Subscription-Service-Project-/login/');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body onscroll="toggleHeader()">
<div class="header">
    <img class="logo" src="../res/logo.png"/>
    <div class="dropdown-btn" onclick="toggleDropdown()">
        <div id="mainDropdown" class="dropdown-content">
            <a href="/Subscription-Service-Project-/">Home</a>
            <a href="/Subscription-Service-Project-/login">Login</a>
            <a href="/Subscription-Service-Project-/register">Register</a>
        </div>
    </div>
</div>

<div class="min-header">
    <a href="/Subscription-Service-Project-/">Home</a>
    <a href="/Subscription-Service-Project-/login">Login</a>
    <a href="/Subscription-Service-Project-/register">Register</a>
    <img class="top" src="../res/button/top.png" onclick="scrollToTop()"/>
</div>

<div class="content">
    <form class="account" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <h3>Create Post</h3><br>
        <?php
        if(isset($_POST['submit'])) {
            addPost($dbh);
        }
        ?>
        <input type="text" name="title" placeholder="Title"><br>
        <input type="text" name="content" placeholder="Content"><br>
        <input type="file" id="image" name="image"><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</div>

<script>
    function scrollToTop() {
        window.scrollTo(0,0);
    }

    function toggleHeader() {
        var header = document.getElementsByClassName("header")[0];
        var minHeader = document.getElementsByClassName("min-header")[0];
        var scroll = window.scrollY;
        if(scroll > header.clientHeight) {
            header.style.display = "none";
            minHeader.style.display = "block";
        }
        else {
            header.style.display = "block";
            minHeader.style.display = "none";
        }
    }

    function toggleDropdown() {
        document.getElementById("mainDropdown").classList.toggle("show");
    }
    window.onclick = function(e) {
        if(!e.target.matches('.dropdown-btn')) {
            var elements = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].classList.contains("show")) elements[i].classList.remove("show");
            }
        }
    };
</script>
</body>
</html>