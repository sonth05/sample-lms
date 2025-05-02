<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = $_POST['remember'] ?? '';

    $sql = "SELECT tk.Username, tk.Password, tk.Failed_Attempts, tk.Is_Locked, u.Role 
            FROM taikhoan tk 
            JOIN user u ON tk.Person_ID = u.Person_ID 
            WHERE tk.Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $login_success = false;
    $role = '';
    $error = '';

    if ($row = $result->fetch_assoc()) {
        if ($row['Is_Locked']) {
            $error = 'locked';
        } elseif ($password === $row['Password']) {
            $login_success = true;
            $role = strtolower($row['Role']);
            $_SESSION['username'] = $row['Username'];
            $_SESSION['role'] = $row['Role'];

            $resetAttempts = $conn->prepare("UPDATE taikhoan SET Failed_Attempts = 0 WHERE Username = ?");
            $resetAttempts->bind_param("s", $username);
            $resetAttempts->execute();
        } else {
            $attempts = $row['Failed_Attempts'] + 1;
            $is_locked = $attempts >= 5 ? 1 : 0;

            $updateStmt = $conn->prepare("UPDATE taikhoan SET Failed_Attempts = ?, Is_Locked = ? WHERE Username = ?");
            $updateStmt->bind_param("iis", $attempts, $is_locked, $username);
            $updateStmt->execute();

            $error = $is_locked ? 'locked' : 'invalid';
        }
    } else {
        $error = 'invalid';
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $login_success,
        'role' => $role,
        'error' => $error
    ]);
    exit;
}
?>


<!-- Giao diện HTML phía dưới không thay đổi -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMU - Hệ thống quản lý học trực tuyến</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="login2.css">
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
                    <a href="#" class="login-register">Đăng nhập/Đăng ký</a>
                </div>
                <div class="search-button">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </header>

    <div class="main-nav">
        <div class="container nav-container">
            <div class="system-title">TMU - HỆ THỐNG QUẢN LÝ HỌC TRỰC TUYẾN</div>
            <div class="breadcrumb">
                <a href="../index.php">Trang chủ</a> / <span>Đăng nhập</span>
            </div>
        </div>
    </div>

    <main class="main-content">
        <div class="login-container">
            <h1 class="login-title">Đăng nhập vào tài khoản của bạn</h1>
            
            <div id="error-message" class="error-message" style="display: none;">
                Tên tài khoản hoặc mật khẩu không đúng. Vui lòng thử lại.
            </div>
            
            <form id="login-form" class="login-form">
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="Tên tài khoản" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Nhớ tên tài khoản</label>
                    </div>
                    <a href="forgotps.php" class="forgot-password">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="login-button">Đăng nhập</button>
            </form>
            
            
        </div>
        
    </main>

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
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('remember');
    const errorMessage = document.getElementById('error-message');

    // Load username nếu đã lưu trong localStorage
    if (localStorage.getItem('rememberedUsername')) {
        usernameInput.value = localStorage.getItem('rememberedUsername');
        rememberCheckbox.checked = true;
    }

    loginForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();

        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);
        formData.append('remember', rememberCheckbox.checked ? '1' : '');

        try {
            const response = await fetch('login2.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // ✅ Ghi nhớ tài khoản nếu được chọn
                if (rememberCheckbox.checked) {
                    localStorage.setItem('rememberedUsername', username);
                } else {
                    localStorage.removeItem('rememberedUsername');
                }

                // ✅ Chuyển trang theo vai trò
                if (data.role === 'student') {
                    window.location.href = '../Sinhvien/main.php';
                } else if (data.role === 'teacher') {
                    window.location.href = '../giangVien/main.php';
                }
            } else {
                // ❌ Xử lý lỗi
                if (data.error === 'locked') {
                    errorMessage.textContent = 'Tài khoản đã bị khoá do nhập sai quá 5 lần.';
                } else if (data.error === 'invalid') {
                    errorMessage.textContent = 'Tên tài khoản hoặc mật khẩu không đúng. Vui lòng thử lại.';
                } else {
                    errorMessage.textContent = 'Đã xảy ra lỗi. Vui lòng thử lại.';
                }

                errorMessage.style.display = 'block';
                passwordInput.value = '';
            }

        } catch (error) {
            console.error('Lỗi gửi form:', error);
            errorMessage.textContent = 'Không thể kết nối tới máy chủ. Vui lòng thử lại sau.';
            errorMessage.style.display = 'block';
        }
    });
});
</script>

</body>
</html>
