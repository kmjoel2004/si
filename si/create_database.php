<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS aspironet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->connect_error . "<br>";
}

// Select database
$conn->select_db("aspironet_db");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255),
    google_id VARCHAR(100),
    user_type ENUM('student', 'professional', 'corporate') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create courses table
$sql = "CREATE TABLE IF NOT EXISTS courses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    duration VARCHAR(50),
    mode VARCHAR(50),
    price DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Courses table created successfully<br>";
} else {
    echo "Error creating courses table: " . $conn->error . "<br>";
}

// Create enrollments table
$sql = "CREATE TABLE IF NOT EXISTS enrollments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    course_id INT(11) NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Enrollments table created successfully<br>";
} else {
    echo "Error creating enrollments table: " . $conn->error . "<br>";
}

// Insert default admin user
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (name, email, password, user_type) 
        VALUES ('Administrator', 'admin@aspironet.com', '$admin_password', 'student')
        ON DUPLICATE KEY UPDATE name='Administrator'";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created/updated successfully<br>";
} else {
    echo "Error creating admin user: " . $conn->error . "<br>";
}

// Insert some sample courses
$sample_courses = [
    "('Full Stack Development', 'Technology', 'Learn full stack web development with modern frameworks', '12 Weeks', 'Online', 499.99, 'active')",
    "('Data Science & AI', 'Technology', 'Master data science, machine learning and artificial intelligence', '16 Weeks', 'Hybrid', 599.99, 'active')",
    "('Cloud Computing (AWS/Azure)', 'Technology', 'Cloud computing certification and deployment strategies', '10 Weeks', 'Online', 449.99, 'active')",
    "('Leadership Excellence', 'Corporate', 'Develop leadership skills for organizational success', '6 Weeks', 'Offline', 399.99, 'active')",
    "('Cybersecurity Fundamentals', 'Technology', 'Learn essential cybersecurity concepts and practices', '8 Weeks', 'Online', 549.99, 'active')",
    "('Project Management', 'Corporate', 'Master project management methodologies and tools', '10 Weeks', 'Hybrid', 449.99, 'active')"
];

$sql = "INSERT INTO courses (title, category, description, duration, mode, price, status) VALUES " . implode(',', $sample_courses) . " ON DUPLICATE KEY UPDATE title=VALUES(title)";

if ($conn->query($sql) === TRUE) {
    echo "Sample courses inserted successfully<br>";
} else {
    echo "Error inserting courses: " . $conn->error . "<br>";
}

// Create admin settings table
$sql = "CREATE TABLE IF NOT EXISTS admin_settings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Admin settings table created successfully<br>";
} else {
    echo "Error creating admin settings table: " . $conn->error . "<br>";
}

// Insert default settings
$default_settings = [
    "('site_name', 'Aspironet Solutions')",
    "('contact_email', 'info@aspironet.com')",
    "('contact_phone', '(123) 456-7890')",
    "('site_address', '123 Tech Street, City, State 12345')",
    "('admin_email', 'admin@aspironet.com')"
];

$sql = "INSERT INTO admin_settings (setting_key, setting_value) VALUES " . implode(',', $default_settings) . " ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)";

if ($conn->query($sql) === TRUE) {
    echo "Default settings inserted successfully<br>";
} else {
    echo "Error inserting settings: " . $conn->error . "<br>";
}

$conn->close();

echo "<h3>Database setup completed successfully!</h3>";
echo "<p>Default Admin Login:</p>";
echo "<ul>";
echo "<li>Email: admin@aspironet.com</li>";
echo "<li>Password: admin123</li>";
echo "</ul>";
echo "<p><a href='admin.php'>Go to Admin Login</a> | <a href='site.php'>Go to Website</a></p>";
?>