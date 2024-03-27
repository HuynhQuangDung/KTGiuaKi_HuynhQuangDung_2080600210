<?php
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Kết nối đến cơ sở dữ liệu
    $connection = mysqli_connect('localhost', 'root', '', 'QL_NhanSu');

    // Kiểm tra kết nối
    if (!$connection) {
        die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
    }

    // Kiểm tra xem có phương thức POST được gửi lên hay không
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sử dụng câu lệnh prepared statement để xóa nhân viên khỏi cơ sở dữ liệu
        $query = "DELETE FROM NHANVIEN WHERE Ma_NV = ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, 's', $id);
        $result = mysqli_stmt_execute($statement);

        if ($result) {
            // Chuyển hướng về trang hiển thị danh sách nhân viên sau khi xóa thành công
            header("Location: listnhanvien.php");
            exit();
        } else {
            echo "Lỗi xóa nhân viên: " . mysqli_error($connection);
        }
    }

    // Sử dụng câu lệnh prepared statement để truy vấn thông tin nhân viên cần xóa
    $query = "SELECT * FROM NHANVIEN WHERE Ma_NV = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, 's', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    if (!$result) {
        die("Lỗi truy vấn: " . mysqli_error($connection));
    }

    // Kiểm tra xem có bản ghi tương ứng với id hay không
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>XÓA NHÂN VIÊN</title>
            <link rel="stylesheet" type="text/css" href="styledelete.css">
        </head>
        <body>
            <h1>Xóa nhân viên</h1>
            <p>Bạn có chắc chắn muốn xóa nhân viên "<?php echo $row['Ten_NV']; ?>"?</p>
            <form method="POST" action="">
                <input type="submit" value="Xóa">
            </form>
        </body>
        </html>
        <?php
    } else {
        // Chuyển hướng về trang hiển thị danh sách nhân viên hoặc hiển thị thông báo không tìm thấy nhân viên
        header("Location: listnhanvien.php");
        exit();
    }

    // Đóng kết nối cơ sở dữ liệu
    mysqli_close($connection);
} else {
    echo "Không tìm thấy thông tin nhân viên!";
}
?>