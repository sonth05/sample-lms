<?php
session_start();
require '../db.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$course_id = $_GET['course_id'] ?? null;
$teacher_id = $_GET['account_id'] ?? null;
$search_term = $_GET['search'] ?? '';

if (!$teacher_id || !$course_id) {
    die("Thiếu thông tin course_id hoặc account_id.");
}

// Nếu submit form lưu điểm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_scores'])) {
    $score1Arr = $_POST['score1'] ?? [];
    $score2Arr = $_POST['score2'] ?? [];
    $score3Arr = $_POST['score3'] ?? [];
    $score4Arr = $_POST['score4'] ?? [];

    // 1) validate all scores
    $invalid = false;
    foreach ($score1Arr as $i => $v) {
        $vals = [
            floatval($score1Arr[$i] ?? 0),
            floatval($score2Arr[$i] ?? 0),
            floatval($score3Arr[$i] ?? 0),
            floatval($score4Arr[$i] ?? 0),
        ];
        foreach ($vals as $vv) {
            if ($vv < 0 || $vv > 10) {
                $invalid = true;
                break 2;
            }
        }
    }

    if ($invalid) {
        echo "<script>alert('❌ Điểm nhập không hợp lệ! Mọi giá trị phải nằm trong khoảng 0–10.');</script>";
    } else {
        // 2) lấy danh sách sinh viên
        $studentsQuery = "SELECT u.Person_ID FROM user u
                          JOIN taikhoan tk ON tk.Person_ID = u.Person_ID
                          JOIN score s ON s.User_ID = u.Person_ID
                          WHERE s.Course_ID = ?";
        $studentsStmt = $conn->prepare($studentsQuery);
        $studentsStmt->bind_param("i", $course_id);
        $studentsStmt->execute();
        $studentRows = $studentsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // 3) cập nhật từng sinh viên, tính Score5
        $updateQuery = "UPDATE score 
                        SET Score1 = ?, Score2 = ?, Score3 = ?, Score4 = ?, Score5 = ?
                        WHERE User_ID = ? AND Course_ID = ?";
        $updateStmt = $conn->prepare($updateQuery);

        foreach ($studentRows as $i => $row) {
            $userId = $row['Person_ID'];
            $s1 = floatval($score1Arr[$i]);
            $s2 = floatval($score2Arr[$i]);
            $s3 = floatval($score3Arr[$i]);
            $s4 = floatval($score4Arr[$i]);
            // Tính Score5 theo tỉ lệ
            $s5 = $s1*0.1 + $s2*0.15 + $s3*0.15 + $s4*0.6;

            // gán 7 tham số: 4 điểm, điểm tổng Score5, userId, courseId
            $updateStmt->bind_param("dddddii", $s1, $s2, $s3, $s4, $s5, $userId, $course_id);
            $updateStmt->execute();
        }

        echo "<script>alert('✅ Đã lưu điểm thành công!');</script>";
    }
}


// Lấy thông tin giảng viên và khóa học
$infoQuery = "SELECT u.Person_Name, u.Person_ID, c.Course_Name, c.Class_Code, c.Schedule
              FROM user u
              JOIN course c ON c.Teacher_ID = u.Person_ID
              WHERE u.Person_ID = ? AND c.Course_ID = ?";

$infoStmt = $conn->prepare($infoQuery);
$infoStmt->bind_param("ii", $teacher_id, $course_id);
$infoStmt->execute();
$infoResult = $infoStmt->get_result();
$info = $infoResult->fetch_assoc();

// Lấy danh sách sinh viên trong khóa học
$studentsQuery = "SELECT u.Person_ID, u.Person_Name, u.Birthday, u.Gender,
                         tk.Username, s.Score1, s.Score2, s.Score3, s.Score4
                  FROM user u
                  JOIN taikhoan tk ON tk.Person_ID = u.Person_ID
                  JOIN score s ON s.User_ID = u.Person_ID
                  WHERE s.Course_ID = ?";

if (!empty($search_term)) {
    $studentsQuery .= " AND (u.Person_Name LIKE CONCAT('%', ?, '%') OR tk.Username LIKE CONCAT('%', ?, '%'))";
    $studentsStmt = $conn->prepare($studentsQuery);
    $studentsStmt->bind_param("iss", $course_id, $search_term, $search_term);
} else {
    $studentsStmt = $conn->prepare($studentsQuery);
    $studentsStmt->bind_param("i", $course_id);
}

$studentsStmt->execute();
$studentsResult = $studentsStmt->get_result();
$students = $studentsResult->fetch_all(MYSQLI_ASSOC);

function extractNameParts($fullName) {
    $parts = explode(" ", trim($fullName));
    $lastName = implode(" ", array_slice($parts, 0, -1));
    $firstName = end($parts);
    return [$lastName, $firstName];
}

