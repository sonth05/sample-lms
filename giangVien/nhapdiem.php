<?php
session_start();
require '../db.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$course_id   = $_GET['course_id'] ?? null;
$teacher_id  = $_GET['account_id'] ?? null;
$search_term = $_GET['search'] ?? '';

if (!$teacher_id || !$course_id) {
    die("Thiếu thông tin course_id hoặc account_id.");
}

// XỬ LÝ LƯU ĐIỂM
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_scores'])) {
    $score1Arr = $_POST['score1'] ?? [];
    $score2Arr = $_POST['score2'] ?? [];
    $score3Arr = $_POST['score3'] ?? [];
    $score4Arr = $_POST['score4'] ?? [];
    $score5Arr = []; // sẽ tính động, không cần client gửi

    // 1) Validate tất cả điểm 1–4 trong 0–10
    foreach ($score1Arr as $i => $_) {
        $vals = [
            floatval($score1Arr[$i] ?? 0),
            floatval($score2Arr[$i] ?? 0),
            floatval($score3Arr[$i] ?? 0),
            floatval($score4Arr[$i] ?? 0),
        ];
        foreach ($vals as $v) {
            if ($v < 0 || $v > 10) {
                echo "<script>alert('❌ Điểm nhập không hợp lệ! Mọi giá trị phải từ 0 đến 10.');</script>";
            
            }
        }
    }

    // 2) Tính score5 và cập nhật database
    $studentsQuery = "
      SELECT u.Person_ID 
      FROM user u
      JOIN taikhoan tk ON tk.Person_ID = u.Person_ID
      JOIN score s    ON s.User_ID = u.Person_ID
      WHERE s.Course_ID = ?
    ";
    $stmt = $conn->prepare($studentsQuery);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $updateQuery = "
      UPDATE score 
      SET Score1 = ?, Score2 = ?, Score3 = ?, Score4 = ?, Score5 = ?
      WHERE User_ID = ? AND Course_ID = ?
    ";
    $updStmt = $conn->prepare($updateQuery);

    foreach ($rows as $i => $row) {
        $uid = $row['Person_ID'];
        $s1 = floatval($score1Arr[$i]);
        $s2 = floatval($score2Arr[$i]);
        $s3 = floatval($score3Arr[$i]);
        $s4 = floatval($score4Arr[$i]);
        $s5 = $s1*0.1 + $s2*0.15 + $s3*0.15 + $s4*0.6;
        $updStmt->bind_param("dddddii", $s1, $s2, $s3, $s4, $s5, $uid, $course_id);
        $updStmt->execute();
    }

    echo "<script>alert('✅ Đã lưu điểm thành công!');</script>";

    // Redirect về chính trang nhập điểm để tránh chạy tiếp các đoạn dưới
    header("Location: nhapdiem.php?course_id=$course_id&account_id=$teacher_id");
    exit;
}

_AFTER_SAVE:

// Lấy thông tin giảng viên và khóa học
$infoQuery = "
  SELECT u.Person_Name, u.Person_ID, c.Course_Name, c.Class_Code, c.Schedule
  FROM user u
  JOIN course c ON c.Teacher_ID = u.Person_ID
  WHERE u.Person_ID = ? AND c.Course_ID = ?
";
$infoStmt = $conn->prepare($infoQuery);
$infoStmt->bind_param("ii", $teacher_id, $course_id);
$infoStmt->execute();
$info = $infoStmt->get_result()->fetch_assoc();

// Lấy danh sách sinh viên
$studentsQuery = "
  SELECT u.Person_ID, u.Person_Name, u.Birthday, tk.Username,
         s.Score1, s.Score2, s.Score3, s.Score4
  FROM user u
  JOIN taikhoan tk ON tk.Person_ID = u.Person_ID
  JOIN score s    ON s.User_ID = u.Person_ID
  WHERE s.Course_ID = ?
";
if ($search_term !== '') {
    $studentsQuery .= " AND (u.Person_Name LIKE CONCAT('%', ?, '%') OR tk.Username LIKE CONCAT('%', ?, '%'))";
    $studentsStmt = $conn->prepare($studentsQuery);
    $studentsStmt->bind_param("iss", $course_id, $search_term, $search_term);
} else {
    $studentsStmt = $conn->prepare($studentsQuery);
    $studentsStmt->bind_param("i", $course_id);
}
$studentsStmt->execute();
$students = $studentsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

