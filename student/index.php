<?php
session_start();
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LMS UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        .dashboard-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            border-color: var(--premium-accent);
        }

        .progress {
            height: 8px;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .progress-bar {
            background-color: var(--premium-accent);
        }
    </style>
</head>

<body>
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fs-3 fw-bold text-white" href="index.php">
                <i class="bi bi-mortarboard-fill me-2" style="color: var(--premium-accent)"></i>LMS UTHM
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
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-journal-text text-primary fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-white mb-0">3</h3>
                                <small class="text-secondary">Active Courses</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-award text-success fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-white mb-0">12</h3>
                                <small class="text-secondary">Completed Modules</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-4 rounded-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-white mb-0">85%</h3>
                                <small class="text-secondary">Overall Progress</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="col-lg-8">
                    <h4 class="fw-bold text-white mb-4">My Current Courses</h4>

                    <div class="card dashboard-card p-4 mb-3 rounded-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold text-white">Web Design Fundamentals</h5>
                                <p class="text-secondary small mb-3">Instructor: Dr. Ali</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar" style="width: 75%"></div>
                                </div>
                                <small class="text-secondary">75% Complete</small>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="#" class="btn btn-premium rounded-pill px-4">Continue</a>
                            </div>
                        </div>
                    </div>

                    <div class="card dashboard-card p-4 mb-3 rounded-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold text-white">JavaScript Mastery</h5>
                                <p class="text-secondary small mb-3">Instructor: Pn. Siti</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar" style="width: 40%"></div>
                                </div>
                                <small class="text-secondary">40% Complete</small>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="#" class="btn btn-premium rounded-pill px-4">Continue</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    <h4 class="fw-bold text-white mb-4">Deadlines</h4>
                    <div class="card dashboard-card p-4 rounded-4">
                        <div class="d-flex mb-4">
                            <div class="text-center bg-danger bg-opacity-10 rounded p-2 me-3" style="min-width: 60px;">
                                <span class="d-block fw-bold text-danger">08</span>
                                <small class="text-danger uppercase">JAN</small>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Project Submission</h6>
                                <small class="text-secondary">Web Tech (BIW10103)</small>
                            </div>
                        </div>
                        <div class="d-flex mb-0">
                            <div class="text-center bg-warning bg-opacity-10 rounded p-2 me-3" style="min-width: 60px;">
                                <span class="d-block fw-bold text-warning">12</span>
                                <small class="text-warning uppercase">JAN</small>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Quiz 2: JS Logic</h6>
                                <small class="text-secondary">Fundamentals Course</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>