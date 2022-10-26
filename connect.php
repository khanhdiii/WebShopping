<?php
    $ipserver = 'localhost'; //IP của server MySQL
    $usernameSQL = 'root'; //Username của server MySQL
    $passwordSQL = ''; //Password của server MySQL
    $database = 'hoaquakhanh'; //Tên Database trong MySQL

    $connect = new mysqli($ipserver, $usernameSQL, $passwordSQL, $database); //Kết nối đến MySQL và lấy thông tin MySQL bỏ vào biến $connect
    if ($connect->connect_error) { // Kiểm tra kết nối của code tới MySQL -> Nếu đéo thành công thì echo ra bên dưới đồng thời dừng code ở tại đây luôn
        die('Kết nối đéo thành công.');
    }
?>