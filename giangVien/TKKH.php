<?php
session_start();
require '../db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$search = $_GET['search'] ?? '';

// Truy vấn thông tin giảng viên và các khóa học họ phụ trách
$query = "SELECT 
            u.Person_ID, 
            u.Person_Name, 
            c.Course_ID, 
            c.Course_Name, 
            c.Course_Description, 
            c.Class_Code, 
            c.Schedule, 
            c.Status
          FROM user u
          JOIN taikhoan tk ON u.Person_ID = tk.Person_ID
          JOIN course c ON c.Teacher_ID = u.Person_ID
          WHERE tk.Username = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
$teacherName = "";
while ($row = $result->fetch_assoc()) {
    $teacherName = $row['Person_Name'];
    $courses[] = $row;
}

// Nếu có từ khóa tìm kiếm, lọc danh sách khóa học
if (!empty($search)) {
    $courses = array_filter($courses, function($course) use ($search) {
        return stripos($course['Course_Name'], $search) !== false ||
               stripos($course['Class_Code'], $search) !== false ||
               stripos($course['Course_ID'], $search) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScoreManagements</title>
    <link rel="stylesheet" href="TKKH.css">
</head>
<body>
    <div class="sidebar">
        <div class="header">
            <div class="logo">TM</div>
            <div class="header-text">SCORE MANAGEMENT</div>
        </div>
        <div class="menu-item"><div class="menu-icon">O</div>THÔNG TIN CÁ NHÂN</div>
        <div class="menu-item"><a href="giangvien.html" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>QUẢN LÝ ĐIỂM</a></div>
        <div class="menu-item"><a href="TKKH.php" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>TÌM KIẾM KHÓA HỌC</a></div>
        <div class="menu-item"><a href="baocao.html" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>XUẤT BÁO CÁO</a></div>
    </div>

    <div class="content">
        <div class="page-title" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div class="toggle"><div class="toggle-circle"></div></div>
                Tìm kiếm khóa học
            </div>
            <div><?= htmlspecialchars($teacherName) ?> | <?= htmlspecialchars($username) ?> ▼</div>
        </div>

        <form method="GET" style="text-align: right; margin: 10px 0;">
            <input type="text" name="search" placeholder="🔍 Tìm theo mã, tên học phần..." value="<?= htmlspecialchars($search) ?>" style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; width: 250px;">
        </form>

        <div class="results-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>MÃ HỌC PHẦN</th>
                        <th>TÊN HỌC PHẦN</th>
                        <th>MÃ LỚP HỌC PHẦN</th>
                        <th>THỜI GIAN HỌC</th>
                        <th>TRẠNG THÁI</th>
                        <th>THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $index => $course): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($course['Course_ID']) ?></td>
                        <td><?= htmlspecialchars($course['Course_Name']) ?></td>
                        <td><?= htmlspecialchars($course['Class_Code']) ?></td>
                        <td><?= htmlspecialchars($course['Schedule']) ?></td>
                        <td><?= htmlspecialchars($course['Status']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn btn-info">Chi tiết</a>
                                <a href="nhapdiem.php?course_id=<?= $course['Course_ID'] ?>&account_id=<?= $course['Person_ID'] ?>" class="btn btn-view">Nhập điểm</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
