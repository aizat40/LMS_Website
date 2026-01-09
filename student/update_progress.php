<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $studentID = $_SESSION['user_id'];
    $courseID = (int)$_POST['courseID'];

    // 1. Ambil jumlah modul asal
    $sql_course = "SELECT total_modules FROM courses WHERE id = ?";
    $stmt_c = $conn->prepare($sql_course);
    $stmt_c->bind_param("i", $courseID);
    $stmt_c->execute();
    $course_data = $stmt_c->get_result()->fetch_assoc();
    $total = $course_data['total_modules'];

    // 2. Ambil progress semasa
    $sql_progress = "SELECT completed_modules FROM enroll_course WHERE studentID = ? AND courseID = ?";
    $stmt_p = $conn->prepare($sql_progress);
    $stmt_p->bind_param("ii", $studentID, $courseID);
    $stmt_p->execute();
    $current_data = $stmt_p->get_result()->fetch_assoc();
    $completed = $current_data['completed_modules'];

    if ($completed < $total) {
        $new_completed = $completed + 1;
        $new_progress = ($new_completed / $total) * 100;

        // 3. Logik untuk 100% (Update Status & Timestamp)
        if ($new_completed >= $total) {
            // Jika cukup 100%, kemaskini status dan time_complete
            $sql_update = "UPDATE enroll_course 
                           SET completed_modules = ?, current_progress = ?, status = 'complete', time_complete = CURRENT_TIMESTAMP 
                           WHERE studentID = ? AND courseID = ?";
        } else {
            // Jika belum 100%, kemaskini modul dan progress sahaja
            $sql_update = "UPDATE enroll_course 
                           SET completed_modules = ?, current_progress = ? 
                           WHERE studentID = ? AND courseID = ?";
        }

        $stmt_u = $conn->prepare($sql_update);
        
        // Pelarasan bind_param mengikut bilangan pembolehubah dalam query
        if ($new_completed >= $total) {
            $stmt_u->bind_param("idii", $new_completed, $new_progress, $studentID, $courseID);
        } else {
            $stmt_u->bind_param("idii", $new_completed, $new_progress, $studentID, $courseID);
        }
        
        if ($stmt_u->execute()) {
            $is_finished = ($new_completed >= $total);
            echo json_encode([
                'status' => 'success', 
                'progress' => $new_progress,
                'is_finished' => $is_finished
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
    } else {
        echo json_encode(['status' => 'info', 'message' => 'Course already completed!']);
    }
}
?>