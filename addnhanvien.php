<?php
// Kết nối đến cơ sở dữ liệu
$connection = mysqli_connect('localhost', 'root', '', 'QL_NhanSu');

// Kiểm tra kết nối
if (!$connection) {
    die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

// Lấy danh sách phòng ban từ bảng PHONGBAN
$query = "SELECT Ma_Phong, Ten_Phong FROM PHONGBAN";
$result = mysqli_query($connection, $query);

// Kiểm tra xem có dữ liệu phòng ban hay không
if (mysqli_num_rows($result) > 0) {
    $phongBanOptions = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $maPhong = $row['Ma_Phong'];
        $tenPhong = $row['Ten_Phong'];
        $phongBanOptions .= "<option value='$maPhong'>$tenPhong</option>";
    }
} else {
    echo "Không có dữ liệu phòng ban.";
}

// Kiểm tra xem có dữ liệu được gửi từ biểu mẫu hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin nhân viên từ biểu mẫu
    $maNV = $_POST['maNV'];
    $tenNV = $_POST['tenNV'];
    $phai = $_POST['phai'];
    $noiSinh = $_POST['noiSinh'];
    $maPhong = $_POST['maPhong'];
    $luong = $_POST['luong'];

    // Thực hiện truy vấn để thêm nhân viên vào cơ sở dữ liệu
    $query = "INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES ('$maNV', '$tenNV', '$phai', '$noiSinh', '$maPhong', '$luong')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Chuyển hướng về trang hiển thị dữ liệu nhân viên sau khi thêm thành công
        header("Location: listnhanvien.php");
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($connection);
    }
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm nhân viên</title>
    <link rel="stylesheet" type="text/css" href="styleadd.css">

</head>
<body>
    <h1>Thêm nhân viên</h1>
    <form method="POST" action="addnhanvien.php">
        <label for="maNV">Mã nhân viên:</label>
        <input type="text" id="maNV" name="maNV" required><br>

        <label for="tenNV">Tên nhân viên:</label>
        <input type="text" id="tenNV" name="tenNV" required><br>

        <label for="phai">Phái:</label>
        <select id="phai" name="phai">
            <option value="NU">Nữ</option>
            <option value="NAM">Nam</option>
        </select><br>

        <label for="noiSinh">Nơi sinh:</label>
        <input type="text" id="noiSinh" name="noiSinh" required><br>

        <label for="maPhong">Tên Phòng:</label>
        <select id="maPhong" name="maPhong">
            <?php echo $phongBanOptions; ?>
        </select><br>
        <label for="luong">Lương:</label>
        <input type="text" id="luong" name="luong" required><br>

        <input type="submit" value="Thêm">
    </form>
</body>
</html>