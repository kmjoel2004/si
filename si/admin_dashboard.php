<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aspironet_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Handle course actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_course'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $category = $conn->real_escape_string($_POST['category']);
        $description = $conn->real_escape_string($_POST['description']);
        $duration = $conn->real_escape_string($_POST['duration']);
        $mode = $conn->real_escape_string($_POST['mode']);
        $price = floatval($_POST['price']);
        $status = 'active';
        
        $sql = "INSERT INTO courses (title, category, description, duration, mode, price, status) 
                VALUES ('$title', '$category', '$description', '$duration', '$mode', $price, '$status')";
        
        if ($conn->query($sql) === TRUE) {
            $course_message = "Course added successfully!";
            $message_type = "success";
        } else {
            $course_message = "Error: " . $conn->error;
            $message_type = "danger";
        }
    }
    
    if (isset($_POST['edit_course'])) {
        $id = intval($_POST['course_id']);
        $title = $conn->real_escape_string($_POST['edit_title']);
        $category = $conn->real_escape_string($_POST['edit_category']);
        $description = $conn->real_escape_string($_POST['edit_description']);
        $duration = $conn->real_escape_string($_POST['edit_duration']);
        $mode = $conn->real_escape_string($_POST['edit_mode']);
        $price = floatval($_POST['edit_price']);
        $status = $conn->real_escape_string($_POST['edit_status']);
        
        $sql = "UPDATE courses SET 
                title = '$title',
                category = '$category',
                description = '$description',
                duration = '$duration',
                mode = '$mode',
                price = $price,
                status = '$status'
                WHERE id = $id";
        
        if ($conn->query($sql) === TRUE) {
            $course_message = "Course updated successfully!";
            $message_type = "success";
        } else {
            $course_message = "Error: " . $conn->error;
            $message_type = "danger";
        }
    }
}

// Handle course deletion
if (isset($_GET['delete_course'])) {
    $id = intval($_GET['delete_course']);
    $sql = "DELETE FROM courses WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $course_message = "Course deleted successfully!";
        $message_type = "success";
    } else {
        $course_message = "Error deleting course: " . $conn->error;
        $message_type = "danger";
    }
}

// Handle course status toggle
if (isset($_GET['toggle_course'])) {
    $id = intval($_GET['toggle_course']);
    $sql = "UPDATE courses SET status = IF(status='active', 'inactive', 'active') WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $course_message = "Course status updated!";
        $message_type = "success";
    } else {
        $course_message = "Error updating status: " . $conn->error;
        $message_type = "danger";
    }
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $id = intval($_GET['delete_user']);
    $sql = "DELETE FROM users WHERE id = $id AND email != 'admin@aspironet.com'";
    
    if ($conn->query($sql) === TRUE) {
        $user_message = "User deleted successfully!";
        $user_message_type = "success";
    } else {
        $user_message = "Error deleting user: " . $conn->error;
        $user_message_type = "danger";
    }
}
// Handle enrollment status update
if (isset($_GET['update_enrollment'])) {
    $id = intval($_GET['update_enrollment']);
    $status = $conn->real_escape_string($_GET['status'] ?? 'pending');
    
    $sql = "UPDATE enrollments SET status = '$status' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $enrollment_message = "Enrollment status updated to $status!";
        $enrollment_message_type = "success";
    } else {
        $enrollment_message = "Error updating enrollment: " . $conn->error;
        $enrollment_message_type = "danger";
    }
}

// Handle enrollment deletion
if (isset($_GET['delete_enrollment'])) {
    $id = intval($_GET['delete_enrollment']);
    $sql = "DELETE FROM enrollments WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $enrollment_message = "Enrollment deleted successfully!";
        $enrollment_message_type = "success";
    } else {
        $enrollment_message = "Error deleting enrollment: " . $conn->error;
        $enrollment_message_type = "danger";
    }
}

// Get all courses
$courses_result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");

// Get all users (excluding admin)
$users_result = $conn->query("SELECT * FROM users WHERE email != 'admin@aspironet.com' ORDER BY created_at DESC");

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE email != 'admin@aspironet.com'")->fetch_assoc()['count'];
$total_courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$active_courses = $conn->query("SELECT COUNT(*) as count FROM courses WHERE status='active'")->fetch_assoc()['count'];
$today_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE() AND email != 'admin@aspironet.com'")->fetch_assoc()['count'];
$total_enrollments = $conn->query("SELECT COUNT(*) as count FROM enrollments")->fetch_assoc()['count'];

