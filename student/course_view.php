<?php
include '../config.php';
// Pastikan user sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.html");
    exit();
}
$username = $_SESSION['username'];
$id = $_SESSION['user_id'];
$courseID = isset($_GET['course']) ? $_GET['course'] : 0;
$moduleNum = isset($_GET['module']) ? (int)$_GET['module'] : 1;
$sql = "SELECT * FROM material_course WHERE courseID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseID);
$stmt->execute();
$result = $stmt->get_result();

$sql2 = "SELECT * FROM course_videos WHERE courseID = ? AND module_number = ? ORDER BY module_number ASC LIMIT 1";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("ii", $courseID, $moduleNum);
$stmt2->execute();
$result2 = $stmt2->get_result()->fetch_assoc();

$sql3 = "SELECT * FROM courses WHERE id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $courseID);
$stmt3->execute();
$result3 = $stmt3->get_result()->fetch_assoc();


$sql_p = "SELECT completed_modules FROM enroll_course WHERE studentID = ? AND courseID = ?";
$stmt_p = $conn->prepare($sql_p);
$stmt_p->bind_param("ii", $id, $courseID);
$stmt_p->execute();
$progress_data = $stmt_p->get_result()->fetch_assoc();
$completed = $progress_data['completed_modules'];

// Ambil semua video untuk kursus ini
$sql_all = "SELECT * FROM course_videos WHERE courseID = ? ORDER BY module_number ASC";
$stmt_all = $conn->prepare($sql_all);
$stmt_all->bind_param("i", $courseID);
$stmt_all->execute();
$all_videos = $stmt_all->get_result();

