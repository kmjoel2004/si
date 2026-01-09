<?php
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

// Add phone number column to users table if not exists
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER email,
        ADD COLUMN IF NOT EXISTS address TEXT AFTER phone,
        ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) AFTER address,
        ADD COLUMN IF NOT EXISTS bio TEXT AFTER profile_picture";

if ($conn->query($sql) === TRUE) {
    echo "Users table updated successfully<br>";
} else {
    echo "Error updating users table: " . $conn->error . "<br>";
}

// Update enrollments table with more details
$sql = "ALTER TABLE enrollments 
        ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER user_id,
        ADD COLUMN IF NOT EXISTS email_sent BOOLEAN DEFAULT FALSE AFTER status,
        ADD COLUMN IF NOT EXISTS enrollment_code VARCHAR(50) AFTER email_sent,
        ADD COLUMN IF NOT EXISTS payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending' AFTER enrollment_code";

if ($conn->query($sql) === TRUE) {
    echo "Enrollments table updated successfully<br>";
} else {
    echo "Error updating enrollments table: " . $conn->error . "<br>";
}

// Create settings table for email configuration
$sql = "CREATE TABLE IF NOT EXISTS email_settings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    company_email VARCHAR(100) NOT NULL,
    email_subject VARCHAR(200) NOT NULL,
    email_template TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Email settings table created successfully<br>";
} else {
    echo "Error creating email settings table: " . $conn->error . "<br>";
}

// Insert default email settings
$default_email = "info@aspironet.com";
$sql = "INSERT INTO email_settings (company_email, email_subject, email_template) 
        VALUES ('$default_email', 'New Course Enrollment - Aspironet Solutions', 
        'A new student has enrolled in a course.\n\nStudent Details:\nName: {student_name}\nEmail: {student_email}\nPhone: {student_phone}\nCourse: {course_title}\nEnrollment Date: {enrollment_date}\n\nPlease contact the student to proceed with the enrollment process.')
        ON DUPLICATE KEY UPDATE company_email = '$default_email'";

if ($conn->query($sql) === TRUE) {
    echo "Email settings inserted successfully<br>";
} else {
    echo "Error inserting email settings: " . $conn->error . "<br>";
}

$conn->close();

echo "<h3>Database update completed successfully!</h3>";
echo "<p><a href='site.php'>Go to Website</a></p>";
?>