// Get course for editing
$edit_course = null;
if (isset($_GET['edit_course'])) {
    $id = intval($_GET['edit_course']);
    $result = $conn->query("SELECT * FROM courses WHERE id = $id");
    if ($result->num_rows > 0) {
        $edit_course = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Aspironet Solutions</title>
    <link rel="icon" type="image" href="./ASSETS/fav.png">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    :root {
        --primary: #2c3e50;
        --secondary: #3498db;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow-x: hidden;
    }

    /* Phone link styling */
    a[href^="tel:"] {
        color: #2c3e50;
        text-decoration: none;
        transition: color 0.3s;
    }

    a[href^="tel:"]:hover {
        color: #3498db;
        text-decoration: underline;
    }

    /* User details modal improvements */
    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-weight: bold;
    }

    .sidebar {
        background: var(--primary);
        color: white;
        min-height: 100vh;
        position: fixed;
        width: 250px;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .sidebar-logo {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .sidebar-logo h4 {
        margin: 0;
        font-weight: 600;
    }

    .sidebar-logo small {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .sidebar-menu .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
        margin: 5px 0;
        border-radius: 0;
        border-left: 3px solid transparent;
        transition: all 0.3s;
    }

    .sidebar-menu .nav-link:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        border-left: 3px solid var(--secondary);
    }

    .sidebar-menu .nav-link.active {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        border-left: 3px solid var(--secondary);
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
        transition: all 0.3s;
    }

    .topbar {
        background: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-text {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .table-responsive {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: var(--primary);
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-student {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-professional {
        background: #fff3cd;
        color: #856404;
    }

    .badge-corporate {
        background: #d1ecf1;
        color: #0c5460;
    }

    .btn-action {
        padding: 5px 10px;
        font-size: 0.8rem;
        margin: 2px;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: none;
        padding: 25px 30px 15px;
    }

    .modal-body {
        padding: 15px 30px 30px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .chart-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    /* Mobile responsive */
    @media (max-width: 992px) {
        .sidebar {
            margin-left: -250px;
        }

        .main-content {
            margin-left: 0;
        }

        .sidebar.mobile-show {
            margin-left: 0;
        }
    }

    /* Animations */
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

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <h4><i class="bi bi-shield-lock"></i> Admin Panel</h4>
            <small>Aspironet Solutions</small>
        </div>

        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#dashboard">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#users">
                        <i class="bi bi-people me-2"></i> Users
                        <span class="badge bg-primary float-end"><?php echo $total_users; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#courses">
                        <i class="bi bi-book me-2"></i> Courses
                        <span class="badge bg-success float-end"><?php echo $total_courses; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#add-course">
                        <i class="bi bi-plus-circle me-2"></i> Add Course
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#enrollments">
                        <i class="bi bi-clipboard-check me-2"></i> Enrollments
                        <span class="badge bg-warning float-end"><?php echo $total_enrollments; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=home" target="_blank">
                        <i class="bi bi-eye me-2"></i> View Site
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="?logout">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer text-center p-3" style="position: absolute; bottom: 0; width: 100%;">
            <small>Logged in as: <strong><?php echo $_SESSION['admin_name']; ?></strong></small>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="topbar">
            <div>
                <button class="btn btn-outline-primary d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 d-inline ms-2">Welcome, <?php echo $_SESSION['admin_name']; ?>!</h5>
            </div>
            <div>
                <a href="site.php" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-house"></i> Home
                </a>
                <a href="?logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>

        <!-- Dashboard Section -->
        <section id="dashboard" class="fade-in">
            <h4 class="mb-4">Dashboard Overview</h4>

            <?php if (isset($course_message)): ?>
            <div class="alert alert-<?php echo $message_type ?? 'info'; ?> alert-dismissible fade show" role="alert">
                <?php echo $course_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (isset($user_message)): ?>
            <div class="alert alert-<?php echo $user_message_type ?? 'info'; ?> alert-dismissible fade show"
                role="alert">
                <?php echo $user_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-number"><?php echo $total_users; ?></div>
                        <div class="stat-text">Total Users</div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="stat-number"><?php echo $total_courses; ?></div>
                        <div class="stat-text">Total Courses</div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $active_courses; ?></div>
                        <div class="stat-text">Active Courses</div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                        <div class="stat-number"><?php echo $today_users; ?></div>
                        <div class="stat-text">Today's Registrations</div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="chart-container">
                        <h5 class="mb-3">User Registrations (Last 7 Days)</h5>
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="chart-container">
                        <h5 class="mb-3">User Types</h5>
                        <canvas id="userTypesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="stat-card">
                        <h5 class="mb-3">Quick Actions</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#add-course" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Add New Course
                            </a>
                            <a href="#courses" class="btn btn-success">
                                <i class="bi bi-pencil-square me-2"></i> Manage Courses
                            </a>
                            <a href="#users" class="btn btn-info">
                                <i class="bi bi-people me-2"></i> Manage Users
                            </a>
                            <a href="index.php" target="_blank" class="btn btn-warning">
                                <i class="bi bi-eye me-2"></i> Preview Site
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Users Section -->
        <!-- Users Section -->
        <section id="users" class="fade-in" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>User Management</h4>
                <span class="badge bg-primary">Total: <?php echo $total_users; ?></span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Type</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if (!empty($user['phone'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($user['phone']); ?>"
                                    class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>
                                    <?php echo htmlspecialchars($user['phone']); ?>
                                </a>
                                <?php else: ?>
                                <span class="text-muted">Not provided</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-status badge-<?php echo $user['user_type']; ?>">
                                    <?php echo ucfirst($user['user_type']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="viewUserDetails(<?php echo $user['id']; ?>)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="?delete_user=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Courses Section -->
        <section id="courses" class="fade-in" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Course Management</h4>
                <div>
                    <span class="badge bg-success me-2">Active: <?php echo $active_courses; ?></span>
                    <span class="badge bg-secondary">Total: <?php echo $total_courses; ?></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Duration</th>
                            <th>Mode</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $courses_result->data_seek(0); // Reset pointer
                        while($course = $courses_result->fetch_assoc()): 
                        ?>
                        <tr>
                            <td>#<?php echo $course['id']; ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo $course['category']; ?></span>
                            </td>
                            <td><?php echo $course['duration']; ?></td>
                            <td><?php echo $course['mode']; ?></td>
                            <td>$<?php echo number_format($course['price'], 2); ?></td>
                            <td>
                                <span
                                    class="badge-status <?php echo $course['status'] == 'active' ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo $course['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="?edit_course=<?php echo $course['id']; ?>#add-course"
                                        class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?toggle_course=<?php echo $course['id']; ?>"
                                        class="btn btn-outline-warning">
                                        <?php echo $course['status'] == 'active' ? '<i class="bi bi-pause"></i>' : '<i class="bi bi-play"></i>'; ?>
                                    </a>
                                    <a href="?delete_course=<?php echo $course['id']; ?>" class="btn btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this course?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Add/Edit Course Section -->
        <section id="add-course" class="fade-in" style="display: none;">
            <h4 class="mb-4"><?php echo $edit_course ? 'Edit Course' : 'Add New Course'; ?></h4>

            <div class="stat-card">
                <form method="POST" action="">
                    <?php if ($edit_course): ?>
                    <input type="hidden" name="course_id" value="<?php echo $edit_course['id']; ?>">
                    <input type="hidden" name="edit_course" value="1">
                    <?php else: ?>
                    <input type="hidden" name="add_course" value="1">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course Title</label>
                            <input type="text" class="form-control"
                                name="<?php echo $edit_course ? 'edit_title' : 'title'; ?>"
                                value="<?php echo $edit_course ? htmlspecialchars($edit_course['title']) : ''; ?>"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select"
                                name="<?php echo $edit_course ? 'edit_category' : 'category'; ?>" required>
                                <option value="Technology"
                                    <?php echo ($edit_course && $edit_course['category'] == 'Technology') ? 'selected' : ''; ?>>
                                    Technology</option>
                                <option value="Corporate"
                                    <?php echo ($edit_course && $edit_course['category'] == 'Corporate') ? 'selected' : ''; ?>>
                                    Corporate</option>
                                <option value="Business"
                                    <?php echo ($edit_course && $edit_course['category'] == 'Business') ? 'selected' : ''; ?>>
                                    Business</option>
                                <option value="Leadership"
                                    <?php echo ($edit_course && $edit_course['category'] == 'Leadership') ? 'selected' : ''; ?>>
                                    Leadership</option>
                                <option value="Soft Skills"
                                    <?php echo ($edit_course && $edit_course['category'] == 'Soft Skills') ? 'selected' : ''; ?>>
                                    Soft Skills</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control"
                            name="<?php echo $edit_course ? 'edit_description' : 'description'; ?>" rows="3"
                            required><?php echo $edit_course ? htmlspecialchars($edit_course['description']) : ''; ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control"
                                name="<?php echo $edit_course ? 'edit_duration' : 'duration'; ?>"
                                value="<?php echo $edit_course ? htmlspecialchars($edit_course['duration']) : ''; ?>"
                                placeholder="e.g., 12 Weeks" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mode</label>
                            <select class="form-select" name="<?php echo $edit_course ? 'edit_mode' : 'mode'; ?>"
                                required>
                                <option value="Online"
                                    <?php echo ($edit_course && $edit_course['mode'] == 'Online') ? 'selected' : ''; ?>>
                                    Online</option>
                                <option value="Offline"
                                    <?php echo ($edit_course && $edit_course['mode'] == 'Offline') ? 'selected' : ''; ?>>
                                    Offline</option>
                                <option value="Hybrid"
                                    <?php echo ($edit_course && $edit_course['mode'] == 'Hybrid') ? 'selected' : ''; ?>>
                                    Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" class="form-control"
                                name="<?php echo $edit_course ? 'edit_price' : 'price'; ?>"
                                value="<?php echo $edit_course ? $edit_course['price'] : ''; ?>" step="0.01" min="0"
                                required>
                        </div>
                    </div>

                    <?php if ($edit_course): ?>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="edit_status" required>
                            <option value="active" <?php echo $edit_course['status'] == 'active' ? 'selected' : ''; ?>>
                                Active</option>
                            <option value="inactive"
                                <?php echo $edit_course['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-<?php echo $edit_course ? 'check-circle' : 'plus-circle'; ?> me-2"></i>
                            <?php echo $edit_course ? 'Update Course' : 'Add Course'; ?>
                        </button>
                        <?php if ($edit_course): ?>
                        <a href="#add-course" class="btn btn-outline-secondary">Cancel Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>

        <!-- Enrollments Section -->
        <!-- Enrollments Section -->
        <section id="enrollments" class="fade-in" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Course Enrollments</h4>
                <span class="badge bg-warning">Total: <?php echo $total_enrollments; ?></span>
            </div>

            <div class="stat-card">
                <?php
        // Get all enrollments with user and course details
        $enrollments_sql = "SELECT e.*, u.name as user_name, u.email, u.user_type, 
                                   c.title as course_title, c.category, c.price
                           FROM enrollments e
                           JOIN users u ON e.user_id = u.id
                           JOIN courses c ON e.course_id = c.id
                           ORDER BY e.enrollment_date DESC";
        $enrollments_result = $conn->query($enrollments_sql);
        
        if ($enrollments_result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Enrollment ID</th>
                                <th>User</th>
                                <th>Course</th>
                                <th>Enrollment Date</th>
                                <th>Status</th>
                                <th>Enrollment Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($enrollment = $enrollments_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $enrollment['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2"
                                            style="width: 35px; height: 35px; font-size: 0.8rem;">
                                            <?php echo strtoupper(substr($enrollment['user_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                <?php echo htmlspecialchars($enrollment['user_name']); ?></div>
                                            <small
                                                class="text-muted"><?php echo htmlspecialchars($enrollment['email']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($enrollment['course_title']); ?>
                                    </div>
                                    <small class="text-muted"><?php echo $enrollment['category']; ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($enrollment['enrollment_date'])); ?></td>
                                <td>
                                    <span class="badge-status <?php 
                                    if($enrollment['status'] == 'active') echo 'badge-active';
                                    elseif($enrollment['status'] == 'completed') echo 'badge bg-success';
                                    elseif($enrollment['status'] == 'cancelled') echo 'badge bg-danger';
                                    else echo 'badge bg-secondary';
                                ?>">
                                        <?php echo ucfirst($enrollment['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <code><?php echo $enrollment['enrollment_code']; ?></code>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary"
                                            onclick="viewEnrollmentDetails(<?php echo $enrollment['id']; ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-warning"
                                            onclick="updateEnrollmentStatus(<?php echo $enrollment['id']; ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger"
                                            onclick="deleteEnrollment(<?php echo $enrollment['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Enrollment Statistics -->
                <div class="row mt-4">
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center py-3">
                                <h5 class="text-primary mb-1">
                                    <?php 
                                $pending_sql = "SELECT COUNT(*) as count FROM enrollments WHERE status = 'pending'";
                                $pending_count = $conn->query($pending_sql)->fetch_assoc()['count'];
                                echo $pending_count;
                                ?>
                                </h5>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center py-3">
                                <h5 class="text-success mb-1">
                                    <?php 
                                $active_sql = "SELECT COUNT(*) as count FROM enrollments WHERE status = 'active'";
                                $active_count = $conn->query($active_sql)->fetch_assoc()['count'];
                                echo $active_count;
                                ?>
                                </h5>
                                <small class="text-muted">Active</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center py-3">
                                <h5 class="text-info mb-1">
                                    <?php 
                                $completed_sql = "SELECT COUNT(*) as count FROM enrollments WHERE status = 'completed'";
                                $completed_count = $conn->query($completed_sql)->fetch_assoc()['count'];
                                echo $completed_count;
                                ?>
                                </h5>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center py-3">
                                <h5 class="text-danger mb-1">
                                    <?php 
                                $cancelled_sql = "SELECT COUNT(*) as count FROM enrollments WHERE status = 'cancelled'";
                                $cancelled_count = $conn->query($cancelled_sql)->fetch_assoc()['count'];
                                echo $cancelled_count;
                                ?>
                                </h5>
                                <small class="text-muted">Cancelled</small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-check display-1 text-muted mb-3"></i>
                    <h5>No enrollments yet</h5>
                    <p class="text-muted">Users will appear here once they enroll in courses.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- User Details Modal -->
        <div class="modal fade" id="userDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="userDetailsContent">
                        Loading...
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-show');
        });

        // Navigation between sections
        document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();

                    // Remove active class from all links
                    document.querySelectorAll('.sidebar-menu .nav-link').forEach(l => {
                        l.classList.remove('active');
                    });

                    // Add active class to clicked link
                    this.classList.add('active');

                    // Hide all sections
                    document.querySelectorAll('section').forEach(section => {
                        section.style.display = 'none';
                    });

                    // Show selected section
                    const targetId = this.getAttribute('href').substring(1);
                    document.getElementById(targetId).style.display = 'block';

                    // On mobile, close sidebar after clicking
                    if (window.innerWidth < 992) {
                        document.getElementById('sidebar').classList.remove('mobile-show');
                    }
                }
            });
        });

        // Show dashboard by default
        document.getElementById('dashboard').style.display = 'block';

        // If editing a course, show that section
        <?php if ($edit_course): ?>
        document.querySelector('a[href="#add-course"]').click();
        <?php endif; ?>

        // View user details
        function viewUserDetails(userId) {
            fetch(`get_user_details.php?id=${userId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('userDetailsContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('userDetailsModal')).show();
                })
                .catch(error => {
                    document.getElementById('userDetailsContent').innerHTML =
                        '<div class="alert alert-danger">Error loading user details.</div>';
                    new bootstrap.Modal(document.getElementById('userDetailsModal')).show();
                });
        }

        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Registrations Chart
            const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
            new Chart(registrationsCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Registrations',
                        data: [12, 19, 8, 15, 22, 18, 25],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // User Types Chart
            const userTypesCtx = document.getElementById('userTypesChart').getContext('2d');
            new Chart(userTypesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Students', 'Professionals', 'Corporate'],
                    datasets: [{
                        data: [65, 25, 10],
                        backgroundColor: [
                            '#3498db',
                            '#2ecc71',
                            '#f39c12'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Confirm before deleting
        document.querySelectorAll('a[href*="delete"]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        });
        // Enrollment functions
        function viewEnrollmentDetails(enrollmentId) {
            alert('View enrollment details for ID: ' + enrollmentId);
            // You can implement a modal to show details
        }

        function updateEnrollmentStatus(enrollmentId) {
            const newStatus = prompt('Enter new status (pending/active/completed/cancelled):');
            if (newStatus && ['pending', 'active', 'completed', 'cancelled'].includes(newStatus.toLowerCase())) {
                if (confirm('Update enrollment status to ' + newStatus + '?')) {
                    window.location.href = '?update_enrollment=' + enrollmentId + '&status=' + newStatus.toLowerCase();
                }
            }
        }

        function deleteEnrollment(enrollmentId) {
            if (confirm('Are you sure you want to delete this enrollment?')) {
                window.location.href = '?delete_enrollment=' + enrollmentId;
            }
        }
        </script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>