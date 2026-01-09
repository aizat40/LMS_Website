<?php
include '../config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.html");
    exit();
}

$username = $_SESSION['username'];
$id = $_SESSION['user_id'];

// 1. Ambil semua kursus yang ada
$sql_all_courses = "SELECT * FROM courses ORDER BY created_at DESC";
$result_all = $conn->query($sql_all_courses);

// 2. Ambil senarai ID kursus yang SUDAH didaftar oleh pelajar ini
$enrolled_ids = [];
$sql_check = "SELECT courseID FROM enroll_course WHERE studentID = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$res_check = $stmt_check->get_result();
while ($row = $res_check->fetch_assoc()) {
    $enrolled_ids[] = $row['courseID'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logotab.png" type="image/png" sizes="32x32">
    <title>Courses | LMS UTHM</title>
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
                        <a class="nav-link px-3 " href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 active" href="courses.php">Courses</a>
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

    <section class="course-hero-bg d-flex align-items-center">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb" class="animate__animated animate__fadeInDown">
                        <ol class="breadcrumb justify-content-center justify-content-lg-start">
                            <li class="breadcrumb-item"><a href="index.html" class="text-decoration-none"
                                    style="color: white">Home</a></li>
                            <li class="breadcrumb-item" style="color: var(--premium-accent);" aria-current="page">
                                Course Catalog</li>
                        </ol>
                    </nav>

                    <h1 class="display-3 fw-bold text-white mb-3 animate__animated animate__fadeInLeft">
                        Course <span style="color: var(--premium-accent)">Catalog</span>
                    </h1>
                    <p class="lead text-white-50 mb-4 animate__animated animate__fadeInLeft"
                        style="animation-delay: 0.2s;">
                        Explore our world-class digital courses and start your learning journey with UTHM experts.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="pb-5 mt-5">
        <div class="container">
            <ul class="nav nav-pills justify-content-center mb-4 animate__animated animate__fadeIn">
                <li class="nav-item mx-1">
                    <button class="nav-link active filter-btn" data-filter="all"
                        style="color: white !important;">All Courses</button>
                </li>
                <li class="nav-item mx-1">
                    <button class="nav-link filter-btn" data-filter="web" style="color: white !important;">Web
                        Technology</button>
                </li>
                <li class="nav-item mx-1">
                    <button class="nav-link filter-btn" data-filter="business"
                        style="color: white !important;">Business</button>
                </li>
                <li class="nav-item mx-1">
                    <button class="nav-link filter-btn" data-filter="design"
                        style="color: white !important;">Design</button>
                </li>
            </ul>

            <div class="card p-4 border-secondary border-opacity-10 animate__animated animate__fadeIn"
                style="background-color: rgba(30, 41, 59, 0.3);">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <span
                                class="input-group-text bg-dark border-secondary border-opacity-25 text-secondary">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="courseSearch" class="form-control"
                                placeholder="Search by course name...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <select id="filterDifficulty"
                            class="form-select bg-dark border-secondary border-opacity-25 text-secondary">
                            <option value="all" selected>All Difficulty</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <select id="filterLecturer" class="form-select bg-dark border-secondary border-opacity-25 text-secondary">
    <option value="all" selected>All Lecturers</option>
    <?php
    // Ambil senarai pengajar yang unik dari database
    $sql_instructors = "SELECT DISTINCT instructor_name FROM courses";
    $res_inst = $conn->query($sql_instructors);
    while($inst = $res_inst->fetch_assoc()) {
        echo '<option value="'.htmlspecialchars($inst['instructor_name']).'">'.htmlspecialchars($inst['instructor_name']).'</option>';
    }
    ?>
</select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="py-5">
        <div class="container">

            <div class="row g-4 animate__animated animate__fadeInUp">
                <?php if ($result_all->num_rows > 0): ?>
                    <?php while ($course = $result_all->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 course-card-container"
                            data-title="<?php echo strtolower(htmlspecialchars($course['title'])); ?>"
                            data-category="<?php echo strtolower(htmlspecialchars($course['category'])); ?>"
                            data-difficulty="<?php echo htmlspecialchars($course['level']); ?>"
                            data-instructor="<?php echo htmlspecialchars($course['instructor_name']); ?>">
                            

                            <div class="card h-100 dashboard-card rounded-4 overflow-hidden border-0">
                                <div class="position-relative">
                                    <img src="../img/<?php echo $course['image_url']; ?>" class="card-img-top" alt="Course" style="height: 200px; object-fit: cover;">
                                    <span class="badge position-absolute top-0 end-0 m-3 bg-<?php echo $course['level']; ?> badge-level">
                                        <?php echo strtoupper($course['level']); ?>
                                    </span>
                                </div>

                                <div class="card-body p-4">
                                    <small class="text-accent fw-bold text-uppercase" style="font-size: 10px;"><?php echo $course['category']; ?></small>
                                    <h5 class="card-title text-white fw-bold mt-1 mb-2"><?php echo htmlspecialchars($course['title']); ?></h5>
                                    <p class="text-secondary small mb-4"><?php echo substr(htmlspecialchars($course['description']), 0, 80) . '...'; ?></p>

                                    <div class="d-flex align-items-center mb-4">
                                        <div class="rounded-circle bg-secondary bg-opacity-25 me-2 overflow-hidden" style="width: 35px; height: 35px; border: 1px solid rgba(255,255,255,0.1);">
                                            <img src="../img/<?php echo htmlspecialchars($course['img_lecturer']); ?>"
                                                alt="Lecturer"
                                                class="w-100 h-100"
                                                style="object-fit: cover; display: block;">
                                        </div>
                                        <small class="text-secondary">Inst: <span class="text-white"><?php echo htmlspecialchars($course['instructor_name']); ?></span></small>
                                    </div>

                                    <?php if (in_array($course['id'], $enrolled_ids)): ?>
                                        <a href="course_view.php?course=<?php echo $course['id']; ?>" class="btn btn-outline-light w-100 rounded-pill py-2">
                                            <i class="bi bi-play-circle me-2"></i>Continue Learning
                                        </a>
                                    <?php else: ?>
                                        <a href="course_details.php?course_id=<?php echo $course['id']; ?>" class="btn btn-premium w-100 rounded-pill py-2">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                     <div id="noResults" class="text-center py-5 d-none">
                        <i class="bi bi-exclamation-circle fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3">No courses found matching your search.</p>
                    </div>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-secondary">No courses available at the moment.</p>
                    </div>
                <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('courseSearch');
    const difficultySelect = document.getElementById('filterDifficulty');
    const lecturerSelect = document.getElementById('filterLecturer');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const courseCards = document.querySelectorAll('.course-card-container');
    const noResultsMsg = document.getElementById('noResults');

    let activeCategory = 'all';

    function filterCourses() {
        const searchText = searchInput.value.toLowerCase();
        const selectedDifficulty = difficultySelect.value;
        const selectedLecturer = lecturerSelect.value;

        courseCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const category = card.getAttribute('data-category');
            const difficulty = card.getAttribute('data-difficulty');
            const instructor = card.getAttribute('data-instructor');

            // Logik padanan (Match logic)
            const matchesSearch = title.includes(searchText);
            const matchesCategory = (activeCategory === 'all' || category.includes(activeCategory.toLowerCase()));
            const matchesDifficulty = (selectedDifficulty === 'all' || difficulty === selectedDifficulty);
            const matchesLecturer = (selectedLecturer === 'all' || instructor === selectedLecturer);

            // Tunjukkan jika semua kriteria dipenuhi
            if (matchesSearch && matchesCategory && matchesDifficulty && matchesLecturer) {
                card.style.display = 'block';
                card.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                card.style.display = 'none';
            }
        });
        noResultsMsg.classList.toggle('d-none', visibleCount > 0);
    }

    // Event Listeners untuk Input dan Select
    searchInput.addEventListener('input', filterCourses);
    difficultySelect.addEventListener('change', filterCourses);
    lecturerSelect.addEventListener('change', filterCourses);

    // Event Listeners untuk Nav Pills (Category)
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // Tukar class active
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            activeCategory = this.getAttribute('data-filter');
            filterCourses();
        });
    });
});
</script>
</body>

</html>