$stmt->close();
$stmt2->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logotab.png" type="image/png" sizes="32x32">
    <title>Course Details | LMS UTHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            /* Rasio 16:9 */
            height: 0;
            overflow: hidden;
            border-radius: 1rem;
            background: #000;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .lesson-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .lesson-item {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .lesson-item:hover,
        .lesson-item.active {
            background: rgba(139, 92, 246, 0.1);
            border-color: var(--premium-accent);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fs-4 fw-bold text-white" href="index.php">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
            <span class="text-secondary d-none d-md-block">Course: <strong><?php echo htmlspecialchars($result3['title']); ?></strong></span>
        </div>
    </nav>

    <main>
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="video-container mb-4 shadow-lg">
                        <<?php if ($result2): ?>
                            <iframe src="<?php echo htmlspecialchars($result2['video_url']); ?>" ...></iframe>
                        <?php else: ?>
                            <div class="alert alert-warning">Video for this module is not available yet.</div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold text-white mb-0">Module <?php echo htmlspecialchars($result2['module_number']); ?>: <?php echo htmlspecialchars($result2['module_title']); ?></h3>
                        <button id="markCompleteBtn" class="btn btn-premium rounded-pill px-4">
                            <i class="bi bi-check2-circle me-2"></i>Mark as Complete
                        </button>
                    </div>
                    <hr class="border-secondary opacity-25">
                    <p class="text-secondary"><?php echo htmlspecialchars($result2['video_description']); ?></p>


                    <div class="card dashboard-card p-4 mt-4 rounded-4">
                        <h5 class="text-white fw-bold mb-3"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Learning Materials</h5>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($material = $result->fetch_assoc()): ?>
                                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary border-opacity-9 mb-2">
                                    <i class="bi <?php echo ($material['file_type'] == 'pdf') ? 'bi-file-pdf text-danger' : 'bi-file-earmark-play text-primary'; ?> me-2"></i>
                                    <span class="text-secondary small"><?php echo htmlspecialchars($material['material_title']); ?></span>
                                    <a href="../<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank" class="btn btn-sm btn-enroll rounded-pill px-3">Download</a>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-secondary small">No learning materials available for this course.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h4 class="fw-bold text-white mb-4">Course Content</h4>
                    <div class="lesson-list pe-2">
                        <?php while ($v = $all_videos->fetch_assoc()):
                            // Logik Unlock: Modul 1 sentiasa buka. Modul lain buka jika modul sebelumnya siap.
                            $is_unlocked = ($v['module_number'] <= ($completed + 1));
                        ?>
                            <div class="lesson-item <?php echo $is_unlocked ? 'active' : 'opacity-50'; ?> p-3 rounded-4 mb-2 d-flex align-items-center"
                                <?php echo $is_unlocked ? "onclick=\"window.location.href='course_view.php?course=$courseID&module=" . $v['module_number'] . "'\"" : ""; ?>>

                                <div class="rounded-circle <?php echo $is_unlocked ? 'bg-premium' : 'bg-secondary bg-opacity-25'; ?> p-2 me-3"
                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi <?php echo $is_unlocked ? 'bi-play-fill text-white' : 'bi-lock-fill text-secondary'; ?>"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <h6 class="mb-0 <?php echo $is_unlocked ? 'text-white' : 'text-secondary'; ?> small fw-bold">
                                        <?php echo $v['module_number'] . ". " . htmlspecialchars($v['module_title']); ?>
                                    </h6>
                                    <small class="text-secondary" style="font-size: 11px;"><?php echo $v['duration']; ?></small>
                                </div>

                                <?php if ($v['module_number'] <= $completed): ?>
                                    <i class="bi bi-check-circle-fill text-success ms-2"></i>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer-premium py-5 mt-5">
        <div class="container">
            <hr class="border-secondary opacity-10 mb-5">

            <div class="row align-items-center">
                <div class="col-md-7 text-center text-md-start mb-4 mb-md-0">
                    <h5
                        class="fw-bold text-white mb-2 d-flex align-items-center justify-content-center justify-content-md-start">
                        <img src="../img/uthmLogo.png" alt="Logo UTHM" class="me-3"
                            style="height: 25px; width: auto; object-fit: contain; filter: drop-shadow(0px 0px 2px rgba(255,255,255,0.2))">
                        <span style="letter-spacing: 0.5px;">Online Learning UTHM</span>
                    </h5>
                    <p class="text-secondary small mb-3">Fundamentals of Web Technology (BIW10103)</p>

                    <div class="developer-section">
                        <p class="text-secondary mb-2"
                            style="font-size: 10px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; opacity: 0.7;">
                            Developed By:
                        </p>
                        <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                            <span class="dev-chip"><i class="bi bi-person-fill me-1"></i>Nuraizat</span>
                            <span class="dev-chip"><i class="bi bi-person-fill me-1"></i>Ilham Hazim</span>
                            <span class="dev-chip"><i class="bi bi-person-fill me-1"></i>Syahmi</span>
                            <span class="dev-chip"><i class="bi bi-person-fill me-1"></i>Ahmad Ismail</span>
                            <span class="dev-chip"><i class="bi bi-person-fill me-1"></i>Darwish Naufal</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3 mb-3">
                        <a href="#" class="footer-link text-secondary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="footer-link text-secondary"><i class="bi bi-x"></i></a>
                        <a href="#" class="footer-link text-secondary"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="footer-link text-secondary"><i class="bi bi-linkedin"></i></a>
                    </div>
                    <div class="small text-secondary opacity-50">
                        © 2025 Semester I 2025/2026 • Universiti Tun Hussein Onn Malaysia
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </main>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        document.getElementById('markCompleteBtn').addEventListener('click', function() {
            const courseID = <?php echo $courseID; ?>;

            fetch('update_progress.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'courseID=' + courseID
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Progress Updated! Your current progress is ' + Math.round(data.progress) + '%');
                        location.reload(); // Refresh untuk unlock video seterusnya di sidebar
                    } else if (data.status === 'info') {
                        alert(data.message);
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Di dalam fetch() .then(data => { ... })
        if (data.is_finished) {
            alert('Congratulations! You have successfully completed the course.');
            // Contoh: Bawa pelajar ke halaman sijil atau dashboard
            window.location.href = 'index.php?status=finished';
        }
    </script>
</body>

</html>