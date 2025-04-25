<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình server SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // ✅ THAY ĐỔI tại đây
        $mail->Username   = 'minhquanpn2004@gmail.com';              // Gửi từ email này
        $mail->Password   = 'kche cusr beog qwfr';                      // ⚠️ Mật khẩu ứng dụng (App Password)

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Người gửi
        $mail->setFrom('minhquanpn2004@gmail.com', 'TMU LMS');

        // Người nhận
        $mail->addAddress($toEmail);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP đặt lại mật khẩu - TMU LMS';
        $mail->Body    = "<p>Xin chào,</p>
                          <p>Đây là mã OTP của bạn để đặt lại mật khẩu:</p>
                          <h2>$otp</h2>
                          <p>Vui lòng không chia sẻ mã này với bất kỳ ai.</p>
                          <p>Trân trọng,<br>TMU LMS</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Lỗi gửi OTP: {$mail->ErrorInfo}");
        return false;
    }
}
