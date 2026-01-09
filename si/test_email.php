<?php
// test_email.php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    die('Access denied');
}

// Test email function
function sendTestEmail($to, $subject, $message) {
    $headers = "From: noreply@aspironet.com\r\n";
    $headers .= "Reply-To: test@aspironet.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    if (mail($to, $subject, $message, $headers)) {
        return "Email sent successfully to $to";
    } else {
        return "Failed to send email";
    }
}

// Test
echo sendTestEmail('your-email@example.com', 'Test Email from Aspironet', 'This is a test email from the enrollment system.');
?>