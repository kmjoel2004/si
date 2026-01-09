<?php
// admin.php - COMPLETELY NEW VERSION
// admin.php - FIXED VERSION
require_once 'config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $conn = getDBConnection();
    
    // Check default admin first
    if ($email === "admin@aspironet.com" && $password === "admin123") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_name'] = "Administrator";
        $_SESSION['admin_email'] = $email;
        
        header("Location: admin_dashboard.php");
        exit();
    }
    
    // Check database
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($admin = $result->fetch_assoc()) {
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Aspironet Solutions</title>
    <link rel="icon" type="image" href="./ASSETS/fav.png">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        max-width: 400px;
        width: 100%;
    }

    .login-header {
        background: #2c3e50;
        color: white;
        padding: 40px 20px;
        text-align: center;
    }

    .login-header i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }

    .login-body {
        padding: 40px 30px;
    }

    .btn-admin {
        background: #2c3e50;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-admin:hover {
        background: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .form-control {
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .admin-info {
        background: #e8f4fc;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        font-size: 0.9rem;
    }

    .admin-info h6 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <i class="bi bi-shield-lock"></i>
            <h3 class="mb-0">Admin Portal</h3>
            <p class="mb-0">Aspironet Solutions</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="admin@aspironet.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter password" required>
                    </div>
                </div>
                <button type="submit" class="btn-admin mb-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login to Admin Panel
                </button>
            </form>

            <div class="admin-info">
                <h6><i class="bi bi-info-circle me-2"></i>Default Admin Credentials</h6>
                <p class="mb-1"><strong>Email:</strong> admin@aspironet.com</p>
                <p class="mb-0"><strong>Password:</strong> admin123</p>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none">
                    <i class="bi bi-arrow-left me-2"></i>Back to Main Website
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>