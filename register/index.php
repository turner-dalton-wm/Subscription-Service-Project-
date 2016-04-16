<?php
require_once('../connect.php');

function register($conn) {
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $continue = true;
    $form = '<form class="account" action="/register/" method="post">
                <span>Missing Required Fields.</span><br><br>';

    if(!isset($email) || trim($email) == '') {
        $form .= '<input class="required" name="email" maxlength="100" placeholder="Email"/><br><br>';
        $continue = false;
    }
    else {
        $form .= '<input name="email" maxlength="100" placeholder="Email" value="'.$email.'"/><br><br>';
    }
    $form .= '<input class="submit" type="submit" name="submit" value="Register"/>
                </form>';
    if(!isset($password) || trim($password) == '') {
        $form .= '<input class="required" type="password" name="password" maxlength="45" placeholder="Password"/><br><br>';
        $continue = false;
    }
    else {
        $form .= '<input type="password" name="password" maxlength="45" placeholder="Password" value="'.$password.'"/><br><br>';
    }


    if($continue) {
        $token = generateToken();
        $sql = 'INSERT INTO users (email, password, token) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($sql);
        try {
            if ($stmt->execute(array($email, $password, $token))) {
                setcookie('token', $token, 0, "/");
            }
        }
        catch (PDOException $e) {
            echo '<form class="account" action="" method="post">
                    <span>Email Already Registered. Try Again.</span><br><br>
                    <input name="email" maxlength="100" placeholder="Email"/><br><br>
                    <input type="password" name="password" maxlength="45" placeholder="Password"/><br><br>
                    <input class="submit" type="submit" name="submit" value="Register"/>
                  </form>';
        }
    }
    else {
        echo $form;
    }
}

function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
    <?php
    if(isset($_POST['submit'])) {
        register($dbh);
    }
    else {
        echo '<form class="account" action="" method="post">
                <input name="email" maxlength="100" placeholder="Email"/><br><br>
                <input type="password" name="password" maxlength="45" placeholder="Password"/><br><br>
                <input class="submit" type="submit" name="submit" value="Register"/>
                </form>';
    }
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