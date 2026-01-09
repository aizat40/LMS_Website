<?php
include '../config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.html");
    exit();
}
$username = $_SESSION['username'];

$id = $_SESSION['user_id'];

$sql = "SELECT * FROM enroll_course WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$sql2 = "SELECT COUNT(*) AS activeCourse FROM enroll_course WHERE studentID=? and status='active'";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$user_row = $result2->fetch_assoc();
$activeCourse = $user_row['activeCourse'];

$sql3 = "SELECT COUNT(*) AS completeModule FROM enroll_course WHERE studentID=? and status='complete'";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $id);
$stmt3->execute();
$result3 = $stmt3->get_result();
$completeModule_row = $result3->fetch_assoc();
$completeModule = $completeModule_row['completeModule'];

// 1. Kira Purata Progress (Overall Progress)
$sql4 = "SELECT AVG(current_progress) AS overallProgress FROM enroll_course WHERE studentID = ?";
$stmt4 = $conn->prepare($sql4);
$stmt4->bind_param("i", $id);
$stmt4->execute();
$result4 = $stmt4->get_result();
$progress_row = $result4->fetch_assoc();

// Jika pelajar belum daftar apa-apa kursus, letakkan 0 supaya tidak keluar ralat
$overallProgress = $progress_row['overallProgress'] ? round($progress_row['overallProgress']) : 0;

$sql5 = "SELECT * FROM enroll_course JOIN courses ON enroll_course.courseID = courses.id WHERE studentID=? and status='active'";
$stmt5 = $conn->prepare($sql5);
$stmt5->bind_param("i", $id);
$stmt5->execute();
$result5 = $stmt5->get_result();

$sql6 = "SELECT a.title AS projectname, a.due_date AS duedate, c.title AS coursetitle FROM assignments AS a JOIN courses AS c ON a.courseID = c.id JOIN enroll_course e ON e.courseID = a.courseID WHERE e.status='active' AND e.studentID=? AND a.due_date >= NOW() ORDER BY a.due_date ASC";
$stmt6 = $conn->prepare($sql6);
$stmt6->bind_param("i", $id);
$stmt6->execute();
$result6 = $stmt6->get_result();

