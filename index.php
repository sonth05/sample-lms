<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS TMU - Hệ thống học trực tuyến Đại học Thương mại</title>
    <link rel="shortcut icon" href="https://cdn-static.tmu.edu.vn/moodledata/filedir/46/0c/460ca143a07e4d6e30e5c62c4dd99c8fae6f9ed5.jpg">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="moodle, LMS TMU - Hệ thống học trực tuyến Đại học Thương mại">
    <meta name="description" content="LMS TMU - Hệ thống học trực tuyến Đại học Thương mại">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header transparent" id="header">
        <div class="container">
            <a href="#" class="logo-container">
                <img src="Assets/logo tmu backgorund.png" alt="TMU Logo" class="logo-img">
            </a>
            <div class="header-right">
                <div class="language-selector">
                    <span>VIETNAMESE (VI)</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="login-register">
                    <a href="#" id="loginButton">Đăng nhập/Đăng ký</a>
                </div>
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN SECTION -->
    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Hệ thống học trực tuyến Đại học Thương mại</h1>
                <a href="login/login2.php">
                    <button class="start-btn" id="startBtn">Bắt đầu ngay</button>
                </a>
            </div>
            <div class="wave-separator"></div>
        </section>

        <section class="content-section">
            <div class="container">
                <!-- Nội dung khác nếu cần -->
            </div>
        </section>
    </main>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Đăng nhập vào tài khoản của bạn</h2>
            <form>
                <input type="text" placeholder="Tên tài khoản" required>
                <input type="password" placeholder="Mật khẩu" required>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Nhớ tên tài khoản</label>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Quên mật khẩu?</a>
                    </div>
                </div>
                
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-container">
            <div class="footer-info">
                <img src="Assets/footer logo.png" alt="TMU Logo" class="footer-logo">
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

    <!-- SCRIPT -->
    <script src="login.js"></script>
    <script>
        // Xử lý modal
        const modal = document.getElementById("loginModal");
        const btn = document.getElementById("loginButton");
        const closeBtn = document.getElementsByClassName("close")[0];

        btn.onclick = function(e) {
            e.preventDefault();
            modal.style.display = "flex";
        };

        closeBtn.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>
</html>