if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    $exportQuery = "SELECT u.Person_Name, u.Birthday, tk.Username, s.Score1, s.Score2, s.Score3, s.Score4
                    FROM user u
                    JOIN taikhoan tk ON tk.Person_ID = u.Person_ID
                    JOIN score s ON s.User_ID = u.Person_ID
                    WHERE s.Course_ID = ?";

    $exportStmt = $conn->prepare($exportQuery);
    $exportStmt->bind_param("i", $course_id);
    $exportStmt->execute();
    $result = $exportStmt->get_result();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Danh sách điểm");

    $headers = [
        ['Năm học:', '2024-2025','','','',  'Học kỳ:', 'Học kỳ 2'],
        ['Học phần:', $info['Course_Name'],'','','', 'Mã lớp học phần:', $info['Class_Code']],
        [''],
        ['STT', 'MÃ SV', 'HỌ VÀ TÊN', 'NGÀY SINH', 'ĐIỂM CHUYÊN CẦN', 'ĐIỂM KT1', 'ĐIỂM KT2', 'ĐIỂM THẢO LUẬN'],
    ];
    $sheet->fromArray($headers[0], null, 'A1');
    $sheet->fromArray($headers[1], null, 'A2'); // ✅ thiếu dòng này
    $sheet->fromArray($headers[3], null, 'A4'); 

    $i = 5;
    $stt = 1;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$i}", $stt++);
        $sheet->setCellValue("B{$i}", $row['Username']);
        $sheet->setCellValue("C{$i}", $row['Person_Name']);
        $sheet->setCellValue("D{$i}", date('d/m/Y', strtotime($row['Birthday'])));
        $sheet->setCellValue("E{$i}", $row['Score1']);
        $sheet->setCellValue("F{$i}", $row['Score2']);
        $sheet->setCellValue("G{$i}", $row['Score3']);
        $sheet->setCellValue("H{$i}", $row['Score4']);
        $i++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="baocao_diem.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập điểm quá trình</title>
    <link rel="stylesheet" href="nhapdiem.css">
</head>
<body>
<div class="sidebar">
    <div class="header">
        <div class="logo">TM</div>
        <div class="header-text">SCORE MANAGEMENT</div>
    </div>
    <div class="menu-item"><div class="menu-icon">O</div>THÔNG TIN CÁ NHÂN</div>
    <div class="menu-item"><a href="giangvien.html" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>THÔNG TIN CÁ NHÂN</a></div>
    <div class="menu-item"><a href="TKKH.php" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>NHẬP ĐIỂM QUÁ TRÌNH</a></div>
    <div class="menu-item"><a href="baocao.html" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>ĐƠN XIN SỬA ĐIỂM</a></div>
</div>
<div class="content">
    <div class="page-title" style="display: flex; justify-content: space-between; align-items: center;">
        <div>Nhập điểm quá trình</div>
        <div><?= htmlspecialchars($info['Person_Name']) ?> - (<?= $info['Person_ID'] ?>)</div>
    </div>

    <div class="info-row">
        <div class="info-label">Năm học:</div>
        <div class="info-value">2024-2025</div>
        <div class="info-label" style="margin-left: 100px;">Học kỳ:</div>
        <div class="info-value">Học kỳ 2</div>
    </div>

    <div class="info-row">
        <div class="info-label">Học phần:</div>
        <div class="info-value"><?= htmlspecialchars($info['Course_Name']) ?></div>
        <div class="info-label" style="margin-left: 100px;">Mã lớp học phần:</div>
        <div class="info-value"><?= htmlspecialchars($info['Class_Code']) ?></div>
    </div>

    <div class="info-row" style="text-align: right;">
        <form method="GET">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <input type="hidden" name="account_id" value="<?= $teacher_id ?>">
            <input type="text" name="search" placeholder="🔍 Tìm tên hoặc mã SV" value="<?= htmlspecialchars($search_term) ?>" style="padding: 4px 8px; width: 200px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        </form>
    </div>

    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>MÃ SV</th>
                    <th>HỌ</th>
                    <th>TÊN</th>
                    <th>NGÀY SINH</th>
                    <th>ĐIỂM CHUYÊN CẦN</th>
                    <th>ĐIỂM KT1</th>
                    <th>ĐIỂM KT2</th>
                    <th>ĐIỂM THẢO LUẬN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $student): 
                    list($lastName, $firstName) = extractNameParts($student['Person_Name']); ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($student['Username']) ?></td>
                        <td><?= htmlspecialchars($lastName) ?></td>
                        <td><?= htmlspecialchars($firstName) ?></td>
                        <td><?= date('d/m/Y', strtotime($student['Birthday'])) ?></td>
                        <td><input type="text" name="score1[]" value="<?= htmlspecialchars($student['Score1']) ?>"></td>
                        <td><input type="text" name="score2[]" value="<?= htmlspecialchars($student['Score2']) ?>"></td>
                        <td><input type="text" name="score3[]" value="<?= htmlspecialchars($student['Score3']) ?>"></td>
                        <td><input type="text" name="score4[]" value="<?= htmlspecialchars($student['Score4']) ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 20px; text-align: right;">
    <button type="submit" name="save_scores" class="btn-save" 
            style="background-color:#007bff; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">
        💾 Lưu điểm
    </button>

    <a href="?course_id=<?= $course_id ?>&account_id=<?= $teacher_id ?>&export=excel<?= $search_term ? '&search=' . urlencode($search_term) : '' ?>" 
       class="btn-export"
       style="margin-left: 10px; background-color:green; color:white; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;">
        📥 Xuất báo cáo
    </a>
</div>

    </form>
</div>
</body>
</html>