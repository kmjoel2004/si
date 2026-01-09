<?php
// auth_api.php - COMPLETELY FIXED VERSION
require_once 'config.php';

header('Content-Type: application/json');

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

$action = $data['action'] ?? '';

// Function to sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

switch ($action) {
    case 'signup':
        handleSignup($data);
        break;
        
    case 'signin':
        handleSignin($data);
        break;
        
    case 'logout':
        handleLogout();
        break;
        
    case 'check_auth':
        checkAuth();
        break;
        
    case 'get_profile':
        getProfile();
        break;
        
    case 'update_profile':
        updateProfile($data);
        break;
        
    case 'enroll_course':
        enrollCourse($data);
        break;
        
    case 'get_my_courses':
        getMyCourses();
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function handleSignup($data) {
    $conn = getDBConnection();
    
    $name = sanitize($data['name'] ?? '');
    $email = sanitize($data['email'] ?? '');
    $phone = sanitize($data['phone'] ?? '');
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';
    $user_type = sanitize($data['user_type'] ?? 'student');
    
    // Validation - make phone optional for now
if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Name, email and password are required']);
    return;
}
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        return;
    }
    
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        return;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        return;
    }
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        $stmt->close();
        return;
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $user_type);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        
        // Set user session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_type'] = $user_type;
        $_SESSION['logged_in'] = true;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Registration successful!',
            'user' => [
                'id' => $user_id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'type' => $user_type
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}

function handleSignin($data) {
    $conn = getDBConnection();
    
    $email = sanitize($data['email'] ?? '');
    $password = $data['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        return;
    }
    
    // Use get_result() instead of bind_result()
    $stmt = $conn->prepare("SELECT id, name, email, phone, password, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Now $user contains all the fields as an associative array
        if (password_verify($password, $user['password'])) {
            // Set user session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['logged_in'] = true;
            
            // Clear admin session if exists
            $_SESSION['admin_logged_in'] = false;
            $_SESSION['admin_id'] = null;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful!',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'type' => $user['user_type']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
    
    $stmt->close();
    $conn->close();
}

function handleLogout() {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function checkAuth() {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        echo json_encode([
            'success' => true,
            'logged_in' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'phone' => $_SESSION['user_phone'],
                'type' => $_SESSION['user_type']
            ]
        ]);
    } else {
        echo json_encode(['success' => true, 'logged_in' => false]);
    }
}

function getProfile() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    // Use get_result() instead of bind_result()
    $stmt = $conn->prepare("SELECT name, email, phone, address, bio, user_type, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($profile = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'profile' => [
                'name' => $profile['name'],
                'email' => $profile['email'],
                'phone' => $profile['phone'],
                'address' => $profile['address'],
                'bio' => $profile['bio'],
                'type' => $profile['user_type'],
                'member_since' => date('F d, Y', strtotime($profile['created_at']))
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Profile not found']);
    }
    
    $stmt->close();
    $conn->close();
}

function updateProfile($data) {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    $name = sanitize($data['name'] ?? '');
    $phone = sanitize($data['phone'] ?? '');
    $address = sanitize($data['address'] ?? '');
    $bio = sanitize($data['bio'] ?? '');
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, address = ?, bio = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $phone, $address, $bio, $user_id);
    
    if ($stmt->execute()) {
        // Update session
        $_SESSION['user_name'] = $name;
        $_SESSION['user_phone'] = $phone;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}

function enrollCourse($data) {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        echo json_encode(['success' => false, 'message' => 'Please login to enroll']);
        return;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    $course_id = intval($data['course_id'] ?? 0);
    $phone = sanitize($data['phone'] ?? $_SESSION['user_phone'] ?? '');
    
    if ($course_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid course']);
        return;
    }
    
    if (empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'Phone number is required']);
        return;
    }
    
    // Check if already enrolled
    $stmt = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You are already enrolled in this course']);
        $stmt->close();
        return;
    }
    $stmt->close();
    
    // Get course details using get_result()
    $stmt = $conn->prepare("SELECT title FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($course = $result->fetch_assoc()) {
        $course_title = $course['title'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
        $stmt->close();
        return;
    }
    $stmt->close();
    
    // Generate enrollment code
    $enrollment_code = 'ENR-' . strtoupper(uniqid());
    
    // Insert enrollment
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, phone, enrollment_code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $course_id, $phone, $enrollment_code);
    
    if ($stmt->execute()) {
        // Update user phone if different
        if ($phone !== $_SESSION['user_phone']) {
            $update_stmt = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
            $update_stmt->bind_param("si", $phone, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
            $_SESSION['user_phone'] = $phone;
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Successfully enrolled in the course! We will contact you soon.',
            'enrollment_code' => $enrollment_code,
            'course_title' => $course_title
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Enrollment failed: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}

function getMyCourses() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT c.*, e.enrollment_date, e.status as enrollment_status, e.enrollment_code 
            FROM enrollments e 
            JOIN courses c ON e.course_id = c.id 
            WHERE e.user_id = ? 
            ORDER BY e.enrollment_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    
    echo json_encode(['success' => true, 'courses' => $courses]);
    
    $stmt->close();
    $conn->close();
}
?>