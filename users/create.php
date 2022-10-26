<?php
    include('../sessions.php');
    include('../connect.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <?php include('../templates/header.php') ?>
</head>

<body>
    <?php
        // if(isset($_SESSION['dangnhapthanhcong']) && isset($_COOKIE["rememberme"])) { // Nếu như có session đăng nhập thành công và đồng thời có cookie rememberme thì thành công
        if(isset($_SESSION['dangnhapthanhcong']) || isset($_COOKIE["rememberme"])) { // Nếu có session đăng nhập thành công hoặc có cookie rememberme thì thành công
            $_SESSION['tranghientai'] = "taotaikhoan";
    ?>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Đang tải...</span>
            </div>
        </div>
        <?php include('../templates/sidebar.php') ?>
        <div class="content">
            <?php include('../templates/navbar.php') ?>
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded">
                <div class="col-xl-12">
                    <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Tạo tài khoản</h6>
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
                            <?php
                                if(isset($_SESSION['thanhcong'])) {
                            ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-exclamation-circle me-2"></i><?php echo $_SESSION['thanhcong']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                                unset($_SESSION['thanhcong']);
                            }
                            ?>
                            <form method="POST" action="./create.php">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Tên hiển thị</label>
                                    <input type="text" class="form-control" name="username" value="">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Địa chỉ Email của bạn</label>
                                    <input type="email" class="form-control" name="email" value="">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="password2" class="form-label">Nhập lại Mật khẩu</label>
                                    <input type="password" class="form-control" name="password2">
                                </div>
                                <div class="mb-3">
                                    <label for="admincheck" class="form-label">Tài khoản admin</label>
                                    <input type="checkbox" class="form-check-input" name="admincheck">
                                </div>
                                <button type="submit" class="btn btn-primary" name="xacnhantaotaikhoan">Tạo tài khoản</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('../templates/footer.php') ?>
        </div>
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    <?php
            if (isset($_POST['xacnhantaotaikhoan'])) {
                $username = mysqli_real_escape_string($connect, $_POST['username']);
                $email = mysqli_real_escape_string($connect, $_POST['email']);
                $password = md5($_POST['password']);
                $password2 = md5($_POST['password2']);

                if ($username != "" && $email != "" && $password != "" && $password2 != "")
                {
                    $sqlcheckuser = "SELECT * FROM `users` WHERE `username` = '". $username ."'";
                    $ketqua = $connect->query($sqlcheckuser);

                    if ($ketqua->num_rows > 0) {
                        $_SESSION['khongthanhcong'] = "Tên tài khoản đã tồn tại";
                        echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                    } else {
                        $sqlcheckuser = "SELECT * FROM `users` WHERE `email` = '". $email ."'";
                        $ketqua = $connect->query($sqlcheckuser);
                        if ($ketqua->num_rows > 0) {
                            $_SESSION['khongthanhcong'] = "Địa chỉ Email đã tồn tại";
                            echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                        } else {
                            if ($password != $password2) {
                                $_SESSION['khongthanhcong'] = "Mật khẩu và Xác nhận mật khẩu không chính xác";
                                echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                            }
                            else {
                                $adminlevel = 0;
                                if (isset($_POST['admincheck'])) {
                                    $adminlevel = 1;
                                }
                                $connect->query("INSERT INTO `users` (`username`, `password`, `email`, `adminlevel`) VALUES ('". $username ."', '". $password ."', '". $email ."', '". $adminlevel ."')");
                                $_SESSION['thanhcong'] = "Tạo tài khoản thành công";
                                echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                            }
                        }
                    }
                }
                else
                {
                    $_SESSION['khongthanhcong'] = "Các thông tin trên không được bỏ trống";
                    echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                }
            }
        }
        else {
            echo '<script>alert("Bạn chưa đăng nhập, có cứt vào được.")</script>';
            echo '<meta http-equiv="refresh" content="0;URL=/login.php">';
        }
    ?>
    <?php include('../templates/scripts.php') ?>
</body>

</html>