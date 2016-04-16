<?php
require_once('connect.php');

if(empty($_COOKIE['token'])) header('location:/Subscription-Service-Project-/login/');

function getPosts($conn) {
    $sql = 'SELECT * FROM posts ORDER BY date';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array())) {
        echo '<ul class="products">';
        while($row = $stmt->fetch()) {
            echo '<li class="product">
                        <div class="product-cat product-img"><img src="res/images/'.$row['image'].'" width="96" height="96"/></div>
                        <div class="product-cat product-detail"><p class="post-title">'.$row['title'].'</p><br>
                        <p class="post-content">'.$row['content'].'</p></div>
                        <div class="product-cat product-rate">[NEWS]</div>
                    </li>';
        }
        echo '</ul>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body onscroll="toggleHeader()">
<div class="header">
    <img class="logo" src="res/logo.png"/>
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
    <img class="top" src="res/button/top.png" onclick="scrollToTop()"/>
</div>

<div class="content">
    <?php
    getPosts($dbh);
    ?>
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
