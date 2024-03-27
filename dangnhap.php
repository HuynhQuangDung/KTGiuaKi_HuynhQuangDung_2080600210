<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h1>Đăng nhập</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Kết nối đến cơ sở dữ liệu
        $connection = mysqli_connect('localhost', 'root', '', 'QL_NhanSu');

        // Kiểm tra kết nối
        if (!$connection) {
            die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
        }

        // Xây dựng truy vấn SQL để kiểm tra thông tin đăng nhập
        $query = "SELECT * FROM USER WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Lỗi truy vấn: " . mysqli_error($connection));
        }

        // Kiểm tra số lượng bản ghi trả về từ truy vấn
        if (mysqli_num_rows($result) == 1) {
            // Đăng nhập thành công, lấy thông tin người dùng
            $user = mysqli_fetch_assoc($result);

            // Lưu thông tin người dùng vào session hoặc cookie
            // Ví dụ:
            session_start();
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Đóng kết nối cơ sở dữ liệu
            mysqli_close($connection);

            // Chuyển hướng đến trang employee_list.php
            header('Location: listnhanvien.php');
            exit();
        } else {
            // Đăng nhập không thành công, hiển thị thông báo lỗi
            echo "<p>Sai tên đăng nhập hoặc mật khẩu. Vui lòng thử lại.</p>";
        }

        // Đóng kết nối cơ sở dữ liệu
        mysqli_close($connection);
    }
    ?>
    <form method="POST" action="dangnhap.php">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Đăng nhập">
    </form>
</body>
</html>