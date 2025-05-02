<?php
session_start();
require '../db.php';           // trả về $conn (MySQLi)

header('Content-Type: application/json; charset=utf-8');

try {
    /* Đọc JSON hoặc form‑urlencoded */
    $raw = file_get_contents('php://input');
    $POST = json_decode($raw, true) ?? $_POST;

    $method = $_SERVER['REQUEST_METHOD'];

    /* ---------- GHI TIN ---------- */
    if ($method === 'POST' && isset($POST['message'])) {
        $course_id = (int)($POST['course_id'] ?? 0);
        $message   = trim($POST['message']);
        $user_name = $_SESSION['username'] ?? 'guest';

        if (!$course_id || $message === '') {
            throw new Exception('Thiếu course_id hoặc message');
        }

        $stmt = $conn->prepare(
            "INSERT INTO class_chat (course_id,user_name,message)
             VALUES (?,?,?)"
        );
        $stmt->bind_param('iss', $course_id, $user_name, $message);
        $stmt->execute();

        echo json_encode(['status'=>'ok']);
        exit;
    }

// --- LẤY TIN ---
$course_id = (int)($_GET['course_id'] ?? 0);
$last_id   = (int)($_GET['last_id']   ?? 0);

$stmt = $conn->prepare(
  "SELECT cc.id,
          cc.user_name,                -- username
          u.Person_Name AS sender,     -- ★ họ tên
          cc.message,
          cc.created_at
     FROM class_chat cc
     JOIN taikhoan tk ON tk.Username   = cc.user_name
     JOIN user      u  ON u.Person_ID = tk.Person_ID
    WHERE cc.course_id = ? AND cc.id > ?
 ORDER BY cc.id ASC"
);
$stmt->bind_param('ii', $course_id, $last_id);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));


} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
