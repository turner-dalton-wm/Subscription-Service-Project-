<?php
require_once('../connect.php');

function login($conn) {
    setcookie('token', "", 0, "/");
    $email = $_POST['email'];
    $password = sha1($_POST['password']);
    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($email))) {
        $accountExists = false;
        while ($row = $stmt->fetch()) {
            $accountExists = true;
            if($row['password'] == $password) {
                $token = generateToken();
                $sql = 'UPDATE users SET token = ? WHERE email = ?';
                $stmt1 = $conn->prepare($sql);
                if($stmt1->execute(array($token, $email))) {
                    setcookie('token', $token, 0, "/");
                    echo '<form class="account" action="" method="post">
                            <span>Login Successful</span><br><br>
                            <input name="email" maxlength="40" placeholder="Email"/><br><br>
                            <input type="password" name="password" maxlength="20" placeholder="Password"/><br><br>
                            <input class="submit" type="submit" name="submit" value="Log In"/><br><br>
                            <a href="/Subscription-Service-Project-/register/">Register</a>
                            </form>';
                }
            }
            else {
                echo '<form class="account" action="" method="post">
                            <span>Incorrect Email or Password</span><br><br>
                            <input name="email" maxlength="40" placeholder="Email"/><br><br>
                            <input type="password" name="password" maxlength="20" placeholder="Password"/><br><br>
                            <input class="submit" type="submit" name="submit" value="Log In"/><br><br>
                            <a href="/Subscription-Service-Project-/register/">Register</a>
                            </form>';
            }
        }
        if(!$accountExists) {
            echo '<form class="account" action="" method="post">
                    <span>Incorrect Email or Password</span><br><br>
                    <input name="email" maxlength="40" placeholder="Email"/><br><br>
                    <input type="password" name="password" maxlength="20" placeholder="Password"/><br><br>
                    <input class="submit" type="submit" name="submit" value="Log In"/><br><br>
                    <a href="/Subscription-Service-Project-/register/">Register</a>
                    </form>';
        }
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
    <title>Login</title>
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
        login($dbh);
    }
    else {
        echo '<form class="account" action="" method="post">
                <input name="email" maxlength="40" placeholder="Email"/><br><br>
                <input type="password" name="password" maxlength="20" placeholder="Password"/><br><br>
                <input class="submit" type="submit" name="submit" value="Log In"/><br><br>
                <a href="/Subscription-Service-Project-/register/">Register</a>
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
