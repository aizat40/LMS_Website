<?php
session_start();
// Pastikan user sudah login
if (!isset($_SESSION['username'])|| $_SESSION['role'] !== 'student') {
    header("Location: ../login.html");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning: Web Design Fundamentals | LMS UTHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* Rasio 16:9 */
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
        .lesson-item:hover, .lesson-item.active {
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
            <span class="text-secondary d-none d-md-block">Course: <strong>Web Design Fundamentals</strong></span>
        </div>
    </nav>

    <main>
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="video-container mb-4 shadow-lg">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Lesson Video" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold text-white mb-0">Module 1: Introduction to HTML5</h3>
                        <button class="btn btn-premium rounded-pill px-4">
                            <i class="bi bi-check2-circle me-2"></i>Mark as Complete
                        </button>
                    </div>
                    <hr class="border-secondary opacity-25">
                    <p class="text-secondary">In this lesson, we will cover the basic structure of an HTML5 document, including tags like <code>&lt;header&gt;</code>, <code>&lt;main&gt;</code>, and <code>&lt;footer&gt;</code>.</p>
                    
                    <div class="card dashboard-card p-4 mt-4 rounded-4">
                        <h5 class="text-white fw-bold mb-3"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Learning Materials</h5>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary border-opacity-10">
                            <span class="text-secondary small">Lecture_Notes_Week1.pdf</span>
                            <a href="#" class="btn btn-sm btn-outline-light rounded-pill px-3">Download</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <h4 class="fw-bold text-white mb-4">Course Content</h4>
                    <div class="lesson-list pe-2">
                        <div class="lesson-item active p-3 rounded-4 mb-2 d-flex align-items-center">
                            <div class="rounded-circle bg-premium p-2 me-3" style="background: var(--premium-accent); width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-play-fill text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-white small fw-bold">1. Introduction to HTML5</h6>
                                <small class="text-secondary" style="font-size: 11px;">15:20 mins</small>
                            </div>
                            <i class="bi bi-check-circle-fill text-success ms-2"></i>
                        </div>

                        <div class="lesson-item p-3 rounded-4 mb-2 d-flex align-items-center">
                            <div class="rounded-circle bg-secondary bg-opacity-25 p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-secondary small fw-bold">2. CSS Selectors & Colors</h6>
                                <small class="text-secondary" style="font-size: 11px;">22:45 mins</small>
                            </div>
                        </div>

                        <div class="lesson-item p-3 rounded-4 mb-2 d-flex align-items-center">
                            <div class="rounded-circle bg-secondary bg-opacity-25 p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-secondary small fw-bold">3. Responsive Layout with Grid</h6>
                                <small class="text-secondary" style="font-size: 11px;">18:10 mins</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>