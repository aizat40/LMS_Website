<?php
// 1. Panggil autoloader Composer
// Pastikan path ini betul mengikut kedudukan folder 'vendor' anda
require '../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../config.php';

$courseID = $_GET['course_id'];
$studentID = $_SESSION['user_id'];

$sql_info = "SELECT c.title, c.total_modules, u.email, u.username 
             FROM courses c, user1 u 
             WHERE c.id = ? AND u.id = ?";
$stmt_info = $conn->prepare($sql_info);
$stmt_info->bind_param("ii", $courseID, $studentID);
$stmt_info->execute();
$info = $stmt_info->get_result()->fetch_assoc();

$courseTitle = $info['title'];
$total = $info['total_modules'];
$userEmail = $info['email'];
$userName = $info['username'];

// 2. Masukkan ke database enroll_course
$sql_enroll = "INSERT INTO enroll_course (studentID, courseID, current_progress, completed_modules, total_modules, status) 
               VALUES (?, ?, 0, 0, ?, 'active')";
$stmt = $conn->prepare($sql_enroll);
$stmt->bind_param("iii", $studentID, $courseID, $total);

if ($stmt->execute()) {
    
    // 2. KONFIGURASI PHPMAILER
    $mail = new PHPMailer(true);

    try {
        // Tetapan SMTP (Gunakan Gmail App Password)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aizatazhar2004@gmail.com'; 
        $mail->Password   = 'vhfj yizc xmii hanh'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Penerima
        $mail->setFrom('no-reply@uthm.edu.my', 'Online Learning UTHM');
        $mail->addAddress($userEmail, $userName);

        // Kandungan Emel
        $mail->isHTML(true);
        $mail->Subject = "Enrollment Successful: " . $courseTitle;
        $mail->Body    = "
        <body style='background-color: #0f172a; padding: 20px; font-family: sans-serif; color: #f8fafc;'>
            <div style='max-width: 600px; margin: auto; background: #1e293b; padding: 30px; border-radius: 15px; border: 1px solid #334155;'>
                <h2 style='color: #8b5cf6;'>Hi $userName,</h2>
                <p>Welcome to <strong>$courseTitle</strong>! Your enrollment is confirmed.</p>
                <p style='color: #94a3b8;'>You can now access all modules and start learning at your own pace.</p>
                <br>
                <a href='https://lms-uthm.wuaze.com/student/index.php' 
                   style='background-color: #8b5cf6; color: white; padding: 12px 25px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;'>
                   Go to My Dashboard
                </a>
            </div>
        </body>";

        $mail->send();
    } catch (Exception $e) {
        // Ralat emel tidak akan menghentikan proses pendaftaran database
    }

    header("Location: index.php?msg=success");
}
?>