$sql_completed = "SELECT * FROM enroll_course JOIN courses ON enroll_course.courseID = courses.id WHERE studentID=? and status='complete'";
$stmt_completed = $conn->prepare($sql_completed);
$stmt_completed->bind_param("i", $id);
$stmt_completed->execute();
$result_completed = $stmt_completed->get_result();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logotab.png" type="image/png" sizes="32x32">
    <title>Dashboard | LMS UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
                <a class="navbar-brand fs-3 fw-semibold text-light" href="index.php">
                <img src="../img/uthmLogo.png" alt="Logo" class="me-2" style="height: 30px; filter: drop-shadow(0px 0px 2px rgba(255,255,255,0.2));">
                Online Learning UTHM
            </a>

            <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-lg-4">
                    <li class="nav-item">
                        <a class="nav-link px-3 active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="courses.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="community.php">Community</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3 border-start border-secondary border-opacity-25 ps-lg-4">
                    <div class="text-end d-none d-xl-block">
                        <small class="text-secondary d-block" style="font-size: 10px; line-height: 1;">User</small>
                        <span class="text-white text-uppercase small fw-semibold"><?php echo htmlspecialchars($username); ?></span>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#logout">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="container py-5">
            <div class="row mb-5 animate__animated animate__fadeIn">
                <div class="col-12">
                    <h1 class="display-5 fw-bold text-white">Student <span style="color: var(--premium-accent)">Dashboard</span></h1>
                    <p class="text-secondary">Keep track of your learning progress and upcoming assignments.</p>
                </div>
            </div>

            <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
                <div class="col-md-4">
                    <div class="card dashboard-card p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 70px; height: 70px; flex-shrink: 0;">
                                <i class="bi bi-journal-text text-primary fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-white mb-0"><?php echo htmlspecialchars($activeCourse); ?></h3>
                                <small class="text-secondary">Active Courses</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 70px; height: 70px; flex-shrink: 0;">
                                <i class="bi bi-award text-success fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-white mb-0"><?php echo htmlspecialchars($completeModule); ?></h3>
                                <small class="text-secondary">Completed Modules</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 70px; height: 70px; flex-shrink: 0;">
                                <i class="bi bi-clock-history text-warning fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-white mb-0"><?php echo htmlspecialchars($overallProgress); ?>%</h3>
                                <small class="text-secondary">Overall Progress</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="col-lg-8">
                    <h4 class="fw-bold text-white mb-4">My Current Courses</h4>
                    <?php if ($result5->num_rows > 0): ?>
                        <?php while ($course = $result5->fetch_assoc()): ?>
                            <div class="card dashboard-card p-4 mb-3 rounded-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="fw-bold text-white"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="text-secondary small mb-3">Instructor: <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?php echo $course['current_progress']; ?>%"
                                                aria-valuenow="<?php echo $course['current_progress']; ?>"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-secondary"><?php echo $course['current_progress']; ?>% Complete</small>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <a href="course_view.php?course=<?php echo $course['courseID']; ?>" class="btn btn-premium rounded-pill px-4">Continue</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="p-5 card dashboard-card text-center rounded-4 border border-secondary border-opacity-10">
                            <i class="bi bi-journal-x fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                            <p class="text-secondary">You are not enrolled in any active courses yet.</p>
                            <a href="courses.php" class="btn btn-premium btn-sm rounded-pill">Explore Courses</a>
                        </div>
                    <?php endif; ?>



                    <h4 class="fw-bold text-white mb-4 mt-5">Completed Courses</h4>
                    <?php if ($result_completed->num_rows > 0): ?>
                        <?php while ($completed_course = $result_completed->fetch_assoc()): ?>
                            <div class="card dashboard-card p-4 mb-3 rounded-4 border-opacity-25">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <h5 class="fw-bold text-white mb-0 me-2"><?php echo htmlspecialchars($completed_course['title']); ?></h5>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill small">
                                                <i class="bi bi-patch-check-fill me-1"></i>Completed
                                            </span>
                                        </div>
                                        <p class="text-secondary small mb-3">Instructor: <?php echo htmlspecialchars($completed_course['instructor_name']); ?></p>

                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: var(--premium-accent);"></div>
                                        </div>
                                        <small class="text-secondary fw-semibold">Course Finished on <?php echo date('d M Y', strtotime($completed_course['time_complete'])); ?></small>
                                    </div>

                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <a href="generate_cert.php?course_id=<?php echo $completed_course['courseID']; ?>" target="_blank" class="btn btn-enroll     rounded-pill px-4">
                                            <i class="bi bi-download me-2"></i>Download Cert
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="p-4 card dashboard-card text-center rounded-4 border border-secondary border-opacity-10 bg-opacity-10">
                            <p class="text-secondary small mb-0">You haven't completed any courses yet. Finish your active modules to earn certificates!</p>
                        </div>
                    <?php endif; ?>
                </div>



                <div class="col-lg-4 mt-5 mt-lg-0">
                    <h4 class="fw-bold text-white mb-4">Deadlines</h4>
                    <div class="card dashboard-card p-4 rounded-4">
                        <?php if ($result6->num_rows > 0): ?>
                            <?php while ($assignment = $result6->fetch_assoc()): ?>
                                <div class="d-flex mb-4">
                                    <div class="text-center bg-danger bg-opacity-10 rounded p-2 me-3" style="min-width: 60px;">
                                        <span class="d-block fw-bold text-danger"><?php echo date('d', strtotime($assignment['duedate'])); ?></span>
                                        <small class="text-danger uppercase"><?php echo strtoupper(date('M', strtotime($assignment['duedate']))); ?></small>
                                    </div>
                                    <div>
                                        <h6 class="text-light fw-bold mb-0"><?php echo htmlspecialchars($assignment['projectname']); ?></h6>
                                        <small class="text-secondary"><?php echo htmlspecialchars($assignment['coursetitle']); ?></small>
                                        <p class="text-secondary small mb-0">Due: <?php echo date('F j, Y, g:i A', strtotime($assignment['duedate'])); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-check fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                <p class="text-secondary">No upcoming deadlines. Great job!</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>

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

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-secondary border-opacity-25" style="background-color: #0f172a; color: #f8fafc;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-3">Login Successful!</h2>
                    <p class="text-secondary mb-4">Welcome back to your dashboard. Ready to learn?</p>
                    <button type="button" class="btn btn-premium w-100 rounded-pill py-3" data-bs-dismiss="modal">
                        Okay, Let's Go
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-secondary border-opacity-25" style="background-color: #0f172a; color: #f8fafc;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-box-arrow-right text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-3">Logout Confirmation</h2>
                    <p class="text-secondary mb-4">Are you sure you want to end your session?</p>

                    <div class="d-grid gap-2">
                        <form action="../logout.php" method="post" class="m-0">
                            <button type="submit" class="btn btn-danger w-100 rounded-pill py-3">
                                Yes, Logout
                            </button>
                        </form>
                        <button type="button" class="btn btn-outline-secondary w-100 rounded-pill py-3" data-bs-dismiss="modal">
                            No, Stay Logged In
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successpayment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-secondary border-opacity-25" style="background-color: #0f172a; color: #f8fafc;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-3">payment Successful!</h2>
                    <p class="text-secondary mb-4">Your payment has been processed successfully.</p>
                    <button type="button" class="btn btn-premium w-100 rounded-pill py-3" data-bs-dismiss="modal">
                        Okay
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>