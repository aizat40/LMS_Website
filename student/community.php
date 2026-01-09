<?php
include '../config.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.html");
    exit();
}

$username = $_SESSION['username'];
$id = $_SESSION['user_id'];

$sql = "SELECT a.*, b.username, c.title AS course_title FROM community_posts As a JOIN user1 as b ON a.studentID = b.id JOIN courses as c ON  a.courseID = c.id ORDER BY a.created_at DESC";
$result = $conn->query($sql);
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum || LMS UTHM</title>
    <link rel="icon" href="../img/logotab.png" type="image/png" sizes="32x32">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../css/styles.css">

</head>

<body>
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <header>
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
                        <a class="nav-link px-3" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="courses.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 active" href="community.php">Community</a>
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
    </header>
    <main>
        <section class="py-5 bg-dark bg-opacity-25 border-bottom border-secondary border-opacity-10">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="fw-bold text-white mb-2 animate__animated animate__fadeInLeft">Community <span
                                style="color: var(--premium-accent)">Forum</span></h1>
                        <p class="text-secondary mb-0 animate__animated animate__fadeInLeft"
                            style="animation-delay: 0.1s;">Ask questions, share knowledge, and connect with peers.</p>
                    </div>
                    <button class="btn btn-premium btn-lg rounded-pill animate__animated animate__zoomIn"
                        data-bs-toggle="modal" data-bs-target="#newThreadModal">
                        <i class="bi bi-plus-circle me-2"></i>Start New Thread
                    </button>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="card p-3 sticky-top" style="top: 120px; background-color: rgba(30, 41, 59, 0.5);">
                            <h6 class="text-white fw-bold mb-3">Categories</h6>
                            <ul class="nav flex-column forum-nav">
                                <li class="nav-item"><a href="#" class="nav-link active">All Topics</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Web Technology</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Data Science</a></li>
                                <li class="nav-item"><a href="#" class="nav-link text-danger"><i
                                            class="bi bi-shield-lock me-2"></i>Moderation </a></li>
                            </ul>
                        </div>
                    </div>


                    <div class="col-lg-9">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($post = $result->fetch_assoc()): ?>
                                <div class="card p-4 mb-4 forum-card animate__animated reveal">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Ahmad"
                                                class="rounded-circle me-3 border border-secondary" width="50" alt="User">
                                            <div>
                                                <h6 class="text-white fw-bold mb-0"><?php echo htmlspecialchars($post['username']); ?> <span
                                                        class="badge bg-primary ms-2 small"
                                                        style="font-size: 10px;">Student</span></h6>
                                                <small class="text-secondary">Posted in <span class="text-accent"><?php echo htmlspecialchars($post['course_title']); ?></span> • <?php echo htmlspecialchars($post['created_at']); ?></small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary border-0"><i class="bi bi-flag"></i>
                                            Report </button>
                                    </div>
                                    <h4 class="text-white fw-bold mb-2"><?php echo htmlspecialchars($post['title']); ?></h4>
                                    <p class="text-secondary"><?php echo htmlspecialchars($post['content']); ?></p>
                                    <div class="d-flex gap-4 mt-3 border-top border-secondary border-opacity-10 pt-3">
                                        <button class="btn-forum-action upvote-btn"><i class="bi bi-caret-up-fill"></i> <span
                                                class="count"><?php echo htmlspecialchars($post['likes_count']); ?></span> Upvotes </button>
                                        <button class="btn-forum-action"><i class="bi bi-chat-left-text"></i> 12 Replies
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-lg-9">
                                <div class="alert alert-info text-center">
                                    No posts found in the community forum. Be the first to start a discussion!
                                </div>
                            <?php endif; ?>
                            </div>
                    </div>
        </section>
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

    <div class="modal fade" id="newThreadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-secondary border-opacity-25 shadow-lg rounded-4">
                <div class="modal-header border-secondary border-opacity-10 p-4">
                    <h5 class="modal-title text-white fw-bold">Create New Discussion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="posting.php" method="post">
                        <div class="mb-3">
                            <label class="text-secondary small fw-medium mb-2">Topic Title</label>
                            <input type="text" class="form-control" placeholder="What is your question?" name="title" id="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary small fw-medium mb-2">Content</label>
                            <textarea class="form-control" rows="4" placeholder="Describe your thoughts..." name="content" id="content" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="text-secondary small fw-medium mb-2">Related Course</label>
                            <select class="form-select" name="course_id" id="course_id" required>
                                <option value="" disabled selected>Select a course</option>
                                <?php
                                // Gunakan connection yang sedia ada jika boleh, jangan close awal
                                include '../config.php';
                                $sql_courses = "SELECT id, title FROM courses";
                                $result_courses = $conn->query($sql_courses);
                                if ($result_courses && $result_courses->num_rows > 0) {
                                    while ($course = $result_courses->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($course['id']) . '">' . htmlspecialchars($course['title']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-premium w-100 py-3 rounded-pill fw-bold">
                            <i class="bi bi-send-fill me-2"></i>Post Thread
                        </button>
                    </form>
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

    <div class="modal fade" id="success" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-secondary border-opacity-25" style="background-color: #0f172a; color: #f8fafc;">
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold text-white mb-3">Post Successful!</h2>
                    <p class="text-secondary mb-4">Your post has been successfully submitted.</p>
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