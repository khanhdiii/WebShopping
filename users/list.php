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
            $_SESSION['tranghientai'] = "danhsachtaikhoan";
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
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4">Danh sách thành viên</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Tên tài khoản</th>
                                        <th scope="col">Địa chỉ Email</th>
                                        <th scope="col">Admin?</th>
                                        <th scope="col">Tính năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sqlcheckuser = "SELECT * FROM `users`";
                                        $ketqua = $connect->query($sqlcheckuser);

                                        if ($ketqua->num_rows > 0) {
                                            while ($user = $ketqua->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $user["id"] ?></td>
                                                <td><?php echo $user["username"] ?></td>
                                                <td><?php echo $user["email"] ?></td>
                                                <td><?php echo $user["adminlevel"] ?></td>
                                                <td>
                                                    <a class="btn btn-sm btn-primary m-2" href="/edit.php"><i class="fas fa-user-edit"></i> Sửa</button>
                                                    <a class="btn btn-sm btn-primary m-2" href="/edit.php"><i class="fas fa-user-minus"></i> Xóa</button>
                                                </td>
                                            </tr>
                                        <?php
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
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

                $sqlcheckuser = "SELECT * FROM `users` WHERE `username` = '". $username ."'";
                $ketqua = $connect->query($sqlcheckuser);

                if ($ketqua->num_rows > 0) {
                    $_SESSION['khongthanhcong'] = "Tên tài khoản đã tồn tại";
                    echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                } else {
                    if ($username != "" && $email != "" && $password != "" && $password2 != "")
                    {
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
                    else
                    {
                        $_SESSION['khongthanhcong'] = "Các thông tin trên không được bỏ trống";
                        echo '<meta http-equiv="refresh" content="0;URL=create.php">';
                    }
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