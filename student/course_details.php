<?php
include '../config.php';

$courseID = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseID);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) { header("Location: courses.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $course['title']; ?> | Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top py-3 animate__animated animate__fadeInDown">
        <div class="container">
            <a class="navbar-brand fs-4 fw-bold text-white" href="courses.php">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
            <span class="text-secondary d-none d-md-block">Course: <strong><?php echo htmlspecialchars($course['title']); ?></strong></span>
        </div>
    </nav>

    <main class="py-5 mt-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 animate__animated animate__fadeInLeft">
                    <img src="../img/<?php echo $course['image_url']; ?>" 
                         class="img-fluid rounded-4 mb-4 shadow-lg w-100 animate__animated animate__zoomIn" 
                         style="max-height: 400px; object-fit: cover; animation-delay: 0.2s;">
                    
                    <h1 class="text-white fw-bold mb-3 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                        <?php echo $course['title']; ?>
                    </h1>

                    <div class="badge bg-<?php echo $course['level']; ?> mb-3 text-uppercase animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                        <?php echo $course['level']; ?>
                    </div>

                    <p class="text-secondary fs-5 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                        <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                    </p>
                </div>

                <div class="col-lg-4 animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
                    <div class="card dashboard-card p-4 rounded-4 sticky-top shadow-accent" style="top: 100px;">
                        <h4 class="text-white fw-bold mb-4">Course Subscription</h4>
                        <div class="display-5 fw-bold text-white mb-2">RM <?php echo number_format($course['price'], 2); ?></div>
                        <p class="text-secondary small mb-4">One-time payment for lifetime access to all modules and certificates.</p>
                        
                        <ul class="list-unstyled text-secondary mb-4">
                            <li class="mb-2 animate__animated animate__fadeInLeft" style="animation-delay: 0.7s;">
                                <i class="bi bi-check-circle text-success me-2"></i> Full lifetime access
                            </li>
                            <li class="mb-2 animate__animated animate__fadeInLeft" style="animation-delay: 0.8s;">
                                <i class="bi bi-check-circle text-success me-2"></i> <?php echo $course['total_modules']; ?> Video Modules
                            </li>
                            <li class="mb-2 animate__animated animate__fadeInLeft" style="animation-delay: 0.9s;">
                                <i class="bi bi-check-circle text-success me-2"></i> Certificate of Completion
                            </li>
                        </ul>

                        <a href="stripe_checkout.php?course_id=<?php echo $courseID; ?>" 
                           class="btn btn-premium w-100 rounded-pill py-3 fw-bold animate__animated animate__pulse animate__infinite animate__slow"
                           style="animation-delay: 1.2s;">
                            Subscribe Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>