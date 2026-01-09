<?php
session_start();

// Get current page from query parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Set page title based on current page
switch($page) {
    case 'about':
        $page_title = "About Us";
        break;
    case 'services':
        $page_title = "Our Services";
        break;
    case 'training':
        $page_title = "Training";
        break;
    case 'staffing':
        $page_title = "Staffing";
        break;
    default:
        $page_title = "Home";
        $page = 'home';
}

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$user_type = $_SESSION['user_type'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspironet Solutions - <?php echo $page_title; ?></title>
    <link rel="icon" type="image" href="./ASSETS/fav.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
    /* Custom Styles */
    :root {
        --primary: #2c3e50;
        --secondary: #3498db;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --light: #f8f9fa;
        --dark: #2c3e50;
        --purple: #6f42c1;
    }

    body {
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-top: 76px;
        color: #333;
    }

    /* Navigation */
    .navbar {
        box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        background: white !important;
        padding: 15px 0;
        transition: all 0.3s;
    }

    .navbar-brand {
        font-weight: 700;
        color: var(--primary) !important;
        font-size: 1.5rem;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        padding: 8px 15px;
        margin: 0 5px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .navbar-nav .nav-link:hover {
        background: rgba(52, 152, 219, 0.1);
    }

    .navbar-nav .nav-link.active {
        background: var(--secondary);
        color: white !important;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 100px 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M500,97C126.7,96.3,0.8,19.8,0,0v100l1000,0V1C1000,59.4,805.9,97.8,500,97z" fill="white"/></svg>');
        background-position: bottom;
        background-repeat: no-repeat;
        background-size: 100% 50px;
        opacity: 0.1;
    }

    /* Cards */
    .card {
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, .15);
    }

    /* Service Cards */
    .service-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s;
        border: none;
    }

    .btn-primary {
        background: var(--secondary);
        border-color: var(--secondary);
    }

    .btn-primary:hover {
        background: #2980b9;
        border-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 1.1rem;
    }

    /* Section Titles */
    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
        display: inline-block;
        font-weight: 600;
    }

    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 60px;
        height: 4px;
        background: var(--secondary);
        border-radius: 2px;
    }

    .text-center .section-title:after {
        left: 50%;
        transform: translateX(-50%);
    }

    /* Testimonial Cards */
    .testimonial-card {
        border-left: 4px solid var(--secondary);
        background: white;
        border-radius: 10px;
    }

    .avatar-circle {
        width: 50px;
        height: 50px;
        background: var(--secondary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    /* Course Cards */
    .course-card {
        height: 100%;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
    }

    .course-card:hover {
        border-color: var(--secondary);
    }

    .course-card .card-footer {
        background: transparent;
        border-top: 1px solid #e9ecef;
        padding: 15px;
    }

    /* Icon Wrapper */
    .icon-wrapper {
        background: rgba(52, 152, 219, 0.1);
        border-radius: 12px;
        padding: 20px;
        display: inline-block;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .card:hover .icon-wrapper {
        transform: scale(1.1);
    }

    /* Purple color classes */
    .bg-purple {
        background-color: var(--purple) !important;
    }

    .text-purple {
        color: var(--purple) !important;
    }

    /* Footer */
    footer {
        background: var(--dark);
        color: white;
        padding: 60px 0 20px;
    }

    footer a {
        color: #ddd;
        text-decoration: none;
        transition: color 0.3s;
    }

    footer a:hover {
        color: white;
        text-decoration: underline;
    }

    .forget {
        color: red;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        margin-top: 40px;
    }

    /* Add this to your existing CSS */
    .container-fluid {
        width: 100%;
        padding-right: var(--bs-gutter-x, 0.75rem);
        padding-left: var(--bs-gutter-x, 0.75rem);
        margin-right: auto;
        margin-left: auto;
    }

    /* Make footer full width */
    footer {
        background: var(--dark);
        color: white;
        padding: 60px 0 20px;
        margin: 0;
        width: 100%;
    }

    /* Remove any max-width constraints on footer */
    footer .container-fluid {
        max-width: 100% !important;
    }

    /* Animation */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Utility Classes */
    .rounded-lg {
        border-radius: 15px;
    }

    .shadow-sm {
        box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
    }

    .shadow-lg {
        box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
    }

    /* Stats Counter */
    .stat-number {
        font-size: 3.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--secondary), var(--purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Learning Methods Section */
    .learning-method-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: none;
        padding: 25px 30px 15px;
    }

    .modal-body {
        padding: 15px 30px 30px;
    }

    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 8px 8px 0 0;
        padding: 12px 25px;
        font-weight: 500;
        color: #666;
    }

    .nav-tabs .nav-link.active {
        background: var(--secondary);
        color: white;
        border: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            padding: 80px 0 !important;
        }

        .display-4 {
            font-size: 2.2rem;
        }

        .btn-lg {
            padding: 10px 20px;
            font-size: 1rem;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .stat-number {
            font-size: 2.8rem;
        }

        .navbar-nav {
            padding: 10px 0;
        }

        .navbar-nav .nav-link {
            margin: 5px 0;
        }

        .img-fluid {
            height: auto;
            max-height: 300px;
        }
    }

    @media (max-width: 576px) {
        .hero-section {
            padding: 60px 0 !important;
        }

        .display-4 {
            font-size: 1.8rem;
        }

        .stat-number {
            font-size: 2.2rem;
        }
    }

    .navbar-brand img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
    }

    .img-fluid {
        height: auto;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }

    /* User dropdown */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 10px;
        min-width: 200px;
    }

    .dropdown-item {
        padding: 8px 15px;
        border-radius: 6px;
        transition: all 0.3s;
    }

    .dropdown-item:hover {
        background: rgba(52, 152, 219, 0.1);
    }

    /* Alert */
    .alert {
        border: none;
        border-radius: 10px;
        padding: 15px 20px;
    }

    /* Form controls */
    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #ddd;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Progress bars */
    .progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
    }

    .progress-bar {
        border-radius: 4px;
    }

    /* Tooltips */
    .tooltip-inner {
        border-radius: 6px;
        padding: 6px 12px;
    }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="?page=home">
                <img src="./ASSETS/fav.png" alt="favicon"> Aspironet Solutions
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'home' ? 'active' : ''; ?>" href="?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'about' ? 'active' : ''; ?>" href="?page=about">About
                            Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'services' ? 'active' : ''; ?>" href="?page=services">Our
                            Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'training' ? 'active' : ''; ?>"
                            href="?page=training">Training</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'staffing' ? 'active' : ''; ?>"
                            href="?page=staffing">Staffing</a>
                    </li>

                    <!-- User Authentication Buttons -->
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><span
                                    class="dropdown-item-text small text-muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="viewProfile()"><i class="bi bi-person"></i>
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="#" onclick="viewMyCourses()"><i class="bi bi-book"></i>
                                    My Courses</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="logout()"><i
                                        class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="bi bi-person"></i> Sign In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Get
                            Started</a>
                    </li>
                    <?php endif; ?>

                    <!-- Admin Link - ONLY show if admin is logged in AND user is not logged in -->
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && !(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="admin_dashboard.php">
                            <i class="bi bi-shield-lock"></i> Admin
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($page == 'home'): ?>
    <!-- Home Page Content -->
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Empowering Learning & Workforce Excellence</h1>
                    <p class="lead mb-4">Aspironet Solutions bridges the gap between talent and opportunity through
                        cutting-edge technology training, corporate development programs, and strategic staffing
                        solutions.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="?page=training" class="btn btn-light btn-lg px-4"><i class="bi bi-book"></i> Explore
                            Courses</a>
                        <a href="?page=staffing" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-people"></i>
                            Partner With Us</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="./ASSETS/fav.png" alt="Training Session" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Highlight -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-laptop text-primary fs-1"></i>
                            </div>
                            <h3 class="h4 mb-3">Technology & Corporate Training</h3>
                            <p class="text-muted">Comprehensive training programs in emerging technologies and
                                professional skill development.</p>
                            <a href="?page=training" class="btn btn-link text-decoration-none">Learn More →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-briefcase text-success fs-1"></i>
                            </div>
                            <h3 class="h4 mb-3">Staffing & Talent Solutions</h3>
                            <p class="text-muted">Strategic workforce solutions connecting organizations with qualified
                                professionals.</p>
                            <a href="?page=staffing" class="btn btn-link text-decoration-none">Explore Solutions →</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-building text-warning fs-1"></i>
                            </div>
                            <h3 class="h4 mb-3">Corporate Clients Served</h3>
                            <p class="text-muted">Trusted by leading organizations for training and staffing needs
                                across industries.</p>
                            <a href="?page=about" class="btn btn-link text-decoration-none">View Clients →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Counter -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">500+</div>
                    <p class="text-muted">Professionals Trained</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">50+</div>
                    <p class="text-muted">Corporate Clients</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">25+</div>
                    <p class="text-muted">Expert Trainers</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">98%</div>
                    <p class="text-muted">Placement Rate</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Methods Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Learning Methods</h2>
                    <p class="text-muted">Innovative approaches for lifelong learning retention</p>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <!-- Visual Representation of Learning Methods -->
                    <div class="position-relative text-center">
                        <div class="position-relative" style="height: 400px;">
                            <!-- Central Circle -->
                            <div class="position-absolute top-50 start-50 translate-middle bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 180px; height: 180px;">
                                <div class="text-center">
                                    <i class="bi bi-lightbulb-fill text-primary fs-1"></i>
                                    <h5 class="mt-2">Innovative Learning</h5>
                                </div>
                            </div>

                            <!-- ILT Icon -->
                            <div class="position-absolute" style="top: 20%; left: 10%;">
                                <div class="bg-white border border-3 border-primary rounded-circle p-3 shadow-sm">
                                    <i class="bi bi-person-standing text-primary fs-3"></i>
                                </div>
                                <div class="mt-2 small fw-bold">ILT</div>
                            </div>

                            <!-- VILT Icon -->
                            <div class="position-absolute" style="top: 20%; right: 10%;">
                                <div class="bg-white border border-3 border-success rounded-circle p-3 shadow-sm">
                                    <i class="bi bi-laptop text-success fs-3"></i>
                                </div>
                                <div class="mt-2 small fw-bold">VILT</div>
                            </div>

                            <!-- Blended Icon -->
                            <div class="position-absolute" style="bottom: 20%; left: 10%;">
                                <div class="bg-white border border-3 border-warning rounded-circle p-3 shadow-sm">
                                    <i class="bi bi-arrows-angle-contract text-warning fs-3"></i>
                                </div>
                                <div class="mt-2 small fw-bold">Blended</div>
                            </div>

                            <!-- Webinars Icon -->
                            <div class="position-absolute" style="bottom: 20%; right: 10%;">
                                <div class="bg-white border border-3 border-info rounded-circle p-3 shadow-sm">
                                    <i class="bi bi-camera-video text-info fs-3"></i>
                                </div>
                                <div class="mt-2 small fw-bold">Webinars</div>
                            </div>

                            <!-- Connecting Lines -->
                            <div class="position-absolute top-50 start-50 translate-middle"
                                style="width: 100%; height: 100%; z-index: -1;">
                                <svg width="100%" height="100%" style="position: absolute; top: 0; left: 0;">
                                    <!-- Lines from center to each method -->
                                    <line x1="50%" y1="50%" x2="20%" y2="25%" stroke="#3498db" stroke-width="2"
                                        stroke-dasharray="5,5" opacity="0.5" />
                                    <line x1="50%" y1="50%" x2="80%" y2="25%" stroke="#2ecc71" stroke-width="2"
                                        stroke-dasharray="5,5" opacity="0.5" />
                                    <line x1="50%" y1="50%" x2="20%" y2="75%" stroke="#f39c12" stroke-width="2"
                                        stroke-dasharray="5,5" opacity="0.5" />
                                    <line x1="50%" y1="50%" x2="80%" y2="75%" stroke="#17a2b8" stroke-width="2"
                                        stroke-dasharray="5,5" opacity="0.5" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ps-lg-4">
                        <p class="lead mb-4">Our experts try and introduce new and innovative learning methods every
                            session. The methods adopted help absorb the content for a lifetime.</p>

                        <!-- Numbered Learning Methods -->
                        <div class="row g-3 mb-4">
                            <!-- Method 1 -->
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">1</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Instructor Led Training (ILT)</h5>
                                        <p class="text-muted mb-0">Traditional classroom-style learning with expert
                                            instructors providing real-time guidance and interaction.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 2 -->
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="badge bg-success rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">2</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Virtual Instructor Led Training (VILT)</h5>
                                        <p class="text-muted mb-0">Live online sessions with interactive virtual
                                            classrooms, enabling remote learning without compromising engagement.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 3 -->
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="badge bg-warning rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">3</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Blended Training</h5>
                                        <p class="text-muted mb-0">Optimal combination of online self-paced modules and
                                            offline instructor-led sessions for maximum flexibility and effectiveness.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 4 -->
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="badge bg-info rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">4</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">Webinars and Seminars</h5>
                                                <p class="text-muted mb-0">Industry insights and expert knowledge
                                                    sharing sessions covering latest trends and best practices.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lifetime Learning Benefit -->
                        <div class="card border-0 bg-primary bg-opacity-10 mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history text-primary fs-2 me-3"></i>
                                    <div>
                                        <h5 class="text-primary mb-1">Content for a Lifetime</h5>
                                        <p class="mb-0 small">Our methods ensure knowledge retention that lasts beyond
                                            the classroom, transforming learning into practical, applicable skills.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help Message -->
                        <div class="alert alert-primary border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-chat-dots-fill me-3 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Welcome to our site!</h5>
                                    <p class="mb-0">If you need help simply reply to this message, we are online and
                                        ready to help.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Popular Training Programs</h2>
                    <p class="text-muted">Explore our most sought-after courses</p>
                </div>
            </div>
            <div class="row g-4">
                <?php
                // Connect to database
                $conn = new mysqli("localhost", "root", "", "aspironet_db");
                
                // Check connection
                if ($conn->connect_error) {
                    // Fallback to default courses
                    $courses = [
                        ['id' => 1, 'title' => 'Full Stack Development', 'category' => 'Technology', 'duration' => '12 Weeks', 'mode' => 'Online', 'price' => 499.99, 'description' => 'Learn full stack web development'],
                        ['id' => 2, 'title' => 'Data Science & AI', 'category' => 'Technology', 'duration' => '16 Weeks', 'mode' => 'Hybrid', 'price' => 599.99, 'description' => 'Master data science and AI'],
                        ['id' => 3, 'title' => 'Cloud Computing (AWS/Azure)', 'category' => 'Technology', 'duration' => '10 Weeks', 'mode' => 'Online', 'price' => 449.99, 'description' => 'Cloud computing certification'],
                        ['id' => 4, 'title' => 'Leadership Excellence', 'category' => 'Corporate', 'duration' => '6 Weeks', 'mode' => 'Offline', 'price' => 399.99, 'description' => 'Leadership development program'],
                    ];
                } else {
                    // Get active courses from database
                    $sql = "SELECT * FROM courses WHERE status='active' ORDER BY created_at DESC LIMIT 4";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        $courses = [];
                        while($row = $result->fetch_assoc()) {
                            $courses[] = $row;
                        }
                    } else {
                        // Fallback to default courses
                        $courses = [
                            ['id' => 1, 'title' => 'Full Stack Development', 'category' => 'Technology', 'duration' => '12 Weeks', 'mode' => 'Online', 'price' => 499.99, 'description' => 'Learn full stack web development'],
                            ['id' => 2, 'title' => 'Data Science & AI', 'category' => 'Technology', 'duration' => '16 Weeks', 'mode' => 'Hybrid', 'price' => 599.99, 'description' => 'Master data science and AI'],
                            ['id' => 3, 'title' => 'Cloud Computing (AWS/Azure)', 'category' => 'Technology', 'duration' => '10 Weeks', 'mode' => 'Online', 'price' => 449.99, 'description' => 'Cloud computing certification'],
                            ['id' => 4, 'title' => 'Leadership Excellence', 'category' => 'Corporate', 'duration' => '6 Weeks', 'mode' => 'Offline', 'price' => 399.99, 'description' => 'Leadership development program'],
                        ];
                    }
                    $conn->close();
                }

                foreach ($courses as $course) {
                ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card course-card h-100">
                        <div class="card-body">
                            <span
                                class="badge bg-primary mb-2"><?php echo htmlspecialchars($course['category']); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="bi bi-clock"></i> <?php echo htmlspecialchars($course['duration']); ?><br>
                                <i class="bi bi-laptop"></i> <?php echo htmlspecialchars($course['mode']); ?><br>
                                <?php if(isset($course['price'])): ?>
                                <i class="bi bi-currency-dollar"></i> $<?php echo number_format($course['price'], 2); ?>
                                <?php endif; ?>
                            </p>
                            <?php if(isset($course['description'])): ?>
                            <p class="card-text small text-muted">
                                <?php echo substr(htmlspecialchars($course['description']), 0, 80); ?>...</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <?php if ($logged_in): ?>
                            <a href="#" class="btn btn-sm btn-primary w-100"
                                onclick="enrollCourse(<?php echo $course['id']; ?>, '<?php echo addslashes($course['title']); ?>')">
                                Enroll Now
                            </a>
                            <?php else: ?>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#authModal">
                                Sign In to Enroll
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="text-center mt-5">
                <a href="?page=training" class="btn btn-primary btn-lg px-5">
                    View All Courses <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">What Our Students Say</h2>
                    <p class="text-muted">Success stories from our alumni</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle me-3">JS</div>
                            <div>
                                <h5 class="mb-0">John Smith</h5>
                                <p class="text-muted small mb-0">Software Engineer</p>
                            </div>
                        </div>
                        <p class="mb-0">"The Full Stack Development course transformed my career. Landed a job at a tech
                            startup within 2 weeks of completion!"</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle me-3">SR</div>
                            <div>
                                <h5 class="mb-0">Sarah Johnson</h5>
                                <p class="text-muted small mb-0">Data Analyst</p>
                            </div>
                        </div>
                        <p class="mb-0">"Excellent instructors and hands-on projects. The Data Science course gave me
                            practical skills I use every day."</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle me-3">MR</div>
                            <div>
                                <h5 class="mb-0">Michael Roberts</h5>
                                <p class="text-muted small mb-0">IT Manager</p>
                            </div>
                        </div>
                        <p class="mb-0">"Sent my entire team for Leadership Excellence training. The ROI has been
                            incredible in terms of productivity."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php elseif ($page == 'about'): ?>
    <!-- About Us Page Content -->
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">About Aspironet Solutions</h1>
                    <p class="lead mb-4">Empowering individuals and organizations through innovative learning solutions
                        since 2010</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">Our Story</h2>
                    <p class="lead mb-4">Founded with a vision to bridge the digital skills gap, Aspironet Solutions has
                        been at the forefront of technology education and workforce development since our inception.</p>
                    <p class="mb-4">We recognized that traditional education systems were struggling to keep pace with
                        rapidly evolving technology landscapes. This gap between academic learning and industry
                        requirements inspired us to create immersive, practical learning experiences that prepare
                        individuals and organizations for the future.</p>
                    <p class="mb-4">Today, we stand as a trusted partner for both aspiring professionals seeking career
                        transformation and enterprises looking to build future-ready teams.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="?page=services" class="btn btn-primary">Our Services</a>
                        <a href="#" class="btn btn-outline-primary">Meet Our Team</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="./ASSETS/about.webp" alt="Our Story" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                                <i class="bi bi-bullseye text-primary fs-1"></i>
                            </div>
                            <h3 class="h2 mb-4">Our Mission</h3>
                            <p class="text-muted fs-5">To democratize access to cutting-edge technology education and
                                create pathways to meaningful careers in the digital economy.</p>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Make quality
                                    tech education accessible</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Bridge the
                                    industry-academia gap</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Foster
                                    innovation and continuous learning</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Build diverse
                                    and inclusive tech communities</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                                <i class="bi bi-eye text-success fs-1"></i>
                            </div>
                            <h3 class="h2 mb-4">Our Vision</h3>
                            <p class="text-muted fs-5">To be the global leader in transformative learning solutions that
                                empower individuals and organizations to thrive in the digital age.</p>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i> Shape the future of
                                    tech education</li>
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i> Create global impact
                                    through learning</li>
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i> Pioneer innovative
                                    learning methodologies</li>
                                <li class="mb-2"><i class="bi bi-star-fill text-warning me-2"></i> Build sustainable
                                    talent ecosystems</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Our Values</h2>
                    <p class="text-muted">The principles that guide everything we do</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-heart-fill text-primary fs-1"></i>
                        </div>
                        <h4 class="h4 mb-3">Learner Centric</h4>
                        <p class="text-muted">Every decision we make prioritizes learner success and growth.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-shield-check text-success fs-1"></i>
                        </div>
                        <h4 class="h4 mb-3">Excellence</h4>
                        <p class="text-muted">We deliver exceptional quality in content, delivery, and outcomes.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-people-fill text-warning fs-1"></i>
                        </div>
                        <h4 class="h4 mb-3">Collaboration</h4>
                        <p class="text-muted">We believe in partnerships that create mutual growth and success.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->


    <?php elseif ($page == 'services'): ?>
    <!-- Our Services Page Content -->
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Our Services</h1>
                    <p class="lead mb-4">Comprehensive solutions for individual and organizational growth</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">What We Offer</h2>
                    <p class="text-muted">Tailored solutions to meet diverse learning and development needs</p>
                </div>
            </div>

            <!-- Cloud Labs -->
            <div class="row align-items-center mb-5 py-4">
                <div class="col-lg-6">
                    <h2 class="h1 mb-4">Cloud Labs</h2>
                    <p class="lead mb-4">Efficient, flexible, scalable, collaborative, real-world simulations, seamless
                        for enhanced learning experiences.</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-primary p-2">Hands-on Practice</span>
                        <span class="badge bg-success p-2">Scalable Infrastructure</span>
                        <span class="badge bg-warning p-2">Collaborative Environment</span>
                        <span class="badge bg-info p-2">Real-world Scenarios</span>
                    </div>
                    <a href="?page=training" class="btn btn-primary btn-lg px-4">Explore Cloud Labs</a>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                        alt="Cloud Labs" class="img-fluid rounded shadow-lg">
                </div>
            </div>

            <!-- Enterprise Training -->
            <div class="row align-items-center mb-5 py-4 bg-light rounded-3">
                <div class="col-lg-6 order-lg-2">
                    <h2 class="h1 mb-4">Enterprise Training</h2>
                    <p class="mb-4">It's imperative for enterprises to take the learning initiative in order to sustain
                        continued growth and innovation. A future-ready enterprise adapts to technological advancements,
                        market changes, and customer expectations, ensuring long-term success in a competitive business
                        landscape.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Customized training
                            programs</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Industry-specific
                            curriculum</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Flexible delivery
                            models</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> ROI-focused outcomes
                        </li>
                    </ul>
                    <a href="?page=training" class="btn btn-success btn-lg px-4">Learn About Enterprise Solutions</a>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                        alt="Enterprise Training" class="img-fluid rounded shadow-lg">
                </div>
            </div>

            <!-- Up-Skilling -->
            <div class="row align-items-center py-4">
                <div class="col-lg-6">
                    <h2 class="h1 mb-4">Up-Skilling</h2>
                    <p class="lead mb-4">Help your organization embrace the newest, future-oriented trends and
                        technologies. Develop a pipeline for success across levels.</p>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-arrow-up-right-circle text-success me-2"></i> Emerging
                                    Technologies</li>
                                <li class="mb-2"><i class="bi bi-arrow-up-right-circle text-success me-2"></i>
                                    Leadership Development</li>
                                <li class="mb-2"><i class="bi bi-arrow-up-right-circle text-success me-2"></i> Digital
                                    Transformation</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-arrow-up-right-circle text-success me-2"></i> Technical
                                    Skills Enhancement</li>
                                <li class="mb-2"><i class="bi bi-arrow-up-right-circle text-success me-2"></i> Soft
                                    Skills Development</li>
                                <li class="mb-2"><i class="bi bi-arrow-up-right-circle text-success me-2"></i> Career
                                    Path Planning</li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="?page=training" class="btn btn-warning btn-lg px-4">Start Up-Skilling</a>
                        <a href="?page=training" class="btn btn-outline-primary btn-lg px-4">View Programs</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                        alt="Up-Skilling" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Learning Journey?</h2>
                    <p class="lead mb-4">Join thousands of professionals and organizations who have accelerated their
                        growth with our services.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="?page=training" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-book"></i> Browse Courses
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-5" data-bs-toggle="modal"
                            data-bs-target="#contactModal">
                            <i class="bi bi-chat-dots"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Complementary Services</h2>
                    <p class="text-muted">Complete solutions for your training and development needs</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-person-badge text-info fs-1"></i>
                            </div>
                            <h4 class="h4 mb-3">Career Guidance</h4>
                            <p class="text-muted">Personalized career counseling and roadmap development for
                                professionals at all levels.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-purple bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-gear text-purple fs-1"></i>
                            </div>
                            <h4 class="h4 mb-3">Custom Solutions</h4>
                            <p class="text-muted">Tailored training programs designed to meet specific organizational
                                goals and challenges.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-danger bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-award text-danger fs-1"></i>
                            </div>
                            <h4 class="h4 mb-3">Certification Prep</h4>
                            <p class="text-muted">Comprehensive preparation for industry-recognized certifications and
                                credentials.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php elseif ($page == 'training'): ?>
    <!-- Training Page Content -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Training Programs</h1>
                    <p class="lead mb-4">Cutting-edge courses designed for career advancement and skill development</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Our Training Categories</h2>
                    <p class="text-muted">Choose from our wide range of technology and corporate training programs</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-code-slash text-primary fs-1"></i>
                            </div>
                            <h3 class="card-title h4 mb-3">Technology Training</h3>
                            <p class="text-muted mb-4">Master the latest technologies and programming languages with
                                hands-on projects and industry-relevant curriculum.</p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Full Stack
                                    Development</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Data Science
                                    & AI/ML</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Cloud
                                    Computing (AWS/Azure/GCP)</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Cybersecurity
                                </li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> DevOps &
                                    CI/CD</li>
                            </ul>
                            <a href="#" class="btn btn-primary mt-3">View Technology Courses</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-people text-success fs-1"></i>
                            </div>
                            <h3 class="card-title h4 mb-3">Corporate Training</h3>
                            <p class="text-muted mb-4">Develop essential professional skills and leadership capabilities
                                for organizational success.</p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Leadership &
                                    Management</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Communication
                                    Skills</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Project
                                    Management</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Team Building
                                </li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Agile & Scrum
                                    Methodologies</li>
                            </ul>
                            <a href="#" class="btn btn-success mt-3">View Corporate Courses</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Courses -->
            <div class="row mt-5">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">All Available Courses</h2>
                </div>
            </div>
            <div class="row g-4">
                <?php
                // Connect to database
                $conn = new mysqli("localhost", "root", "", "aspironet_db");
                
                if (!$conn->connect_error) {
                    $sql = "SELECT * FROM courses WHERE status='active' ORDER BY created_at DESC";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($course = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card course-card h-100">
                        <div class="card-body">
                            <span
                                class="badge bg-primary mb-2"><?php echo htmlspecialchars($course['category']); ?></span>
                            <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="bi bi-clock"></i> <?php echo htmlspecialchars($course['duration']); ?><br>
                                <i class="bi bi-laptop"></i> <?php echo htmlspecialchars($course['mode']); ?><br>
                                <i class="bi bi-currency-dollar"></i> $<?php echo number_format($course['price'], 2); ?>
                            </p>
                            <p class="card-text small"><?php echo htmlspecialchars($course['description']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <?php if ($logged_in): ?>
                            <a href="#" class="btn btn-sm btn-primary w-100"
                                onclick="enrollCourse(<?php echo $course['id']; ?>)">
                                Enroll Now
                            </a>
                            <?php else: ?>
                            <a href="#" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#authModal">
                                Sign In to Enroll
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                        }
                    } else {
                        echo '<div class="col-12 text-center"><p class="text-muted">No courses available yet. Check back soon!</p></div>';
                    }
                    $conn->close();
                } else {
                    echo '<div class="col-12 text-center"><p class="text-muted">Unable to load courses. Please try again later.</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Training Features -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Why Choose Our Training?</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-person-video3 text-primary fs-1"></i>
                        </div>
                        <h4 class="h4 mb-3">Expert Instructors</h4>
                        <p class="text-muted">Learn from industry professionals with years of practical experience.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-laptop text-success fs-1"></i>
                        </div>
                        <h4 class="h4 mb-3">Hands-on Projects</h4>
                        <p class="text-muted">Gain practical experience through real-world projects and assignments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-award text-warning fs-1"></i>
                        </div>
                        <h4 class="h4 mb-3">Certification</h4>
                        <p class="text-muted">Earn industry-recognized certificates upon successful completion.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php elseif ($page == 'staffing'): ?>
    <!-- Staffing Page Content -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Staffing Solutions</h1>
                    <p class="lead mb-4">Connecting organizations with top talent for sustainable growth</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">Our Staffing Services</h2>
                    <p class="lead mb-4">We bridge the gap between talented professionals and organizations seeking
                        specialized skills.</p>
                    <p class="mb-4">With our extensive network and rigorous screening process, we ensure that you get
                        the right talent for your specific needs, whether it's for short-term projects or long-term
                        positions.</p>
                    <a href="#" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                        data-bs-target="#staffingModal">Request Staffing Services</a>
                </div>
                <div class="col-lg-6">
                    <img src="./ASSETS/teach.jpg" alt="Staffing Solutions" class="img-fluid rounded shadow-lg">
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-search text-primary fs-1"></i>
                            </div>
                            <h3 class="h4 mb-3">Talent Acquisition</h3>
                            <p class="text-muted">Find the right professionals for your organization with our
                                comprehensive recruitment process.</p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Technical
                                    Recruitment</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Executive
                                    Search</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Bulk Hiring
                                    Solutions</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Campus
                                    Recruitment</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-gear text-success fs-1"></i>
                            </div>
                            <h3 class="h4 mb-3">Workforce Management</h3>
                            <p class="text-muted">Optimize your human resources for maximum efficiency and productivity.
                            </p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Contract
                                    Staffing</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Permanent
                                    Placement</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Payroll
                                    Management</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Performance
                                    Management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staffing Process -->
            <div class="row mt-5">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Our Staffing Process</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <span class="text-primary fw-bold fs-4">1</span>
                        </div>
                        <h5>Requirement Analysis</h5>
                        <p class="small text-muted">Understanding your specific staffing needs and requirements</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <span class="text-success fw-bold fs-4">2</span>
                        </div>
                        <h5>Talent Sourcing</h5>
                        <p class="small text-muted">Screening and identifying suitable candidates from our database</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <span class="text-warning fw-bold fs-4">3</span>
                        </div>
                        <h5>Assessment</h5>
                        <p class="small text-muted">Rigorous technical and behavioral assessment of candidates</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <span class="text-info fw-bold fs-4">4</span>
                        </div>
                        <h5>Placement</h5>
                        <p class="small text-muted">Successful onboarding and post-placement support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Staffing Stats -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="display-4 fw-bold mb-2">2000+</h2>
                    <p>Professionals Placed</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="display-4 fw-bold mb-2">150+</h2>
                    <p>Client Companies</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="display-4 fw-bold mb-2">95%</h2>
                    <p>Client Retention Rate</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="display-4 fw-bold mb-2">48h</h2>
                    <p>Average Placement Time</p>
                </div>
            </div>
        </div>
    </section>

    <?php endif; ?>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white mb-4">
                        <i class="bi bi-lightbulb"></i> Aspironet Solutions
                    </h5>
                    <p class="text-light">Empowering learning and workforce excellence through innovative training and
                        staffing solutions.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-light"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-linkedin fs-5"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="?page=home" class="text-light text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="?page=about" class="text-light text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="?page=services" class="text-light text-decoration-none">Our
                                Services</a></li>
                        <li class="mb-2"><a href="?page=training" class="text-light text-decoration-none">Training</a>
                        </li>
                        <li class="mb-2"><a href="?page=staffing" class="text-light text-decoration-none">Staffing</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-white mb-4">Contact Info</h5>
                    <div class="onelap">
                        <ul class="list-unstyled text-light lap">
                            <li class="mb-2">
                                <i class="bi bi-telephone me-2"></i>
                                <a href="tel:+918077673418" class="text-light text-decoration-none">8077673418</a>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-envelope me-2"></i>
                                <a href="mailto:Sales@aspironet.in"
                                    class="text-light text-decoration-none">Sales@aspironet.in</a>,
                                <a href="mailto:Training@aspironet.in"
                                    class="text-light text-decoration-none">Training@aspironet.in</a>
                            </li>
                            <li class="mb-2"><i class="bi bi-clock me-2"></i> Mon - Fri: 9:00 AM - 6:00 PM</li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="footer-bottom text-center pt-4">
                <p class="text-light mb-0">&copy; <?php echo date('Y'); ?> Aspironet Solutions. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Authentication Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Welcome to Aspironet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="authTabs">
                        <li class="nav-item">
                            <button class="nav-link active" id="signin-tab" data-bs-toggle="tab"
                                data-bs-target="#signin">
                                Sign In
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup">
                                Sign Up
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content py-4">
                        <!-- Sign In Tab -->
                        <div class="tab-pane fade show active" id="signin">
                            <form id="signinForm">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                                </button>

                            </form>
                        </div>

                        <!-- Sign Up Tab -->
                        <div class="tab-pane fade" id="signup">
                            <form id="signupForm">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" required>
                                    <div class="form-text">We need this to contact you about your enrollment</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required minlength="6">
                                    <div class="form-text">At least 6 characters</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">User Type</label>
                                    <select class="form-select" name="user_type">
                                        <option value="student">Student</option>
                                        <option value="professional">Working Professional</option>
                                        <option value="corporate">Corporate</option>
                                    </select>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label" for="terms">I agree to the <a href="#"
                                            class="text-decoration-none">Terms & Conditions</a></label>
                                </div>
                                <button type="submit" class="btn btn-success w-100 mb-3">
                                    <i class="bi bi-person-plus"></i> Create Account
                                </button>



                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Us</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="contactForm">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Staffing Modal -->
    <div class="modal fade" id="staffingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Staffing Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="staffingForm">
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Staffing Needs</label>
                            <textarea class="form-control" rows="4" placeholder="Describe your staffing requirements..."
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of Positions</label>
                            <input type="number" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urgency</label>
                            <select class="form-select" required>
                                <option value="">Select urgency</option>
                                <option value="immediate">Immediate (Within 1 week)</option>
                                <option value="urgent">Urgent (1-2 weeks)</option>
                                <option value="standard">Standard (2-4 weeks)</option>
                                <option value="flexible">Flexible (1+ months)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Enrollment Modal -->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enroll in Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="enrollmentForm">
                        <input type="hidden" id="enrollCourseId" name="course_id">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="enrollName"
                                value="<?php echo htmlspecialchars($user_name); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="enrollEmail"
                                value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="enrollPhone" name="phone"
                                value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>" required>
                            <div class="form-text">We'll contact you on this number</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Selected Course</label>
                            <input type="text" class="form-control" id="enrollCourseTitle" readonly>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            After enrollment, we will send you a confirmation email and contact you for further details.
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-2"></i> Confirm Enrollment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">My Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="avatar-circle mx-auto mb-3"
                                style="width: 100px; height: 100px; font-size: 2.5rem;">
                                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                            </div>
                            <h5 id="profileName"><?php echo htmlspecialchars($user_name); ?></h5>
                            <p class="text-muted" id="profileEmail"><?php echo htmlspecialchars($user_email); ?></p>
                            <span class="badge bg-primary"
                                id="profileType"><?php echo htmlspecialchars($user_type); ?></span>
                        </div>
                        <div class="col-md-8">
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name" id="profileFormName"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" name="phone" id="profileFormPhone"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" id="profileFormAddress"
                                        rows="2"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control" name="bio" id="profileFormBio" rows="3"
                                        placeholder="Tell us about yourself..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Member Since</label>
                                    <input type="text" class="form-control" id="profileMemberSince" readonly>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Courses Modal -->
    <div class="modal fade" id="myCoursesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">My Courses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="coursesLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading your courses...</p>
                    </div>

                    <div id="coursesList" style="display: none;">
                        <!-- Courses will be loaded here -->
                    </div>

                    <div id="noCourses" class="text-center py-5" style="display: none;">
                        <i class="bi bi-book display-1 text-muted mb-3"></i>
                        <h5>No courses enrolled yet</h5>
                        <p class="text-muted">Browse our courses and start your learning journey!</p>
                        <a href="?page=training" class="btn btn-primary">Browse Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Success Modal -->
    <div class="modal fade" id="enrollmentSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        </div>
                        <h3>Enrollment Successful!</h3>
                        <p class="text-muted">You have successfully enrolled in the course</p>
                    </div>

                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h5 id="successCourseTitle"></h5>
                            <p class="mb-2"><strong>Enrollment Code:</strong> <span id="successEnrollmentCode"
                                    class="text-primary"></span></p>
                            <p class="mb-0"><small class="text-muted">We have sent an email to the company. They will
                                    contact you shortly.</small></p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Continue
                            Browsing</button>
                        <button type="button" class="btn btn-primary" onclick="viewMyCourses()">View My Courses</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Authentication JavaScript
    const API_BASE = 'auth_api.php';
    let currentCourseId = null;
    let currentCourseTitle = null;

    // Check authentication status on page load
    checkAuthStatus();

    async function checkAuthStatus() {
        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'check_auth'
                })
            });

            const result = await response.json();

            if (result.success && result.logged_in) {
                // User is logged in, update UI if needed
                console.log('User is logged in:', result.user);
            }
        } catch (error) {
            console.error('Auth check error:', error);
        }
    }

    // Handle signup form
    document.getElementById('signupForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Get form data directly from form elements
        const name = this.querySelector('[name="name"]').value;
        const email = this.querySelector('[name="email"]').value;
        const phone = this.querySelector('[name="phone"]').value;
        const password = this.querySelector('[name="password"]').value;
        const confirm_password = this.querySelector('[name="confirm_password"]').value;
        const user_type = this.querySelector('[name="user_type"]').value;

        // Validation
        if (!phone) {
            alert('Phone number is required!');
            return;
        }

        if (password !== confirm_password) {
            alert('Passwords do not match!');
            return;
        }

        if (password.length < 6) {
            alert('Password must be at least 6 characters!');
            return;
        }

        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'signup',
                    name: name,
                    email: email,
                    phone: phone,
                    password: password,
                    confirm_password: confirm_password,
                    user_type: user_type
                })
            });

            const result = await response.json();

            if (result.success) {
                location.reload(); // Reload to update navigation
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Signup error:', error);
            alert('Network error. Please try again.');
        }
    });

    // Handle signin form
    document.getElementById('signinForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'signin',
                    email: formData.get('email'),
                    password: formData.get('password')
                })
            });

            const result = await response.json();

            if (result.success) {
                location.reload(); // Reload to update navigation
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Network error. Please try again.');
        }
    });

    // Handle enrollment form
    document.getElementById('enrollmentForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'enroll_course',
                    course_id: formData.get('course_id'),
                    phone: formData.get('phone')
                })
            });

            const result = await response.json();

            if (result.success) {
                // Close enrollment modal
                const enrollmentModal = bootstrap.Modal.getInstance(document.getElementById(
                    'enrollmentModal'));
                enrollmentModal.hide();

                // Show success modal
                document.getElementById('successCourseTitle').textContent = currentCourseTitle;
                document.getElementById('successEnrollmentCode').textContent = result.enrollment_code;
                new bootstrap.Modal(document.getElementById('enrollmentSuccessModal')).show();

                // Reset form
                this.reset();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Network error. Please try again.');
        }
    });

    // Handle profile form
    document.getElementById('profileForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'update_profile',
                    name: formData.get('name'),
                    phone: formData.get('phone'),
                    address: formData.get('address'),
                    bio: formData.get('bio')
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('Profile updated successfully!');
                // Update session data
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            alert('Network error. Please try again.');
        }
    });

    // Enrollment function
    function enrollCourse(courseId, courseTitle) {
        if (!<?php echo (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) ? 'true' : 'false'; ?>) {
            $('#authModal').modal('show');
            return;
        }

        currentCourseId = courseId;
        currentCourseTitle = courseTitle;

        // Set course details in modal
        document.getElementById('enrollCourseId').value = courseId;
        document.getElementById('enrollCourseTitle').value = courseTitle;

        // Set current phone if available
        const userPhone = '<?php echo $_SESSION["user_phone"] ?? ""; ?>';
        if (userPhone) {
            document.getElementById('enrollPhone').value = userPhone;
        }

        // Show enrollment modal
        new bootstrap.Modal(document.getElementById('enrollmentModal')).show();
    }

    // View profile
    function viewProfile() {
        // Load profile data
        fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_profile'
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const profile = result.profile;

                    // Update form fields
                    document.getElementById('profileFormName').value = profile.name;
                    document.getElementById('profileFormPhone').value = profile.phone;
                    document.getElementById('profileFormAddress').value = profile.address || '';
                    document.getElementById('profileFormBio').value = profile.bio || '';
                    document.getElementById('profileMemberSince').value = profile.member_since;

                    // Update display
                    document.getElementById('profileName').textContent = profile.name;
                    document.getElementById('profileEmail').textContent = profile.email;
                    document.getElementById('profileType').textContent = profile.type;
                }
            })
            .catch(error => {
                console.error('Error loading profile:', error);
                alert('Error loading profile. Please try again.');
            });

        // Show profile modal
        new bootstrap.Modal(document.getElementById('profileModal')).show();
    }

    // View my courses
    function viewMyCourses() {
        // Show loading
        document.getElementById('coursesLoading').style.display = 'block';
        document.getElementById('coursesList').style.display = 'none';
        document.getElementById('noCourses').style.display = 'none';

        // Load courses
        fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_my_courses'
                })
            })
            .then(response => response.json())
            .then(result => {
                document.getElementById('coursesLoading').style.display = 'none';

                if (result.success && result.courses && result.courses.length > 0) {
                    const coursesList = document.getElementById('coursesList');
                    coursesList.innerHTML = '';

                    result.courses.forEach(course => {
                        const courseCard = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-book text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">${course.title}</h5>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-primary">${course.category}</span>
                                                <span class="badge bg-secondary">${course.duration}</span>
                                                <span class="badge bg-info">${course.mode}</span>
                                                <span class="badge ${course.enrollment_status === 'active' ? 'bg-success' : 'bg-warning'}">
                                                    ${course.enrollment_status}
                                                </span>
                                            </div>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar me-1"></i>
                                                Enrolled: ${new Date(course.enrollment_date).toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'short',
                                                    day: 'numeric'
                                                })}
                                                <span class="mx-2">•</span>
                                                <i class="bi bi-tag me-1"></i>
                                                Code: <strong>${course.enrollment_code}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="d-flex flex-column gap-2">
                                        <span class="fw-bold text-primary">$${parseFloat(course.price || 0).toFixed(2)}</span>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewCourseDetails(${course.id})">
                                            <i class="bi bi-eye me-1"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        coursesList.innerHTML += courseCard;
                    });

                    coursesList.style.display = 'block';
                } else {
                    document.getElementById('noCourses').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                document.getElementById('coursesLoading').style.display = 'none';
                document.getElementById('noCourses').style.display = 'block';
            });

        // Show courses modal
        new bootstrap.Modal(document.getElementById('myCoursesModal')).show();
    }

    // View course details
    function viewCourseDetails(courseId) {
        // In a real implementation, this would show course details
        window.location.href = 'site.php?page=training';
    }

    // Logout function
    function logout() {
        fetch(API_BASE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'logout'
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Redirect to home page
                    window.location.href = 'site.php?page=home';
                } else {
                    alert('Logout failed: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                // Even if there's an error, try to redirect
                window.location.href = 'site.php?page=home';
            });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add phone field to signup form if not exists
        const signupForm = document.getElementById('signupForm');
        if (signupForm && !signupForm.querySelector('input[name="phone"]')) {
            const emailField = signupForm.querySelector('input[name="email"]');
            const phoneField = document.createElement('div');
            phoneField.className = 'mb-3';
            phoneField.innerHTML = `
            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" name="phone" required>
            <div class="form-text">We need this to contact you about your enrollment</div>
        `;
            emailField.parentNode.insertBefore(phoneField, emailField.nextSibling);
        }

        // Update all enroll buttons
        document.querySelectorAll('.btn-primary').forEach(button => {
            if (button.textContent.includes('Enroll Now')) {
                const courseId = button.getAttribute('onclick')?.match(/enrollCourse\((\d+)/)?. [1];
                const card = button.closest('.card');
                const courseTitle = card?.querySelector('.card-title')?.textContent;
                if (courseId && courseTitle) {
                    button.setAttribute('onclick',
                        `enrollCourse(${courseId}, '${courseTitle.replace(/'/g, "\\'")}')`);
                }
            }
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>
</body>

</html>