<?php
session_start();
require '../db.php';
require 'send_otp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username_search'])) {
    $username = trim($_POST['username_search']);
    $stmt = $conn->prepare("SELECT Email FROM taikhoan WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row || empty($row['Email'])) {
        echo json_encode(['status' => 'invalid']);
    } else {
        $otp = rand(100000, 999999);
        $_SESSION['reset_username'] = $username;
        $_SESSION['otp'] = $otp;
        sendOTP($row['Email'], $otp);
        echo json_encode(['status' => 'valid']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $username = $_SESSION['reset_username'] ?? '';
    $otp_input = $_POST['otp'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($otp_input != $_SESSION['otp']) {
        echo json_encode(['status' => 'otp_invalid']);
    } elseif ($new_password !== $confirm_password) {
        echo json_encode(['status' => 'password_mismatch']);
    } else {
        $stmt = $conn->prepare("UPDATE taikhoan SET Password = ? WHERE Username = ?");
        $stmt->bind_param("ss", $new_password, $username);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMU - Đặt lại mật khẩu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="forgotps.css">
</head>
<body>
<header class="header">
        <div class="container header-container">
            <div class="logo-container">
                <a href="../index.php">
                    <img src="../Assets/logo tmu backgorund.png" alt="TMU Logo" class="logo">
                </a>
            </div>
            <div class="header-right">
                <div class="language-selector">
                    <span>VIETNAMESE (VI)</span> <i class="fas fa-chevron-down"></i>
                </div>
                <div class="auth-links">
                    <a href="#" class="login-register" id="loginButton">Đăng nhập/Đăng ký</a>
                </div>
                <div class="search-button">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </header>

<main>
    <div class="main-container">
        <div class="password-recovery-section">
            <p class="instructions">
                Để đặt lại mật khẩu của bạn, hãy cung cấp tên người dùng bên dưới. Nếu hợp lệ, một mã OTP sẽ được gửi đến email của bạn.
            </p>
            <div class="search-section">
                <form id="search-form">
                    <div class="form-group">
                        <label for="username_search">Tên tài khoản</label>
                        <input type="text" id="username_search" name="username_search" class="form-control" required>
                        <p id="search-error" style="color:red;display:none;">Tài khoản không tồn tại hoặc chưa có email.</p>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="search-button">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<div id="resetModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2>Đặt lại mật khẩu</h2>
        <p>Mã OTP đã được gửi về mail đăng ký trước</p>
        <form id="reset-form">
            <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
            <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
            <input type="text" name="otp" placeholder="Mã OTP" required>
            <button type="submit">Xác nhận</button>
            <p id="reset-msg" style="margin-top:10px;"></p>
        </form>
    </div>
</div>

<script>
document.getElementById('search-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const username = document.getElementById('username_search').value;
    const formData = new FormData();
    formData.append('username_search', username);

    const res = await fetch('', { method: 'POST', body: formData });
    const result = await res.json();

    if (result.status === 'valid') {
        document.getElementById('search-error').style.display = 'none';
        document.getElementById('resetModal').style.display = 'flex';
    } else {
        document.getElementById('search-error').style.display = 'block';
    }
});

document.getElementById('reset-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const res = await fetch('', { method: 'POST', body: formData });
    const result = await res.json();
    const msg = document.getElementById('reset-msg');

    if (result.status === 'success') {
        msg.innerText = '✅ Mật khẩu đã được cập nhật thành công! Đang chuyển hướng...';
        msg.style.color = 'green';
        // Chờ 2 giây rồi chuyển về trang đăng nhập
        setTimeout(() => {
            window.location.href = 'login2.php';
        }, 5000);
    } else if (result.status === 'password_mismatch') {
        msg.innerText = '❌ Mật khẩu không khớp!';
        msg.style.color = 'red';
    } else {
        msg.innerText = '❌ Mã OTP không đúng!';
        msg.style.color = 'red';
    }
});
</script>


<div class="footer">
        <div class="footer-container">
            <div class="footer-info">
                <img src="../Assets/footer logo.png" alt="TMU Logo" class="footer-logo">
            </div>
            <div class="campus-info">
                <h3>Cơ sở 1</h3>
                <div class="campus-details">
                    <p>Địa chỉ: 79 Hồ Tùng Mậu Cầu Giấy, Hà Nội</p>
                    <p>Điện thoại: (024) 3764 3219</p>
                    <p>Fax: (024) 37643228</p>
                    <p>Email: mail@tmu.edu.vn</p>
                </div>
            </div>
            <div class="campus-info">
                <h3>Cơ sở 2</h3>
                <div class="campus-details">
                    <p>Địa chỉ: đường Lý Thường Kiệt, phường Lê Hồng Phong, Phủ Lý, Hà Nam</p>
                    <p>Điện thoại: (024) 3764 3219</p>
                    <p>Fax: (024) 37643228</p>
                    <p>Email: mail@tmu.edu.vn</p>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <p>Copyright © 2022 TMU. All Rights Reserved.</p>
    </div>
</body>
</html>
