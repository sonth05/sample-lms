<?php
session_start();
require '../db.php';

// Nếu chưa đăng nhập, chuyển về trang login
if (!isset($_SESSION['username'])) {
    header("Location: login2.php");
    exit();
}

$username = $_SESSION['username'];
$search_term = $_GET['search'] ?? '';

// Truy vấn danh sách khóa học mà sinh viên đã tham gia
$sql = "SELECT DISTINCT c.Course_Name, c.Course_Description
        FROM score s
        JOIN course c ON s.Course_ID = c.Course_ID
        JOIN taikhoan tk ON s.User_ID = tk.Person_ID
        WHERE tk.Username = ?";

if (!empty($search_term)) {
    $sql .= " AND (c.Course_Name LIKE CONCAT('%', ?, '%') OR c.Course_Description LIKE CONCAT('%', ?, '%'))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $search_term, $search_term);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
}

$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thuongmai University LMS</title>
    <link rel="stylesheet" href="main.css">
    <style>
        <?php include 'main-style-inline.css'; ?>
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../Assets/footer logo.png" alt="Thuongmai University Logo">
        </div>
        <div class="language-selector">VIETNAMESE (VI) ▼</div>
        <div class="user-actions">
            <div class="notification_icon"></div>
            <button class="action-button">?</button>
            <button class="action-button">✉</button>
            <button class="action-button">⚙</button>
            <div class="user-dropdown">
                <div class="user-avatar" id="userDropdownBtn">NVA</div>
                <div class="dropdown-content" id="userDropdown">
                    <div class="user-info">
                        <div class="user-profile-avatar">NVA</div>
                        <div class="user-name"><?= htmlspecialchars($username) ?></div>
                        <div class="user-email"><?= htmlspecialchars($username) ?>@tmuedu.vn</div>
                    </div>
                    <a href="#" class="dropdown-item">👤 Trang cá nhân</a>
                    <a href="#" class="dropdown-item">📋 Điểm số</a>
                    <a href="#" class="dropdown-item">💬 Tin nhắn</a>
                    <a href="#" class="dropdown-item">⚙️ Cài đặt</a>
                    <a href="#" class="dropdown-item">📱 Ứng dụng di động</a>
                    <a href="#" class="dropdown-item">❓ Trợ giúp</a>
                    <a href="logout.php" class="dropdown-item">🚪 Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="left-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h2>Khóa học</h2>
                <button class="button">
                    <span class="button-icon">▾</span>
                    Tất cả (ngoại trừ Ẩn)
                </button>
            </div>

            <form method="GET" style="margin-bottom: 15px;">
                <input type="text" name="search" placeholder="🔍 Tìm khóa học..." value="<?= htmlspecialchars($search_term) ?>" style="width: 30%; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
            </form>

            <div class="empty-courses">
                <?php if (count($courses) === 0): ?>
                    <div class="empty-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24">
                            <path fill="#aaa" d="M4 8h16v10H4z" opacity="0.3"/>
                            <path fill="#aaa" d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-8-2h4v2h-4V4zm8 14H4V8h16v10z"/>
                        </svg>
                    </div>
                    <p>Không có khóa học</p>
                <?php else: ?>
                    <ul style="width: 100%; padding: 0 10px;">
                        <?php foreach ($courses as $course): ?>
                            <li style="margin-bottom: 15px; list-style: none; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                                <strong><?= htmlspecialchars($course['Course_Name']) ?></strong><br>
                                <small><?= htmlspecialchars($course['Course_Description']) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <div class="right-panel">
            <div class="panel">
                <h3 class="panel-title">Dòng thời gian</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <button class="button" style="padding: 5px 10px; font-size: 14px;">⏱ ▾</button>
                    <button class="button" style="padding: 5px 10px; font-size: 14px;">⫶ ▾</button>
                </div>
                <div style="display: flex; justify-content: center; padding: 30px 0;">
                    <svg width="50" height="50" viewBox="0 0 24 24">
                        <path fill="#aaa" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 15l-5-5h3V9h4v4h3l-5 5z"/>
                    </svg>
                </div>
                <p style="text-align: center; color: #777;">Không có hoạt động sắp tới hạn</p>
            </div>

            <div class="panel">
                <h3 class="panel-title">Sự kiện sắp diễn ra</h3>
                <p style="color: #777;">Không có sự kiện nào sắp diễn ra</p>
                <p style="color: #777;">Đi đến lịch ...</p>
            </div>

            <div class="panel">
                <h3 class="panel-title">Lịch</h3>
                <div class="month-nav">
                    <span>◄</span><span>Tháng 4 - 2025</span><span>►</span>
                </div>
                <table class="calendar">
                    <thead>
                        <tr><th>T2</th><th>T3</th><th>T4</th><th>T5</th><th>T6</th><th>T7</th><th>CN</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr>
                        <tr><td>8</td><td>9</td><td>10</td><td class="today">11</td><td>12</td><td>13</td><td>14</td></tr>
                        <tr><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
                        <tr><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
                        <tr><td>29</td><td>30</td><td></td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer style="text-align: center; padding: 10px; color: #777; font-size: 12px; border-top: 1px solid #ddd; margin-top: 20px;">
        Copyright © 2022 TMU. All Rights Reserved.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdownBtn = document.getElementById('userDropdownBtn');
            const userDropdown = document.getElementById('userDropdown');

            userDropdownBtn.addEventListener('click', function() {
                userDropdown.classList.toggle('show');
            });

            window.addEventListener('click', function(event) {
                if (!event.target.matches('.user-avatar') && !userDropdown.contains(event.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>