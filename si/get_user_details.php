<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die('Access denied');
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
    die('Database connection failed');
}

$user_id = intval($_GET['id'] ?? 0);

// Get user details with phone number
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Get user enrollments
    $enrollments_sql = "SELECT c.title, e.enrollment_date, e.status, e.phone as enrollment_phone 
                        FROM enrollments e 
                        JOIN courses c ON e.course_id = c.id 
                        WHERE e.user_id = $user_id 
                        ORDER BY e.enrollment_date DESC";
    $enrollments_result = $conn->query($enrollments_sql);
    ?>

<div class="user-details">
    <div class="text-center mb-4">
        <div class="avatar-circle mx-auto mb-3"
            style="width: 80px; height: 80px; background: #3498db; font-size: 2rem;">
            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
        </div>
        <h5><?php echo htmlspecialchars($user['name']); ?></h5>
        <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
        <?php if (!empty($user['phone'])): ?>
        <p class="text-muted">
            <i class="bi bi-telephone"></i>
            <?php echo htmlspecialchars($user['phone']); ?>
        </p>
        <?php endif; ?>
    </div>

    <div class="row mb-4">
        <div class="col-4">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-2">
                    <small class="text-muted">User Type</small>
                    <h6 class="mb-0"><?php echo ucfirst($user['user_type']); ?></h6>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-2">
                    <small class="text-muted">Member Since</small>
                    <h6 class="mb-0"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></h6>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-2">
                    <small class="text-muted">Phone</small>
                    <h6 class="mb-0">
                        <?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Not provided'; ?>
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($user['address'])): ?>
    <div class="mb-4">
        <h6>Address</h6>
        <p class="text-muted"><?php echo htmlspecialchars($user['address']); ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($user['bio'])): ?>
    <div class="mb-4">
        <h6>Bio</h6>
        <p class="text-muted"><?php echo htmlspecialchars($user['bio']); ?></p>
    </div>
    <?php endif; ?>

    <h6>Course Enrollments</h6>
    <?php if ($enrollments_result->num_rows > 0): ?>
    <div class="list-group">
        <?php while($enrollment = $enrollments_result->fetch_assoc()): ?>
        <div class="list-group-item border-0 px-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1"><?php echo htmlspecialchars($enrollment['title']); ?></h6>
                    <small class="text-muted">Enrolled:
                        <?php echo date('M d, Y', strtotime($enrollment['enrollment_date'])); ?></small>
                    <?php if (!empty($enrollment['enrollment_phone'])): ?>
                    <br>
                    <small class="text-muted">
                        <i class="bi bi-telephone"></i>
                        <?php echo htmlspecialchars($enrollment['enrollment_phone']); ?>
                    </small>
                    <?php endif; ?>
                </div>
                <span class="badge bg-<?php echo $enrollment['status'] == 'active' ? 'success' : 'secondary'; ?>">
                    <?php echo ucfirst($enrollment['status']); ?>
                </span>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        This user hasn't enrolled in any courses yet.
    </div>
    <?php endif; ?>
</div>

<?php
} else {
    echo '<div class="alert alert-danger">User not found.</div>';
}

$conn->close();
?>