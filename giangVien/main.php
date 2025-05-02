<?php
session_start();
require '../db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$searchTerm = $_GET['search'] ?? '';

// Lấy thông tin tài khoản giảng viên
$sql = "SELECT u.Person_Name, tk.ID AS Account_ID 
        FROM user u
        JOIN taikhoan tk ON u.Person_ID = tk.Person_ID
        WHERE tk.Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$teacherName = $userData['Person_Name'];
$accountID = $userData['Account_ID'];

// Lấy danh sách khóa học phụ trách
$courses = [];
$courseQuery = "SELECT Course_Name, Course_Description, Course_ID
                FROM course
                WHERE Teacher_ID = (SELECT Person_ID FROM taikhoan WHERE Username = ?)";
$stmt = $conn->prepare($courseQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Kết quả tìm kiếm
$searchResults = [];
if (!empty($searchTerm)) {
    $searchQuery = "SELECT Course_Name, Course_Description, Course_ID FROM course
                    WHERE Course_Name LIKE CONCAT('%', ?, '%') 
                       OR Course_Description LIKE CONCAT('%', ?, '%')";
    $stmt = $conn->prepare($searchQuery);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $searchResult = $stmt->get_result();
    while ($row = $searchResult->fetch_assoc()) {
        $searchResults[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giao diện giảng viên - TMU LMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../Assets/footer logo.png" alt="TMU Logo">
        </div>
        <div class="language-selector">VIETNAMESE (VI) ▼</div>
        <div class="user-actions">
            <div class="user-dropdown">
                <div class="user-avatar" id="userDropdownBtn">
                    <?= strtoupper(substr($teacherName, 0, 1)) ?>
                </div>
                <div class="dropdown-content" id="userDropdown">
                    <div class="user-info">
                        <div class="user-profile-avatar"><?= strtoupper(substr($teacherName, 0, 1)) ?></div>
                        <div class="user-name"><?= htmlspecialchars($teacherName) ?></div>
                        <div class="user-email"><?= htmlspecialchars($username) ?>@tmuedu.vn</div>
                    </div>
                    <a href="#" class="dropdown-item">👤 Trang cá nhân</a>
                    <a href="TKKH.php?account_id=<?= $accountID ?>" class="dropdown-item teacher-interface">👨‍🏫 Quản lý điểm số</a>
                    <a href="#" class="dropdown-item">⚙️ Cài đặt</a>
                    <a href="#" class="dropdown-item">❓ Trợ giúp</a>
                    <a href="../logout.php" class="dropdown-item">🚪 Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="left-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Khóa học phụ trách</h2>
                <form method="GET" style="display: flex; align-items: center;">
                    <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="🔍 Tìm khóa học..." style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 5px; margin-left: 10px;">
                </form>
            </div>

            <?php
                $list = !empty($searchTerm) ? $searchResults : $courses;
                ?>
                <ul class="course-list">
                <?php if (empty($list)): ?>
                    <li class="no-results">Không tìm thấy khóa học phù hợp.</li>
                <?php else: ?>
                    <?php foreach ($list as $course): ?>
                        <li>
                            <a href="../lophoc/lophocchitiet.php?course_id=<?= $course['Course_ID'] ?>&account_id=<?= $accountID ?>">
                                <strong><?= htmlspecialchars($course['Course_Name']) ?></strong><br>
                                <small><?= htmlspecialchars($course['Course_Description']) ?></small>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                </ul>


        </div>

        <div class="right-panel">
            <div class="panel">
                <h3 class="panel-title">Thông báo</h3>
                <p style="color: #777;">Chưa có thông báo mới</p>
            </div>
            <div class="panel">
                <h3 class="panel-title">Lịch</h3>
                <p style="color: #777;">Không có sự kiện sắp tới</p>
            </div>
        </div>
    </div>

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
