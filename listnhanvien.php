<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>THÔNG TIN NHÂN VIÊN</title>
    <link rel="stylesheet" type="text/css" href="stylelist.css">
</head>
<body>
    <?php
    session_start();
    $role = $_SESSION['role'];

    if ($role === 'admin') {

        echo '<a href="addnhanvien.php"><button>Thêm nhân viên</button></a>';
    }
    ?>
    <h1>THÔNG TIN NHÂN VIÊN</h1>
    <?php

    $connection = mysqli_connect('localhost', 'root', '', 'QL_NhanSu');


    if (!$connection) {
        die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
    }
    
    // Xác định trang hiện tại
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Số lượng nhân viên trên mỗi trang
    $limit = 5;

    // Tính toán vị trí bắt đầu của các nhân viên trên trang hiện tại
    $start = ($page - 1) * $limit;
    // Truy vấn dữ liệu từ bảng NHANVIEN với giới hạn trang và vị trí bắt đầu
    $query = "SELECT Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong FROM NHANVIEN LIMIT $start, $limit";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($connection));
    }
    ?>

    <div id="data-container">
        <table>
            <tr>
                <th>Mã Nhân Viên</th>
                <th>Tên Nhân Viên</th>
                <th>Phái</th>
                <th>Nơi Sinh</th>
                <th>Mã Phòng</th>
                <th>Lương</th>
                <th>ACTION</th>
            </tr>
            <?php
            // Duyệt qua các hàng dữ liệu và hiển thị
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Ma_NV'] . "</td>";
                echo "<td>" . $row['Ten_NV'] . "</td>";
                echo "<td><img class='thumbnail' src='http://localhost/KTGiuaKi/images/" . ($row['Phai'] == 'NU' ? 'nu.png' : 'nam.png') . "' alt='Hình ảnh'></td>";
                echo "<td>" . $row['Noi_Sinh'] . "</td>";
                echo "<td>" . $row['Ma_Phong'] . "</td>";
                echo "<td>" . $row['Luong'] . "</td>";
                // echo "<td><a href='edit_employee.php?id=" . $row['Ma_NV'] . "'>Sửa</a> | <a href='delete_employee.php?id=" . $row['Ma_NV'] . "'>Xóa</a></td>";
                if ($role === 'admin') {
                    // Hiển thị liên kết "Sửa" và "Xóa" cho vai trò "admin"
                    echo "<td><a href='editnhanvien.php?id=" . $row['Ma_NV'] . "'>Sửa</a> | <a href='xoanhanvien.php?id=" . $row['Ma_NV'] . "'>Xóa</a></td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <?php
    // Truy vấn tổng số lượng nhân viên
    $totalQuery = "SELECT COUNT(*) AS total FROM NHANVIEN";
    $totalResult = mysqli_query($connection, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalEmployees = $totalRow['total'];

    // Tính toán số lượng trang
    $totalPages = ceil($totalEmployees / $limit);

    // Hiển thị các liên kết phân trang
    echo "<div>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
    echo "</div>";
    ?>

</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
mysqli_close($connection);
?>