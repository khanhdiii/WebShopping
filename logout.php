<?php
    include('sessions.php');
    if(isset($_POST['tienhanhdangxuat'])) {
        session_unset();
        session_destroy();
        if (isset($_COOKIE['rememberme'])) {
            setcookie("rememberme", '', 0);
        }
        echo '<script>alert("Đăng xuất thành công.");</script>';
        header("Refresh: 0; url=/login.php");
    }
?>