<?php
    include('sessions.php');
    include('connect.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <?php include('templates/header.php') ?>
</head>

<body>
    <?php
        // if(isset($_SESSION['dangnhapthanhcong']) && isset($_COOKIE["rememberme"])) { // Nếu như có session đăng nhập thành công và đồng thời có cookie rememberme thì thành công
        if(isset($_SESSION['dangnhapthanhcong']) || isset($_COOKIE["rememberme"])) { // Nếu có session đăng nhập thành công hoặc có cookie rememberme thì thành công
            $_SESSION['tranghientai'] = "hosocanhan";
    ?>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Đang tải...</span>
            </div>
        </div>
        <?php include('templates/sidebar.php') ?>
        <div class="content">
            <?php include('templates/navbar.php') ?>
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded">
                <div class="col-xl-12">
                    <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Chỉnh sửa hồ sơ cá nhân</h6>
                            <?php
                                if(isset($_SESSION['khongthanhcong'])) {
                            ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fa fa-exclamation-circle me-2"></i><?php echo $_SESSION['khongthanhcong']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                                unset($_SESSION['khongthanhcong']);
                            }
                            ?>
                            <form method="POST" action="profile.php">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Tên hiển thị</label>
                                    <input type="text" class="form-control" name="username" value="" placeholder="<?php echo $_SESSION['username']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Địa chỉ Email của bạn</label>
                                    <input type="email" class="form-control" name="email" value="" placeholder="<?php echo $_SESSION['email']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="oldpassword" class="form-label">Mật khẩu cũ</label>
                                    <input type="password" class="form-control" name="oldpassword">
                                </div>
                                <div class="mb-3">
                                    <label for="newpassword" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="newpassword">
                                </div>
                                <div class="mb-3">
                                    <label for="newpassword2" class="form-label">Nhập lại Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="newpassword2">
                                </div>
                                <button type="submit" class="btn btn-primary" name="xacnhanthaydoithongtin">Thay đổi thông tin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('templates/footer.php') ?>
        </div>
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    <?php
            if (isset($_POST['xacnhanthaydoithongtin'])) {
                $username = mysqli_real_escape_string($connect, $_POST['username']);
                $email = mysqli_real_escape_string($connect, $_POST['email']);
                $oldpassword = $_POST['oldpassword'];
                $newpassword = md5($_POST['newpassword']);
                $newpassword2 = md5($_POST['newpassword2']);

                $thaydoitentaikhoan = false;
                $thaydoidiachiemail = false;

                if ($username != "") { // Nếu username mà không bị bỏ trống thì thực hiện bên dưới < > >= <= == !=
                    $sqlcheckuser = "SELECT * FROM `users` WHERE `username` = '". $username ."'";
                    $ketqua = $connect->query($sqlcheckuser);

                    if ($ketqua->num_rows > 0) {
                        $_SESSION['khongthanhcong'] = "Tên tài khoản đã tồn tại";
                        echo '<meta http-equiv="refresh" content="0;URL=profile.php">';
                    } else {
                        $connect->query("UPDATE `users` SET `username` = '". $username ."' WHERE `username` = '" . $_SESSION['username'] . "'");
                        $thaydoitentaikhoan = true;
                    }
                }
                if ($email != "") {
                    $sqlcheckuser = "SELECT * FROM `users` WHERE `email` = '". $email ."'";
                    $ketqua = $connect->query($sqlcheckuser);

                    if ($ketqua->num_rows > 0) {
                        $_SESSION['khongthanhcong'] = "Địa chỉ Email đã tồn tại";
                        echo '<meta http-equiv="refresh" content="0;URL=profile.php">';
                    } else {
                        $connect->query("UPDATE `users` SET `email` = '". $email ."' WHERE `username` = '" . $_SESSION['username'] . "'");
                        $thaydoidiachiemail = true;
                    }
                }
                if ($oldpassword != "") {
                    if ($newpassword != $newpassword2) {
                        $_SESSION['khongthanhcong'] = "Mật khẩu mới và Xác nhận mật khẩu mới không chính xác";
                        echo '<meta http-equiv="refresh" content="0;URL=profile.php">';
                    } else {
                        $sqlcheckuser = "SELECT * FROM `users` WHERE `email` = '". $_SESSION['email'] ."' AND `password` = '". md5($oldpassword) ."'";
                        $ketqua = $connect->query($sqlcheckuser);
                        if ($ketqua->num_rows > 0) {
                            $connect->query("UPDATE `users` SET `password` = '". $newpassword ."' WHERE `username` = '" . $_SESSION['username'] . "'");
                        }
                        else {
                            $_SESSION['khongthanhcong'] = "Mật khẩu cũ không chính xác";
                            echo '<meta http-equiv="refresh" content="0;URL=profile.php">';
                        }
                    }
                }

                if ($thaydoitentaikhoan) {
                    $_SESSION['username'] = $username;
                    echo '<meta http-equiv="refresh" content="0;URL=profile.php">';
                }

                if ($thaydoidiachiemail) {
                    $_SESSION['email'] = $email;
                    echo '<meta http-equiv="refresh" content="0;URL=profile.php">';
                }
            }
        }
        else {
            echo '<script>alert("Bạn chưa đăng nhập, có cứt vào được.")</script>';
            header("Refresh: 0; url=/login.php");
        }
    ?>
    <?php include('templates/scripts.php') ?>
</body>

</html>