function extractNameParts($fullName) {
    $parts     = explode(" ", trim($fullName));
    $lastName  = implode(" ", array_slice($parts, 0, -1));
    $firstName = end($parts);
    return [$lastName, $firstName];
}
if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    $exportQuery = "SELECT u.Person_Name, u.Birthday, tk.Username, s.Score1, s.Score2, s.Score3, s.Score4, s.Score5
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
        ['STT', 'MÃ SV', 'HỌ VÀ TÊN', 'NGÀY SINH', 'ĐIỂM CHUYÊN CẦN', 'ĐIỂM KT1', 'ĐIỂM KT2', 'ĐIỂM THẢO LUẬN', 'ĐIỂM THI'],
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
        $sheet->setCellValue("I{$i}", $row['Score5']);
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
    <style>
      /* Cho tất cả các input trong table chiếm 100% chiều rộng cell */
      table input[type="text"],
      table input[type="number"] {
        width: 100%;
        box-sizing: border-box;
        padding: 2px;
        font-size: inherit;
      }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="header">
        <div class="logo">TM</div>
        <div class="header-text">SCORE MANAGEMENT</div>
    </div>
    <div class="menu-item"><div class="menu-icon">O</div>THÔNG TIN CÁ NHÂN</div>
    <div class="menu-item"><a href="#" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>THÔNG TIN CÁ NHÂN</a></div>
    <div class="menu-item"><a href="TKKH.php" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>NHẬP ĐIỂM QUÁ TRÌNH</a></div>
    <div class="menu-item"><a href="#" style="text-decoration: none; color: inherit;"><div class="menu-icon">□</div>ĐƠN XIN SỬA ĐIỂM</a></div>
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
                    <th>ĐIỂM KIỂM TRA</th>
                    <th>ĐIỂM THẢO LUẬN</th>
                    <th>ĐIỂM THI</th>
                    <th>ĐIỂM TỔNG</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($students as $index => $st):
            list($ln, $fn) = extractNameParts($st['Person_Name']);
            $s1 = $st['Score1'];
            $s2 = $st['Score2'];
            $s3 = $st['Score3'];
            $s4 = $st['Score4'];
            $s5 = round($s1*0.1 + $s2*0.15 + $s3*0.15 + $s4*0.6, 1);
        ?>
          <tr>
            <td><?= $index+1 ?></td>
            <td><?= htmlspecialchars($st['Username']) ?></td>
            <td><?= htmlspecialchars($ln) ?></td>
            <td><?= htmlspecialchars($fn) ?></td>
            <td><?= date('d/m/Y', strtotime($st['Birthday'])) ?></td>
            <td><input type="number" step="0.1" min="0" max="10" name="score1[]" value="<?=$s1?>"></td>
            <td><input type="number" step="0.1" min="0" max="10" name="score2[]" value="<?=$s2?>"></td>
            <td><input type="number" step="0.1" min="0" max="10" name="score3[]" value="<?=$s3?>"></td>
            <td><input type="number" step="0.1" min="0" max="10" name="score4[]" value="<?=$s4?>"></td>
            <td><input type="number" class="score5" name="score5[]" value="<?=$s5?>" readonly onfocus="alert('Điểm tổng không được sửa');"></td>
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


  <script>
  // Tự động tính lại Score5 khi sửa Score1–4
  document.querySelectorAll('tbody tr').forEach(row=>{
    const ins = row.querySelectorAll('input[type=number]');
    const out5 = row.querySelector('input.score5');
    function recalc(){
      let [a,b,c,d]=[...ins].map(i=>parseFloat(i.value)||0);
      for(let v of [a,b,c,d]){
        if(v<0||v>10){ alert('Điểm phải từ 0 đến 10'); return; }
      }
      out5.value=(a*0.1+b*0.15+c*0.15+d*0.6).toFixed(2);
    }
    ins.forEach(i=>i.addEventListener('input',recalc));
  });
  </script>
</body>
</html>
